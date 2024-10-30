<?php

namespace App\Http\Requests\User;

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
}