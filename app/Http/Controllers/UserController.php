<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\KontenType;
use App\Helpers\FileHelper;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\AksesDownload;
use App\DataTables\UserDataTable;
use App\Services\UserRoleService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Snowfire\Beautymail\Beautymail;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\password;
use Spatie\Permission\Models\Permission;

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
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('backoffice.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::when(!auth()->user()->hasRole('superadmin'), function ($q) {
                        $q->where('name', '<>', 'superadmin');
                    })
                    ->orderBy('name', 'ASC')->get();
        return view('backoffice.user.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

        return redirect()->route('user.index')->with(["success" => "Data berhasil ditambah."]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $role = Role::orderBy('name', 'ASC')->get();

        return view('backoffice.user.show', compact('user', 'role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data = User::findOrFail($id);
        $role = Role::when(!auth()->user()->hasRole('superadmin'), function ($q) {
                        $q->where('name', '<>', 'superadmin');
                    })
                    ->orderBy('name', 'ASC')->get();
        $image = $data->profile ? public_path($data->profile) : null;
        $isProfile = false;

        return view('backoffice.user.edit', compact('data', 'role', 'image', 'isProfile'));
    }

    // editProfile
    public function editProfile($id){
        if($id != auth()->user()->id){
            return redirect()->back()->withErrors('You Cannot Edit Others Profile');
        }

        $data = User::findOrFail($id);
        $role = Role::when(!auth()->user()->hasRole('superadmin'), function ($q) {
                        if(auth()->user()->hasRole('operator')){
                            $q->where('name', 'operator');
                        } else{
                            $q->where('name', '<>', 'superadmin');
                        }
                    })
                    ->orderBy('name', 'ASC')->get();
        $image = $data->profile ? public_path($data->profile) : null;
        $isProfile = true;

        return view('backoffice.user.edit', compact('data', 'role', 'image', 'isProfile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
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
        // $data->syncPermissions($request->permission);
        if($request->isProfile){
            return redirect()->route('dashboard')->with(["success" => "Data berhasil diupdate"]);
        } else{
            return redirect()->route('user.index')->with(["success" => "Data berhasil diupdate"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if($id == auth()->user()->id){
            return redirect()->back()->with('error', 'Cannot delete yourself');
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

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }
}
