<?php

namespace App\Helpers;

class ResponseApiHelper{

    private static function responseDefault($message, $data, $errors = [], $status = 200, $httpStatus = 200)
    {
        $result = [
            "status" => (string) $status,
            "message" => $message,            
            "data" => $data,
            "errors" => $errors,
            'timestamp' => floor(microtime(true) * 1000)
        ];
        return response()->json($result, $httpStatus);
    }

    public static function success($message, $data)
    {
        return self::responseDefault($message, $data);
    }

    public static function invalidate($message, $data, $errors)
    {
        return self::responseDefault($message, $data, $errors, 422, 422);
    }

    public static function unauthorize($message, $data, $status = 401)
    {
        return self::responseDefault($message, $data, [], $status, 401);
    }

    public static function notfound($message, $data, $status = 988)
    {
        return self::responseDefault($message, $data, [], $status, 404);
    }

    public static function servererror($message, $data)
    {
        return self::responseDefault($message, $data, [], 500, 500);
    }

    public static function responseStatus($status = null) {
        $data =  [
            '200' => 'SUCCESS',
            '400' => 'Invalid Parameter Request',
            '401' => 'Unauthorized Request',
            '402' => 'Invalid Client ID [X-Api-Client-Id]',
            '403' => 'Invalid Signature [X-Api-Signature]',
            '405' => 'Invalid Token [authorization]',
            '406' => 'Invalid Timestamp [X-Api-Timestamp], please use ISO 8601 Format (UTC)',
            '407' => 'Invalid Whitelist IP Client, please ensure the IP',
            '408' => 'Invalid Username or Password',
            '409' => 'Token Expired [authorization]',
            '500' => 'Internal server error, please try again later',
            '988' => 'Route / Data not found'
        ];

        if ($status != null) {
            return $data[$status] ?? null;
        }

        return $data;
    }
}

?>