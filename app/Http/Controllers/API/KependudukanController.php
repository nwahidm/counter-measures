<?php

namespace App\Http\Controllers\API;

use ResponseApi;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Events\FetchKegiatanPoskoSentimentEvent;
use Image;

class KependudukanController extends Controller
{
    public function CallNik(Request $request)
    {
        $nik = $request->nik;
        $cek = Penduduk::where('NIK', $nik)->count();

        if($cek > 0){
            $data = Penduduk::where('NIK', $nik)->first();
            // $data = [
            //     'status'    => '200',
            //     'ket'       => $penduduk
            // ];
            return ResponseApi::success('Get data Successfully', $data);
        }

        $response = Http::post('http://192.168.12.5:801/api/dukcapil/origin/nik', 
                                    [
                                        'NIK' => $nik
                                    ]
                                );

        if ($response->successful()) {
            $result = json_decode($response->body());
            if (is_object($result)) {
                $data = collect();
                foreach ($result as $key => $value) {
                    $data[$key] = $value;
                }
                $data->prepend('200', 'status');
                $data = $data['content'];
                // CreateToPendudukHelper($data);

                //Insert To Table Penduduk
                foreach($data as $value){
                    $insert=Penduduk::firstOrCreate(['NIK' =>  $value->NIK],[
                        'NO_KK' => $value->NO_KK,
                        'NAMA_LGKP' => $value->NAMA_LGKP,
                        'TMPT_LHR' => $value->TMPT_LHR,
                        'TGL_LHR' => $value->TGL_LHR,
                        'PROP_NAME' => $value->PROP_NAME,
                        'KAB_NAME' => $value->KAB_NAME,
                        'KEC_NAME' => $value->KEC_NAME,
                        'KEL_NAME' => $value->KEL_NAME,
                        'NO_RT' => $value->NO_RT,
                        'NO_RW' => $value->NO_RW,
                        'ALAMAT' => $value->ALAMAT,
                        'NIK_IBU' => $value->NIK_IBU,
                        'NAMA_LGKP_IBU' => $value->NAMA_LGKP_IBU,
                        'AGAMA' => $value->AGAMA,
                        'STATUS_KAWIN' => $value->STATUS_KAWIN,
                        'JENIS_PKRJN' => $value->JENIS_PKRJN,
                        'JENIS_KLMIN' => $value->JENIS_KLMIN,
                        'PDDK_AKH' => $value->PDDK_AKH,
                        'STAT_HBKEL' => $value->STAT_HBKEL,
                        'NO_PROP' => $value->NO_PROP,
                        'NO_KAB' => $value->NO_KAB,
                        'NO_KEC' => $value->NO_KEC,
                        'NO_KEL' => $value->NO_KEL,
                        'NIK' => $value->NIK,
                        'FOTO' => $value->FOTO,
                    ]);
                }
            }
            else {
                $data = [
                    'status'    => '404',
                    'ket'       => $result
                ];
            }
        }
        else {
            $data = [
                'status'    => (string) $response->status(),
                'ket'       => 'Unsuccessfull Request'
            ];
        }

        return ResponseApi::success('Get data Successfully', $data);
    }

    public function CallFr(Request $request)
    {
        $image = $request->image;
        $prefixname = '';
        if ($image) {
            $fileData = base64_decode($image);
            $prefixname = 'CallFrFotoByMobile.jpg';
            $path = public_path().'/storage/filefotoktp/'.$prefixname;
            file_put_contents(public_path().'/storage/filefotoktp/'.$prefixname, $fileData);
        }
        $response = Http::attach('image', file_get_contents($path), $prefixname)->post('http://192.168.12.5:801/api/dukcapil/recognition', $request->all());

        if ($response->successful()) {
            $result = json_decode($response->body());
            if (is_object($result)) {
                $data = collect();
                foreach ($result as $key => $value) {
                    $data[$key] = $value;
                }
                $data->prepend('200', 'status');
                $result = json_decode($data['response'], true);
                $data = $result['face']['FACE_T5'];
            }
            else {
                $data = [
                    'status'    => '404',
                    'ket'       => $result
                ];
            }
        }
        else {
            $data = [
                'status'    => (string) $response->status(),
                'ket'       => 'Unsuccessfull Request'
            ];
        }

        return ResponseApi::success('Get data Successfully', $data);
    }

    public function checkByNoPaspor(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nopaspor' => 'required'
        ]);

        if ($validator->fails()){
            return [
                'status' => 422,
                'Ket' => 'No Paspor Harus Diisi'
            ];
        }

        $client = fetchClientByUsername($_SERVER['PHP_AUTH_USER']);
        $nopaspor = $request->nopaspor;
        $response = Http::withBasicAuth($client->client_username, $client->client_password)->post('http://45.251.75.184/api/imigrasi/get-perlintasan-by-paspor', 
                                    [
                                        'nopaspor' => $nopaspor
                                    ]
                                );

        if ($response->successful()) {
            $result = json_decode($response->body());
            if (is_object($result)) {
                $data = collect();
                foreach ($result as $key => $value) {
                    $data[$key] = $value;
                }
                $data->prepend('200', 'status');
            }
            else {
                $data = [
                    'status'    => '404',
                    'ket'       => $result
                ];
            }
        }
        else {
            $data = [
                'status'    => (string) $response->status(),
                'ket'       => 'Unsuccessfull Request'
            ];
        }

        return response()->json($data);

    }

    public function checkByNamaPaspor(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'tanggal_lahir' => 'required'
        ]);

        if ($validator->fails()){
            return [
                'status' => 422,
                'Ket' => ' Nama dan Tanggal Lahir Harus Diisi (Format Date yyymmdd)'
            ];
        }

        $nama = $request->nama;
        $tanggal_lahir = $request->tanggal_lahir;
        $response = Http::withBasicAuth('admin', 'kTp5ervice!!24')->post('http://45.251.75.184/api/imigrasi/get-perlintasan-by-nama-tgl', 
                                    [
                                        'nama' => $nama, 'tanggal_lahir' => $tanggal_lahir
                                    ]
                                );

        if ($response->successful()) {
            $result = json_decode($response->body());
            if (is_object($result)) {
                $data = collect();
                foreach ($result as $key => $value) {
                    $data[$key] = $value;
                }
                $data->prepend('200', 'status');
            }
            else {
                $data = [
                    'status'    => '404',
                    'ket'       => $result
                ];
            }
        }
        else {
            $data = [
                'status'    => (string) $response->status(),
                'ket'       => 'Unsuccessfull Request'
            ];
        }
        return response()->json($data);
    }
}
