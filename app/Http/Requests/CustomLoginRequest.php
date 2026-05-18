<?php

namespace App\Http\Requests;

use App\Rules\RecaptchaRule;
use Laravel\Fortify\Fortify;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Http\Requests\LoginRequest;

class CustomLoginRequest extends LoginRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            Fortify::username() => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => ['required', new RecaptchaRule()]
        ];
    }
}
