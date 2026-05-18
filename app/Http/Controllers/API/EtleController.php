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

class EtleController extends Controller
{
    public function checkPlat(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'plat' => 'required'
        ]);

        if ($validator->fails()){
            return [
                'status' => 422,
                'Ket' => 'Plat harus diisi.'
            ];
        }
        $plat = $request->plat;
        $response = Http::asForm()->post('https://api.otisrawned-etle.info/auth/realms/etle/protocol/openid-connect/token', [
            'grant_type' => 'password',
            'username' => 'jamintel',
            'password' => 'jamintel@2024.etle',
            'client_id' => 'jamintel',
            'client_secret' => '01b94138-daef-40a0-8051-d2ca96440ded'
        ]);
        
        // Check if request was successful
        if ($response->successful()) {
            // Handle successful response
            $responseData = json_decode($response->body(), true); // Ubah JSON menjadi array asosiatif
            $token = $responseData['access_token'];

            // URL target
            $url = 'https://belik.etle-korlantas.info/rse/eris/'.$plat;

            // Header request
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ];
            // Lakukan request dengan method GET
            $response = Http::withHeaders($headers)->get($url);
            $data = json_decode($response->body(), true); // Mengambil respons sebagai JSON

            if(isset($data['status'])){
                if($data['status'] == "0021"){
                    return ResponseApi::notfound(ResponseApi::responseStatus('988'), null, 988);
                }
            }
        } else {
            // Handle failed request
            $data = $response->json();
            // Do something with $errorResponse
        }
        // Tampilkan output
        return ResponseApi::success('Get data Successfully', $data);
        
    }
}
