<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ];
    }

    public function authorize()
    {
        return true; // add authorization logic if needed
    }
}