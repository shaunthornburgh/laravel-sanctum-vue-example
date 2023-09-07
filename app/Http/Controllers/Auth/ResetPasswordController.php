<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordTokenRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function store(UpdatePasswordTokenRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        $user = User::where('email', $request->validated('email'))->first();

        return $status === Password::PASSWORD_RESET
            ? response()->json([
                'message' => __($status),
                'token' => $user->createToken('AppToken')->plainTextToken
            ])
            : response()->json(['message' => __($status)], 500);
    }
}
