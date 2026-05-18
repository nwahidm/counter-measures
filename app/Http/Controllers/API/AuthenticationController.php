<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $errors = [];
        $data = null;
        $message = "";
        $status = 200;

        $validator = Validator::make($request->all(),[
            'username' => 'required|string|max:128',
            'password' => 'required|string|max:255'
        ]);

        if ($validator->fails()){
            $status = 422;
            $errors = $validator->errors();
            $message = "Login Failed";

            return ResponseApiHelper::invalidate($message, $data, $errors);
        }

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        $token = $this->guard()->attempt($credentials);
        
        if (!$token){
            $status = 401;
            $message = "Invalid username or password";

            return ResponseApiHelper::unauthorize($message, $data, 408);
        }

        $user = $this->guard()->user()->toArray();
        if (!$user['is_active']) {
            $status = 401;
            $message = "User non aktif, silahkan hubungi admin";

            return ResponseApiHelper::unauthorize($message, $data);
        }

        return $this->respondWithToken($token, $user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh(), $this->guard()->user()->toArray());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        $ttl = $this->guard()->factory()->getTTL();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl, 
            'user' => $user
        ]);
    }

    private function guard()
    {
        return Auth::guard('api');
    }
}
