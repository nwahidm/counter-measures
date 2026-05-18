<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        $validator = Validator::make($input,[
            'current_password'  => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        if ($validator->fails()){
            return redirect()->back()->with(["error" => "Password dan konfirmasi password harus sama, silahkan cek ulang."]);
        }

        if (!Hash::check($input['current_password'], $user->password)) {
            return redirect()->back()->with(["error" => "Password lama tidak sesuai"]);
        }

        $user->update([
            'password' => bcrypt($input['password'])
        ]);

        return redirect()->back()->with(["success" => "Sukses update password"]);
    }
}
