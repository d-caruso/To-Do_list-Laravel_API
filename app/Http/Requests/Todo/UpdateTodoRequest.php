<?php

namespace App\Http\Requests\Todo;

class UpdateTodoRequest extends AbstractTodoRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'string|filled|max:50',
            'description' => 'nullable|string|max:255',
            'due_date' => 'sometimes|date_format:Y-m-d H:i:s',
            'status' => 'nullable|string|max:255',
        ];

        // Merge parent and additional rules
        return array_merge(parent::rules(), $rules);
    }
}
