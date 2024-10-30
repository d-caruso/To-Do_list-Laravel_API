<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTodoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string|filled|max:50',
            'description' => 'nullable|string|max:255',
            'due_date' => 'sometimes|date_format:Y-m-d H:i:s',
            'status' => 'nullable|string|max:255',
        ];
    }
}
