<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\BodyCamDevice\BodyCamDevice;
use phpseclib3\Crypt\RSA;

class BodycamDeviceDataHelper
{

    public static function getBodycamDevicebyUser()
    {
        auth()->user() ? $userRole = auth()->user()->user_roles : $userRole = Auth::guard('api')->user()->user_roles;

        if ($userRole == 'superadmin') {
            $case = BodyCamDevice::all();
        } else {
            $case = BodyCamDevice::where('device_used_for', auth()->user()->satker->kode_satker)
                ->get();
        }

        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->device_name), 128);

            return $data;
        });

        return collect($data);
    }

    public static function loginDss()
    {
        $host = env('DSS_HOST'); 
        $username = env('DSS_USERNAME'); 
        $password = env('DSS_PASSWORD'); 

        $response = Http::withOptions([
            'verify' => false,  // Disable SSL verification
        ])->post("$host/brms/api/v1.0/accounts/authorize", [
            'userName' => $username,
            'clientType' => 'WINPC_V2',  
            'ipAddress' => '', 
        ]);
        // return $response['realm'];
        if ($response->status() == 401) {
            $realm = $response['realm'];
            $randomKey = $response['randomKey'];
            $publicKey = $response['publickey'];

            $signature = self::calculateSignature($username, $password, $realm, $randomKey);

            $secondResponse = Http::withOptions([
                'verify' => false,  // Disable SSL verification
            ])->post("$host/brms/api/v1.0/accounts/authorize", [
                // 'mac' => '30:9c:23:79:40:08',  
                'signature' => $signature,
                'userName' => $username,
                'randomKey' => $randomKey,
                'publicKey' => $publicKey,
                'encryptType' => 'MD5', 
                'clientType' => 'WINPC_V2',
                'ipAddress' => '',  
                'userType' => '0',  
            ]);

            $token = $secondResponse['token'];
            return $token;
        } else {
            return $response->status();
        }
    }

    private static function calculateSignature($username, $password, $realm, $randomKey)
    {
        $temp1 = md5($password);
        $temp2 = md5($username . $temp1);
        $temp3 = md5($temp2);

        $temp4 = md5($username . ":" . $realm . ":" . $temp3);
        $signature = md5($temp4 . ":" . $randomKey);

        return $signature;
    }

    public static function getStreamToken($dahuaId = '')
    {
        $host = env('DSS_HOST'); 
        $token = self::loginDss();  

        if(!$dahuaId){
            return 'id is null';
        }

        $data = [
            "clientType" => "WINPC_V2",
            "clientMac" => "30:9c:23:79:40:08",
            "clientPushId" => "",
            "project" => "PSDK",
            "method" => "MTS.Video.StartVideo",
            "data" => [
                "streamType" => "1",
                "optional" => "/brms/api/v1.0/MTS/Video/StartVideo",
                "trackId" => "",
                "extend" => "",
                "channelId" => $dahuaId . "$1$0$0",
                "keyCode" => "",
                "planId" => "",
                "dataType" => "2",
                "enableRtsps" => "0",
                "enableMulticast" => "0"
            ]
        ];

        $response = Http::withHeaders([
            'X-Subject-Token' => $token,  // Add the token in the header
        ])->withOptions([
            'verify' => false,  
        ])->post($host . '/brms/api/v1.0/MTS/Video/StartVideo', $data);

        if ($response->successful()) {
            $responseData = $response->json();

            return $responseData['data']['token'];

        } else {
            // Handle errors
            return [
                'error' => 'Failed to fetch data',
                'status' => $response->status(),
            ];
        }
    }



    public static function getBodycamDatabyId($bodycam_id)
    {

    
        $bodycam_id = request()->query('bodycam_id') ?? $bodycam_id;

        if (!$bodycam_id) {
            return [];
        } else {


            $bodycam_data = BodyCamDevice::where('id', $bodycam_id)->first();
            $bodycam_data->token = self::getStreamToken($bodycam_data->device_dahua_id);
             
            return collect($bodycam_data);
        }
    }

}
