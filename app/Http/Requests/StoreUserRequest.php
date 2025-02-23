<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|digits:10|unique:users,phone_number|regex:/^([0-9\s\-\+\(\)]*)$/',
            'password' => 'required|string|confirmed|min:6',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
