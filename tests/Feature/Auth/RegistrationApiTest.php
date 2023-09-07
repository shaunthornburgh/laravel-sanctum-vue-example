<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationApiTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register(): void
    {
        $this->withoutExceptionHandling();

        $attributes = $this->getUserAttributes();

        $this->postJson(route('auth.register.store'), $attributes)
            ->assertStatus(201)
            ->assertJson(['user' => [
                'name' => $attributes['name'],
                'email' => $attributes['email']
            ]]);

        $this->assertDatabaseHas('users', ['name' => $attributes['name']]);
        $this->assertDatabaseHas('users', ['email' => $attributes['email']]);
    }

    /** @test */
    public function a_user_cannot_register_with_an_existing_email(): void
    {
        $attributes = $this->getUserAttributes();

        $this->postJson(route('auth.register.store'), $attributes);
        $this->postJson(route('auth.register.store'), $attributes)
            ->assertJsonValidationErrors([
                'email' => 'The email has already been taken.'
            ])
            ->assertStatus(422);

        unset($attributes['password_confirmation']);
        $this->assertDatabaseMissing('users', $attributes);
    }

    /** @test */
    public function name_field_is_required(): void
    {
        $attributes = $this->getUserAttributes();
        $attributes['name'] = null;

        $this->postJson(route('auth.register.store'), $attributes)
            ->assertJsonValidationErrors([
                'name' => 'The name field is required.'
            ])
            ->assertStatus(422);

        unset($attributes['password_confirmation']);
        $this->assertDatabaseMissing('users', $attributes);
    }

    /** @test */
    public function email_field_is_required(): void
    {
        $attributes = $this->getUserAttributes();
        $attributes['email'] = null;

        $this->postJson(route('auth.register.store'), $attributes)
            ->assertJsonValidationErrors([
                'email' => 'The email field is required.'
            ])
            ->assertStatus(422);

        unset($attributes['password_confirmation']);
        $this->assertDatabaseMissing('users', $attributes);
    }

    /** @test */
    public function email_value_must_be_a_valid_email_address(): void
    {
        $attributes = $this->getUserAttributes();
        $attributes['email'] = 'INVALID_EMAIL_ADDRESS';

        $this->postJson(route('auth.register.store'), $attributes)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'The email field must be a valid email address.'
            ]);

        unset($attributes['password_confirmation']);
        $this->assertDatabaseMissing('users', $attributes);
    }

    /** @test */
    public function password_field_is_required(): void
    {
        $attributes = $this->getUserAttributes();
        $attributes['password'] = null;

        $this->postJson(route('auth.register.store'), $attributes)
            ->assertJsonValidationErrors([
                'password' => 'The password field is required.'
            ])
            ->assertStatus(422);

        unset($attributes['password_confirmation']);
        $this->assertDatabaseMissing('users', $attributes);
    }

    /** @test */
    public function password_fields_must_match(): void
    {
        $attributes = $this->getUserAttributes();
        $attributes['password_confirmation'] = 'SOMETHING_ELSE';

        $this->postJson(route('auth.register.store'), $attributes)
            ->assertJsonValidationErrors([
                'password' => 'The password field confirmation does not match.'
            ])
            ->assertStatus(422);

        unset($attributes['password_confirmation']);
        $this->assertDatabaseMissing('users', $attributes);
    }

    private function getUserAttributes(): array
    {
        return User::factory()->raw([
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
    }
}
