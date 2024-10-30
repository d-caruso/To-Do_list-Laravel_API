<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractTodoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Ensure the user_id exists in the users table
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function authorize()
    {
        // Get the user ID from the request
        $userId = $this->user_id;

        // Check if the authenticated user can access the specified user's todos
        return (request()->user()->id === (int) $userId);
    }
}
