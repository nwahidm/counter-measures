<?php

namespace App\Actions\Fortify;

use App\Helpers\FileHelper;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        $validator = Validator::make($input,[
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id
        ]);

        if ($validator->fails()){
            return redirect()->back()->with(["error" => "Gagal update profile, silahkan cek ulang pada inputan dan uploadan"]);
        }

        $filename = '';
        if (isset($input['profile_photo_path'])) {
            $fileData = $input['profile_photo_path'];
            $prefixname = "avatar";
            $filename = FileHelper::uploadFile($fileData, '/user/', $prefixname);
        }

        $user->update([
            'name' => $input['name'],
            'email' => $input['email'],
            'profile_photo_path' => $filename == '' ? $user->profile_photo_path : $filename,
        ]);

        return redirect()->back()->with(["success" => "Sukses update profile"]);
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
