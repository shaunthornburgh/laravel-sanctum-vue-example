<?php

namespace Tests\Feature\Auth;

use App\Events\PasswordResetFormSubmitted;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ForgotPasswordApiTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    public function test_a_user_can_request_a_new_password_token(): void
    {
        $this->withoutExceptionHandling();

        Event::fake();

        $user = User::factory()->create();

        $this->post(route('auth.forgot-password.store'), [
            'email' => $user->email
        ])
            ->assertStatus(200);
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email
        ]);

        Event::assertDispatched(PasswordResetFormSubmitted::class);
    }

    /** @test */
    public function email_field_is_required(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('auth.forgot-password.store'), [])
            ->assertJsonValidationErrors([
                'email' => 'The email field is required.'
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email
        ]);
    }

    /** @test */
    public function email_field_is_valid(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('auth.forgot-password.store'), [
            'email' => 'WRONG'
        ])
            ->assertJsonValidationErrors([
                'email' => 'The email field must be a valid email address.'
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email
        ]);
    }

    /** @test */
    public function email_field_exists(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('auth.forgot-password.store'), [
            'email' => 'john.doe@gmail.com'
        ])
            ->assertJsonValidationErrors([
                'email' => 'The selected email is invalid.'
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email
        ]);
    }
}
