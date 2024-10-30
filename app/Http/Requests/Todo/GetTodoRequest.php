<?php

namespace App\Http\Requests\Todo;

class GetTodoRequest extends AbstractTodoRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Define additional or overriding rules
        $rules = [
            // Optional status filter
            'status' => 'sometimes|in:pending,completed',
            // Optional sort by due date direction
            'sort_by_due_date' => 'sometimes|in:asc,desc',
        ];

        // Merge parent and additional rules
        return array_merge(parent::rules(), $rules);
    }
}
