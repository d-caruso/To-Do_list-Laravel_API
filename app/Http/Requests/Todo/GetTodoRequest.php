<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;

class GetTodoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Optional status filter
            'status' => 'sometimes|in:pending,completed',
            // Optional sort by due date direction
            'sort_by_due_date' => 'sometimes|in:asc,desc',
        ];
    }
}
