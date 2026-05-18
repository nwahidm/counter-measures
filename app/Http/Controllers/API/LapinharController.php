<?php

namespace App\Http\Controllers\API;

use ResponseApi;
use App\Models\LapInHar;
use App\Models\EsignVerifyData;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\WilayahSatker;
use Mpdf\Config\FontVariables;
use Mpdf\Config\ConfigVariables;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use App\Events\KinerjaMysimkariProcessEvent;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\FetchKegiatanPoskoSentimentEvent;

class LapinharController extends Controller
{
    public function login(Request $request)
    {
		try {
			
			$cek = User::where('username', '=', $request->input('username'))->count();
			if($cek>0){
				$query = User::where('username', '=', $request->input('username'))->first();
				if(Hash::check($request->input('password'), $query->password)){
					$user = User::where('username', '=', $request->input('username'))->first();
					$data = [
						'status_code' => '200',
						'message' => 'Anda berhasil login',
                        'content' => $user
					];
					return response()->json($data);
				}else{
					$data =['status_code' => '401', 'message' => 'Password Anda Salah'];//Password Salah
					return response()->json($data);
				}
			}else{
				$data =['status_code' => '402', 'message' => 'Username Tidak Ditemukan'];//user tidak ditemukan
				return response()->json($data);
			}
			
		}
		catch (\Exception $ex) {
			$data = [
				'status_code'    => '500',
				'message'       => $ex->getMessage()
			];
			return response()->json($data);
		}
		
    }

    public function index(LapInHarDataTable $dataTable)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $kodeSatker = $satker->kode_satker;
        $data = LapInHar::when(!$user->hasRole(['superadmin', 'admin-kejagung']), function($q) use ($user, $satker, $kodeSatker) {
                            $q->where('lapinhar.kode_satker', 'like', "$kodeSatker%");
                        })->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nomor' => 'required',
            'tgl' => 'required',
            'info_yg_diperoleh' => 'required',
            'sumber_info' => 'required',
            'trend_perkembangan' => 'required',
            'pendapat_saran' => 'required',
        ]);

        if ($validator->fails()){
            $data = [
                'status_code' => '400',
                'message' => 'Periksa inputan anda'
            ];
            return response()->json($data);
        }

        $idUser = $request->id_user;
        $user = User::findOrFail($idUser);
        $satker = $user->satker;
        $kodeSatker = $satker->kode_satker;
        
        DB::beginTransaction();
        try {
            $satker = MasterSatker::where('master_satker.id_satker', $satker->id_satker)
                ->select([
                    'master_satker.id_satker', 'master_satker.kode_satker', 'master_satker.nama_satker',
                ])
                ->first();


            $LapInHar = LapInHar::create([
                'id_satker' => $satker?->id_satker,
                'kode_satker' => $satker?->kode_satker,
                'nama_satker' => $satker?->nama_satker,
                'nomor' => $request->nomor,
                'tgl' => $request->tgl,
                'info_yg_diperoleh' => $request->info_yg_diperoleh,
                'sumber_info' => $request->sumber_info,
                'trend_perkembangan' => $request->trend_perkembangan,
                'pendapat_saran' => $request->pendapat_saran,
                'created_by' => $idUser,
                'updated_by' => $idUser,
            ]);

            DB::commit();
            $lapinhar = LapInHar::when(!$user->hasRole(['superadmin', 'admin-kejagung']), function($q) use ($kodeSatker) {
                $q->where('lapinhar.kode_satker', 'like', "$kodeSatker%");
            })->get();

            $data = [
                'status_code' => '200',
                'message' => 'Data berhasil ditambah',
                'content' => $lapinhar
            ];
            return response()->json($data);
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            $data = [
				'status_code'    => '500',
				'message'       => $ex->getMessage()
			];
			return response()->json($data);
        }
    }

    public function show(Request $request)
    {
        $idUser = $request->id_user;
        $user = User::findOrFail($idUser);
        $satker = $user->satker;
        $kodeSatker = $satker->kode_satker;

        $id = $request->id;

        $satker = MasterSatker::where('master_satker.id_satker', $satker->id_satker)
            ->select([
                'master_satker.id_satker', 'master_satker.city', 'master_satker.nama_satker',
            ])
            ->first();

        $lapinhar = LapInHar::findOrFail($id);
        $path = 'https://inteliz.kejaksaan.go.id/storage/fileesign/'.$data->file_signed;
               
        if (!$data) {
            $data = [
                'status_code' => '400',
                'message' => 'Data tidak tersedia'
            ];
            return response()->json($data);
        }else{
            $data = [
                'status_code' => '200',
                'message' => 'Success',
                'content' => $lapinhar
            ];
            return response()->json($data);
        }
    
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nomor' => 'required',
            'tgl' => 'required',
            'info_yg_diperoleh' => 'required',
            'sumber_info' => 'required',
            'trend_perkembangan' => 'required',
            'pendapat_saran' => 'required',
        ]);

        if ($validator->fails()){
            $data = [
                'status_code' => '400',
                'message' => 'Periksa inputan anda'
            ];
            return response()->json($data);
        }

        $idUser = $request->id_user;
        $user = User::findOrFail($idUser);
        $satker = $user->satker;
        $kodeSatker = $satker->kode_satker;

        $id = $request->id;
        
        DB::beginTransaction();
        try {
            $satker = MasterSatker::where('master_satker.id_satker', $satker->id_satker)
                ->select([
                    'master_satker.id_satker', 'master_satker.kode_satker', 'master_satker.nama_satker',
                ])
                ->first();


            $LapInHar = LapInHar::where('id', $id)->update([
                'nomor' => $request->nomor,
                'tgl' => $request->tgl,
                'info_yg_diperoleh' => $request->info_yg_diperoleh,
                'sumber_info' => $request->sumber_info,
                'trend_perkembangan' => $request->trend_perkembangan,
                'pendapat_saran' => $request->pendapat_saran,
                'updated_by' => $idUser,
                'updated_by' => $idUser,
            ]);

            DB::commit();
            $lapinhar = LapInHar::when(!$user->hasRole(['superadmin', 'admin-kejagung']), function($q) use ($kodeSatker) {
                $q->where('lapinhar.kode_satker', 'like', "$kodeSatker%");
            })->get();

            $data = [
                'status_code' => '200',
                'message' => 'Data berhasil dirubah',
                'content' => $lapinhar
            ];
            return response()->json($data);
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            $data = [
				'status_code'    => '500',
				'message'       => $ex->getMessage()
			];
			return response()->json($data);
        }
    }

    public function destroy($id, Request $request)
    {
        $idUser = $request->id_user;
        $user = User::findOrFail($idUser);
        $satker = $user->satker;
        $kodeSatker = $satker->kode_satker;

        $id = $request->id;

        $lapinhar = LapInHar::find($id);
        if (!$lapinhar) {
            $data = [
                'status_code' => '400',
                'message' => 'Data tidak tersedia'
            ];
            return response()->json($data);
        }else{
            $lapinhar->delete();

            $lapinhar = LapInHar::when(!$user->hasRole(['superadmin', 'admin-kejagung']), function($q) use ($user, $satker, $kodeSatker) {
                $q->where('lapinhar.kode_satker', 'like', "$kodeSatker%");
            })->get();

            $data = [
                'status_code' => '200',
                'message' => 'Data berhasil dihapus',
                'content' => $lapinhar
            ];
            return response()->json($data);
        }

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    public function getSatker()
    {
        $role = strtolower(auth()->user()->roles->first()->name);

        $satker = MasterSatker::where('nama_satker', 'ILIKE', "%" . request()->term . "%")
            ->when($role, function ($query, $role) {
                if ($role == 'admin') {
                    return $query->where('id_satker', auth()->user()->id_satker);
                }
            })
            ->orderBy('id_satker', 'asc')
            ->get();

        $dataSatker = $satker->map(function ($item, $key) {
            $data['id'] = $item->id_satker;
            $data['text'] = $item->nama_satker;
            return $data;
        });

        return collect($dataSatker);
    }


}
