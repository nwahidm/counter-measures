<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Intrusion\IntrusionResult;
use App\Models\Intrusion\IntrusionTargetEnv;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class UserController extends Controller
{
    public function __construct()
    {
        $this->pathFile = '/user/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();

        $data = User::when(!$user->hasRole(['superadmin', 'admin-kejagung']), function($q) use ($user) {
                        $q->where('users.id_satker', $user->id_satker);
                    })
                    ->leftJoin('master_satker', 'master_satker.id_satker', '=', 'users.id_satker')
                    ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                    ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->select(['users.id as id', 'users.email as email', 'users.name as name', 'users.is_active', 'roles.name as role', 'master_satker.nama_satker as nama_satker', 'master_satker.id_satker'])
                    ->paginate(10);

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:64',
            'username' => 'required|string|unique:users,username|max:64',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|max:64',
            'role' => 'required',
            'satker' => 'required',
            'nik' => 'required|string|max:64',
            'nip' => 'required|string|max:32',
            'avatar' => 'nullable|mimes:jpeg,png,jpg|max:2048'
        ]);

        $satker = MasterSatker::findOrFail($request->satker);

        $avatarFilename = null;
        $folderPath = public_path('avatars/');
        if ($request->has('avatar')) {
            $image = $request->file('avatar');

            $avatarFilename = $request->username . '.'. $image->getClientOriginalExtension();
            $image->move($folderPath, $avatarFilename);
            $avatarFilename = 'avatars/' . $avatarFilename;
        }

        $user = User::create([
            'id_satker' => $satker->id_satker,
            'kode_satker' => $satker->kode_satker,
            'tipe_satker' => $satker->tipe_satker,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'nik' => $request->nik,
            'nip' => $request->nip,
            'password' =>  bcrypt($request->password),
            'profile' => $avatarFilename
        ]);

        $user->syncRoles($request->role);

        return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'User berhasil disimpan',
                "data" => $user,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
    }

    public function show(Request $request, $id)
    {
        $data = User::where('users.id', $id)
                    ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                    ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->select(['users.id as id', 'users.email as email', 'users.name as name', 'users.is_active', 'roles.name as role',  'users.*'])
                    ->with('satker')
                    ->first();

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:64',
            'username' => 'required|string|max:64'.$id,
            'email' => 'nullable|email|unique:users,email,'.$id,
            'role' => 'required',
            'satker' => 'required',
            'nik' => 'required|string|max:64',
            'nip' => 'required|string|max:32',
            'avatar' => 'nullable|mimes:jpeg,png,jpg|max:2048'
        ]);

        $satker = MasterSatker::findOrFail($request->satker);
        $data = User::findOrFail($id);

        if ($data->roles->first()->name != 'superadmin' && $request->role == 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role ini');
        }

        $avatarFilename = $data->profile;
        $folderPath = public_path('avatars/');
        if ($request->has('avatar')) {
            // remove prev avatar if exists
            if ($data->profile) {
                $existingImagePath = $data->profile;
                if (file_exists($folderPath . $existingImagePath)) {
                    unlink($folderPath . $existingImagePath);
                }
            }

            $image = $request->file('avatar');

            $avatarFilename = $request->username . '.'. $image->getClientOriginalExtension();
            $image->move($folderPath, $avatarFilename);
            $avatarFilename = 'avatars/' . $avatarFilename;
        }

        $data->update([
            'id_satker' => $satker->id_satker,
            'kode_satker' => $satker->kode_satker,
            'tipe_satker' => $satker->tipe_satker,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'nik' => $request->nik,
            'nip' => $request->nip,
            'password' => $request->password ? bcrypt($request->password) : $data->password,
            'is_active' => $request->is_active ?? false,
            'profile' => $avatarFilename
        ]);

        $data->syncRoles($request->role);
        
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil disimpan',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function destroy($id, Request $request)
    {
        $user = Auth::guard('api')->user();

        if($id == $user->id){
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Cannot delete yourself',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        
        $user = User::findOrFail($id);

        // remove avatar
        if ($user->profile) {
            $imagePaths = $user->profile;

            if (file_exists($imagePaths)) {
                unlink($imagePaths);
            }
        }

        $user->delete();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }
}
