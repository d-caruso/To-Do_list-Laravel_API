<?php

namespace Tests\Unit\BusinessControllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for testing
        $this->user = User::factory()->create([
            'password' => Hash::make('old_password'), // Set an initial password
        ]);
    }

    public function test_can_update_password_successfully()
    {
        $data = [
            'current_password' => 'old_password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ];

        // Act as the user and send the update password request
        $response = $this
                        ->actingAs($this->user)
                        ->withHeaders(['Accept' => 'application/json'])
                        ->put('/api/' . env('API_CURRENT_VERSION') . '/user/password', $data);

        // Assert the response indicates success
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Password updated successfully.']);

        // Assert that the password was updated in the database
        $this->assertTrue(Hash::check('new_password', $this->user->fresh()->password));
    }

    public function test_cannot_update_password_with_incorrect_current_password()
    {
        $data = [
            'current_password' => 'wrong_password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ];

        // Act as the user and send the update password request
        $response = $this
                        ->actingAs($this->user)
                        ->withHeaders(['Accept' => 'application/json'])
                        ->put('/api/' . env('API_CURRENT_VERSION') . '/user/password', $data);

        // Assert the response indicates an error
        $response->assertStatus(403)
                 ->assertJson(['message' => 'Current password is incorrect.']);
    }

    public function test_unauthenticated_user_cannot_update_password()
    {
        $data = [
            'current_password' => 'old_password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ];

        // Send the update password request without authentication
        $response = $this
                        ->withHeaders(['Accept' => 'application/json'])
                        ->put('/api/' . env('API_CURRENT_VERSION') . '/user/password', $data);

        // Assert the response indicates the user is unauthorized
        $response->assertStatus(401);
    }
}