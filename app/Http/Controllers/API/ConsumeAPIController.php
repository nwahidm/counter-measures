<?php

namespace App\Http\Controllers\API;

use ResponseApi;
use App\Models\Dct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Events\FetchKegiatanPoskoSentimentEvent;

class ConsumeAPIController extends Controller
{
    public function getTokenInteliz()
    {
        try {
            $url = 'https://inteliz.kejaksaan.go.id/api/v1/authentication/create-token';

            $payload = [
                'username' => 'INTEL_USER',
                'password' => 'Inte7P@sSw0rd'
            ];

            $response = Http::withHeaders(accessTokenInteliz($payload))
                            ->post($url, $payload);
            $body = $response->object();

            if ($response->successful()) {
                return $body;
            }

            return response()->json(['status' => '400', 'message' => $body?->responseMessage ?? 'Internal Server Error']);
        }

        catch (\Exception $ex) {
            return response()->json(['status' => '500', 'message' => 'Internal Server Error']);
        }
    }

    public function getToken()
    {
        $retrieveToken = $this->getTokenInteliz();
        if (!$retrieveToken->status == '200') {
            throw new Exception($retrieveToken['message']);
        }

        return $retrieveToken->getData()->access_token;
    }


    public function getListNPHD(Request $request)
    {
        try {
            $url = 'https://inteliz.kejaksaan.go.id';
            $path = '/api/v1/nphd/list';

            $response = Http::withHeaders(serviceSignatureInteliz("GET", $path, [], $this->getToken()))
                            ->get($url.$path);
            $body = $response->object();

            return $body;
        }
        catch (ConnectionException $ex) {
            return response()->json(['status' => '405', 'message' => 'Timeout, please check status later', 'is_reversal' => false]);
        }
        catch (Exception $ex) {
            return response()->json(['status' => '500', 'message' => "Internal Server Error : {$ex->getMessage()}", 'is_reversal' => true]);
        }
    }
}
