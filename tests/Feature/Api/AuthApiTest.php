<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use App\Notifications\UserResetPasswordNotification;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_email_and_password(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'User Demo',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'user@example.com')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token', 'token_type', 'user'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
            'is_admin' => false,
            'is_frozen' => false,
        ]);
    }

    public function test_register_rejects_existing_email_case_insensitive(): void
    {
        User::query()->create([
            'name' => 'Google User',
            'email' => 'Google.User@Example.com',
            'password' => Hash::make('password123'),
            'google_id' => 'google-sub-123',
            'is_admin' => false,
            'is_frozen' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'User Demo',
            'email' => 'google.user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.email.0', 'Email sudah terdaftar. Silakan gunakan email lain atau login.');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_can_login_with_email_and_password(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'is_frozen' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token', 'token_type', 'user'],
            ]);
    }

    public function test_user_can_login_with_google_id_token(): void
    {
        config()->set('services.google.client_id', 'google-client-id-demo');

        Http::fake([
            'https://oauth2.googleapis.com/tokeninfo*' => Http::response([
                'aud' => 'google-client-id-demo',
                'sub' => 'google-sub-123',
                'email' => 'google.user@example.com',
                'email_verified' => 'true',
                'name' => 'Google User',
                'picture' => 'https://example.com/avatar.jpg',
            ], 200),
        ]);

        $response = $this->postJson('/api/v1/auth/google', [
            'id_token' => 'valid-id-token',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'google.user@example.com')
            ->assertJsonPath('data.user.avatar', 'https://example.com/avatar.jpg')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token', 'token_type', 'user'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'google.user@example.com',
            'google_id' => 'google-sub-123',
            'is_admin' => false,
            'is_frozen' => false,
        ]);
    }

    public function test_user_can_request_forgot_password_link_from_api(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'is_admin' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true);

        Notification::assertSentTo($user, UserResetPasswordNotification::class);
    }

    public function test_user_can_reset_password_from_api_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true);

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', (string) $user->password));
    }
}
