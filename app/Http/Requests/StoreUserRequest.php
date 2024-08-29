<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|unique:users',
            'name' => 'required|unique:users',
            'profile_photo' => 'required|mimes:jpg,jpeg,png|max:2048',
            'certificate' => 'required|mimes:pdf|max:2048',
            'password' => 'required|min:8|confirmed',
        ];
    }
}
