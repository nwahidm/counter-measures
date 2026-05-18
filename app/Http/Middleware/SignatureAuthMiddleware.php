<?php

namespace App\Http\Middleware;

use Closure;
use ResponseApi;
use Illuminate\Http\Request;

class SignatureAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $clientId = $request->header('X-Api-Client-Id');
        $timestamp = $request->header('X-Api-Timestamp');
        $signature = $request->header('X-Api-Signature');

        $method = $request->method();
        $path = $request->getRequestUri();
        $ip = $request->ip();
        $requestBody = $request->all();

        if (!$clientId)
            return ResponseApi::unauthorize(ResponseApi::responseStatus('402'), null, 402);

        if (!$timestamp)
            return ResponseApi::unauthorize(ResponseApi::responseStatus('406'), null, 406);

        if (isISO8601($timestamp)) 
            return ResponseApi::unauthorize(ResponseApi::responseStatus('406'), null, 406);

        if (!$signature)
            return ResponseApi::unauthorize(ResponseApi::responseStatus('403'), null, 403);  

        $client = fetchClient($clientId);

        if (!$client)
            return ResponseApi::unauthorize(ResponseApi::responseStatus('402'), null, 402);

        if ($client->whitelist_ip != null && !in_array($ip, $client->whitelist_ip))
            return ResponseApi::unauthorize(ResponseApi::responseStatus('402'), null, 402);

        if (strtoupper($method) === 'GET') {
            $messagePayload = $method . $path . $timestamp;
        } else {
            $messagePayload = $method . md5(json_encode($requestBody)) . $path . $timestamp;
        }

        $md5SecretKey = md5($client->client_key);

        $signatureServer = base64_encode(hash_hmac('sha512', $messagePayload, $md5SecretKey, true));

        if ($signature != $signatureServer)
            return ResponseApi::unauthorize(ResponseApi::responseStatus('403'), null, 403);

        return $next($request);
    }
}
