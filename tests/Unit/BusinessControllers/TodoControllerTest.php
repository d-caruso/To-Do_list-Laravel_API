<?php

namespace Tests\Unit\BusinessControllers;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for testing
        $this->user = User::factory()->create();
    }

    public function test_can_list_todos()
    {
        // Create some todos for the user
        Todo::factory()->count(5)->create(['user_id' => $this->user->id]);

        // Act as the user and get the todos
        $response = $this
                        ->actingAs($this->user)
                        ->get('/api/' . env('API_CURRENT_VERSION') . '/todos');

        // Assert response is successful and returns the correct structure
        $response
                ->assertStatus(200)
                ->assertJsonStructure([
                     'current_page',
                     'data' => [
                         '*' => ['id', 'user_id', 'title', 'description', 'status', 'due_date', 'created_at', 'updated_at'],
                     ],
                     'last_page',
                     'per_page',
                     'total',
                 ]);
    }

    public function test_can_create_todo()
    {
        $todoData = [
            'title' => 'Test Todo',
            'description' => 'Test description',
            'status' => 'pending',
        ];

        // Act as the user and create a new todo
        $response = $this
                        ->actingAs($this->user)
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post('/api/' . env('API_CURRENT_VERSION') . '/todos', $todoData);

        // Assert the todo is created successfully
        $response->assertStatus(201)
                 ->assertJsonFragment($todoData);
        
        $this->assertDatabaseHas('todos', $todoData + ['user_id' => $this->user->id]);
    }

    public function test_can_update_todo()
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);
        $updateData = [
            'title' => 'Updated Title',
            'status' => 'completed',
        ];

        // Act as the user and update the todo
        $response = $this
                        ->actingAs($this->user)
                        ->withHeaders(['Accept' => 'application/json'])
                        ->put('/api/' . env('API_CURRENT_VERSION') . '/todos/'.$todo->id, $updateData);

        // Assert the todo is updated successfully
        $response
                ->assertStatus(200)
                ->assertJsonFragment($updateData);
        
        $this->assertDatabaseHas('todos', $updateData + ['id' => $todo->id]);
    }

    public function test_cannot_update_other_users_todo()
    {
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);
        $updateData = ['title' => 'Unauthorized Update'];

        // Act as the original user and attempt to update
        $response = $this
                        ->actingAs($this->user)
                        ->withHeaders(['Accept' => 'application/json'])
                        ->put('/api/' . env('API_CURRENT_VERSION') . '/todos/'.$todo->id, $updateData);

        // Assert response is unauthorized
        $response
                ->assertStatus(403)
                ->assertJson(['message' => 'Unauthorized']);
    }

    public function test_can_delete_todo()
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        // Act as the user and delete the todo
        $response = $this
                        ->actingAs($this->user)
                        ->delete('/api/' . env('API_CURRENT_VERSION') . '/todos/'.$todo->id);

        // Assert the todo is deleted successfully
        $response
                ->assertStatus(200)
                ->assertJson(['message' => 'Todo deleted successfully']);
        
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_cannot_delete_other_users_todo()
    {
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);

        // Act as the original user and attempt to delete
        $response = $this
                        ->actingAs($this->user)
                        ->delete('/api/' . env('API_CURRENT_VERSION') . '/todos/'.$todo->id);

        // Assert response is unauthorized
        $response
                ->assertStatus(403)
                ->assertJson(['message' => 'Unauthorized']);
    }
}