<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResetPasswordApiTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    public function test_a_user_can_reset_their_password(): void
    {
        $user = User::factory()->create();

        $token = app('auth.password.broker')->createToken($user);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email
        ]);

        $this->post(route('auth.reset-password.store'), [
            'email' => $user->email,
            'password' => $password = fake()->password(8),
            'password_confirmation' => $password,
            'token' => $token
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $this->getJson('/api/user')
            ->assertStatus(200);
    }

    /** @test */
    public function email_field_is_required(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $this->postJson(route('auth.reset-password.store'), [
                'password' => $password = fake()->password,
                'password_confirmation' => $password,
                'token' => $token
            ])
            ->assertJsonValidationErrors([
                'email' => 'The email field is required.'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function email_field_is_valid(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $this->postJson(route('auth.reset-password.store'), [
                'email' => 'WRONG',
                'password' => $password = fake()->password,
                'password_confirmation' => $password,
                'token' => $token
            ])
            ->assertJsonValidationErrors([
                'email' => 'The email field must be a valid email address.'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function email_field_exists(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $this->postJson(route('auth.reset-password.store'), [
            'email' => 'john.doe@gmail.com',
            'password' => $password = fake()->password,
            'password_confirmation' => $password,
            'token' => $token
        ])
            ->assertJsonValidationErrors([
                'email' => 'The selected email is invalid.'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function token_is_required(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('auth.reset-password.store'), [
            'email' => $user->email,
            'password' => $password = fake()->password,
            'password_confirmation' => $password,
        ])
            ->assertJsonValidationErrors([
                'token' => 'The token field is required.'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function password_is_required(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $this->postJson(route('auth.reset-password.store'), [
            'email' => $user->email,
            'password_confirmation' => fake()->password,
            'token' => $token
        ])
            ->assertJsonValidationErrors([
                'password' => 'The password field is required.'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function password_confirmation_is_required(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $this->postJson(route('auth.reset-password.store'), [
            'email' => $user->email,
            'password' => fake()->password,
            'token' => $token
        ])
            ->assertJsonValidationErrors([
                'password' => 'The password field confirmation does not match.'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function passwords_must_match(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $this->postJson(route('auth.reset-password.store'), [
            'email' => $user->email,
            'password' => fake()->password,
            'password_confirmation' => fake()->password,
            'token' => $token
        ])
            ->assertJsonValidationErrors([
                'password' => 'The password field confirmation does not match.'
            ])
            ->assertStatus(422);
    }
}
