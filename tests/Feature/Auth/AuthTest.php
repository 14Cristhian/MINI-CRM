<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ── Registration ─────────────────────────────────────────────────────────

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_register_requires_all_fields(): void
    {
        $this->post('/register', [])->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->post('/register', [
            'name'                  => 'Another User',
            'email'                 => 'taken@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    // ── Login ─────────────────────────────────────────────────────────────────

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'secret'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_requires_email_and_password(): void
    {
        $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    // ── Protected routes ─────────────────────────────────────────────────────

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/clients')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertOk();
    }
}
