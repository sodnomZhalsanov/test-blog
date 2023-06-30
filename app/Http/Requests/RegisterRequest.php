<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'firstname' => 'required|string|min:3',
            'lastname' => 'required|string|min:3',
            'login' => 'required|string|email|min:6|unique:users',
            'password' => 'required|string|min:8',
        ];
    }
}
