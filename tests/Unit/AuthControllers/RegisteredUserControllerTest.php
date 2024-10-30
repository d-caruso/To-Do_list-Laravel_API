<?php

namespace Tests\Unit\AuthControllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_user_successfully()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Send registration request
        $response = $this
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post('/api/' . env('API_CURRENT_VERSION') . '/users', $data);

        // Assert response is successful
        $response->assertStatus(204); // No content for successful registration

        // Assert that the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_cannot_register_with_existing_email()
    {
        // Create a user first
        User::factory()->create(['email' => 'existing@example.com']);

        $data = [
            'name' => 'Jane Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Send registration request
        $response = $this
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post('/api/' . env('API_CURRENT_VERSION') . '/users', $data);

        // Assert response indicates validation error for unique email
        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_register_with_missing_fields()
    {
        $data = [
            'email' => 'john@example.com',
            'password' => 'password123',
            // 'name' is missing
            'password_confirmation' => 'password123',
        ];

        // Send registration request
        $response = $this
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post('/api/' . env('API_CURRENT_VERSION') . '/users', $data);

        // Assert response indicates validation error for missing name
        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_cannot_register_with_password_mismatch()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ];

        // Send registration request
        $response = $this
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post('/api/' . env('API_CURRENT_VERSION') . '/users', $data);

        // Assert response indicates validation error for password confirmation
        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_cannot_register_with_invalid_email()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Send registration request
        $response = $this
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post('/api/' . env('API_CURRENT_VERSION') . '/users', $data);

        // Assert response indicates validation error for invalid email format
        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors(['email']);
    }
}
