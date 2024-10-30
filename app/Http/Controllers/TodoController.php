<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\TodoRequest;
use App\Models\Todo;

class TodoController extends Controller
{
    public function store(TodoRequest $request)
    {
        $user = $request->user();
        
        // Create a new Todo using validated data from the request
        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => now(),
            'status' => $request->status,
            'user_id' => $request->user()->id, // Set the user_id to the authenticated user's ID
        ]);

        return response()->json(['message' => 'Todo updated successfully.']);
    }
}
