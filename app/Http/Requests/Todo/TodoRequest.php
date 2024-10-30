<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TodoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:50',
        ];
    }

    public function authorize()
    {
        $todo = $this->route('todo'); // Assuming you're using route model binding

        // Check if the user is authenticated //and is the owner of the post
        return request()->user() !== null;
    }
}
