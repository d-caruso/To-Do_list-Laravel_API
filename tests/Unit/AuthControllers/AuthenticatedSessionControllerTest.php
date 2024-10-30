<?php

namespace Tests\Unit\AuthControllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for testing
        $this->user = User::factory()->create([
            'password' => bcrypt('password123'), // Set a known password
        ]);
    }

    public function test_can_login_successfully()
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'password123',
        ];

        Log::info('Current session:', session()->all());
        // Send login request to the "auth" endpoint
        $response = $this
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post('/api/' . env('API_CURRENT_VERSION') . '/auth', $data);

        // Assert response is successful
        $response->assertStatus(204); // No content for successful login

        // Check that the user is authenticated
        $this->assertTrue(Auth::check());
    }

    public function test_cannot_login_with_incorrect_password()
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
        ];

        // Send login request to the "auth" endpoint
        $response = $this
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post('/api/' . env('API_CURRENT_VERSION') . '/auth', $data);

        // Assert response indicates Unprocessable Entity for wrong validation
        $response->assertStatus(422);
    }

    public function test_cannot_login_with_nonexistent_user()
    {
        $data = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ];

        // Send login request to the "auth" endpoint
        $response = $this
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post('/api/' . env('API_CURRENT_VERSION') . '/auth', $data);

        // Assert response indicates Unprocessable Entity for wrong validation
        $response->assertStatus(422);
    }

    public function test_can_logout_successfully()
    {
        // Send logout request to the "auth" endpoint
        $response = $this
                        ->actingAs($this->user)
                        ->withHeaders(['Accept' => 'application/json'])
                        ->delete('/api/' . env('API_CURRENT_VERSION') . '/auth');

        // Assert response is successful
        $response->assertStatus(204); // No content for successful logout

        // Check that the user is logged out
        $this->assertFalse(Auth::check());
    }
}