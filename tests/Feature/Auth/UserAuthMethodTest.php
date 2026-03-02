<?php

namespace Tests\Feature\Auth;

use App\Models\LoginDeviceChallenge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserDeviceVerificationNotification;
use Tests\TestCase;

class UserAuthMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_rejects_existing_email_from_google_account(): void
    {
        User::query()->create([
            'name' => 'Google User',
            'email' => 'Google.User@Example.com',
            'password' => Hash::make('secret12345'),
            'google_id' => 'google-sub-001',
            'is_admin' => false,
            'is_frozen' => false,
        ]);

        $response = $this->from('/daftar')->post('/daftar', [
            'name' => 'New User',
            'email' => 'google.user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertRedirect('/daftar')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_login_email_password_is_case_insensitive(): void
    {
        User::query()->create([
            'name' => 'Password User',
            'email' => 'Case.User@Example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'is_admin' => false,
            'is_frozen' => false,
        ]);

        $response = $this->post('/masuk', [
            'email' => 'case.user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('user.panel', absolute: false));
        $this->assertAuthenticated();
    }

    public function test_verified_user_login_from_new_device_requires_device_verification(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'is_frozen' => false,
        ]);

        $this->withHeader('User-Agent', 'Browser-A/1.0')->post('/masuk', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('user.panel', absolute: false));

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();

        $response = $this->withHeader('User-Agent', 'Browser-B/1.0')->post('/masuk', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('user.login'));
        $this->assertGuest();
        Notification::assertSentTo($user, UserDeviceVerificationNotification::class);
        $this->assertDatabaseCount('trusted_devices', 1);
        $this->assertDatabaseCount('login_device_challenges', 1);
    }

    public function test_user_can_complete_device_verification_from_email_link(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'is_frozen' => false,
        ]);

        $this->withHeader('User-Agent', 'Browser-A/1.0')->post('/masuk', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('user.panel', absolute: false));

        $this->post('/logout');
        $this->assertGuest();

        $this->withHeader('User-Agent', 'Browser-B/1.0')->post('/masuk', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('user.login'));

        $challenge = LoginDeviceChallenge::query()->where('user_id', $user->id)->latest('id')->firstOrFail();

        $this->withHeader('User-Agent', 'Browser-B/1.0')
            ->get('/verifikasi-device/'.$challenge->token)
            ->assertRedirect(route('user.panel', absolute: false));

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('trusted_devices', 2);

        $challenge->refresh();
        $this->assertNotNull($challenge->used_at);
    }
}
