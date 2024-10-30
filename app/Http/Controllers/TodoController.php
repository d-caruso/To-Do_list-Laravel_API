<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    const DEFAULT_PAGINATION = 10;

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

    /**
     * Display a listing of the Todos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Validate the user_id query parameter
        $request->validate([
            // Ensure the user_id exists in the users table
            'user_id' => 'required|exists:users,id',
            // Optional status filter
            'status' => 'sometimes|in:pending,completed',
            // Optional sort by due date direction
            'sort_by_due_date' => 'sometimes|in:asc,desc',
        ]);

        // Get the user ID from the request
        $userId = $request->query('user_id');

        // Check if the authenticated user can access the specified user's todos
        if (request()->user()->id !== (int) $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Get the 'per_page' query parameter or set a default value
        $perPage = $request->query('per_page', TodoController::DEFAULT_PAGINATION);

        // Get the 'page_num' query parameter or default to 1
        $pageNum = $request->query('page_num', 1);

        // Get the optional 'status' filter
        $status = $request->query('status');

        // Get the optional 'sort_by_due_date' parameter for due_date
        $sort_by_due_date = $request->query('sort_by_due_date');


        // Build the query for todos
        $query = Todo::where('user_id', $userId);

        // Apply the status filter if it is provided
        if ($status) {
            $query->where('status', $status);
        }

        // Apply the due_date sorting if it is provided
        if ($sort_by_due_date) {
            $query->orderBy('due_date', $sort_by_due_date);
        }

        // Retrieve todos for the specified user, with pagination
        $todos = $query->paginate($perPage, ['*'], 'page', $pageNum);

        return response()->json($todos);
    }
}
