<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\GetTodoRequest;
use App\Http\Requests\Todo\StoreTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    const DEFAULT_PAGINATION = 10;

    /**
     * Create a Todo.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTodoRequest $request)
    {
        // Create a new Todo using validated data from the request
        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => now(),
            'status' => $request->status,
            'user_id' => $request->user_id,
        ]);

        return response()->json($todo);
    }

    /**
     * Update the specified Todo.
     *
     * @param  Request  $request
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        $filteredData = $request->except(['id', 'user_id', 'created_at', 'updated_at']);
        // Update the Todo with the validated data
        $todo->update($filteredData);

        return response()->json($todo);
    }

    /**
     * Display a listing of the Todos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetTodoRequest $request)
    {
        // Get the user ID from the request
        $userId = $request->query('user_id');

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
