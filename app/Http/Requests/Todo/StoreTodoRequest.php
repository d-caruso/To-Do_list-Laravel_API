<?php

namespace App\Http\Requests\Todo;

class StoreTodoRequest extends AbstractTodoRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:50',
            'due_date' => 'sometimes|date_format:Y-m-d H:i:s',
        ];

        // Merge parent and additional rules
        return array_merge(parent::rules(), $rules);
    }
}
