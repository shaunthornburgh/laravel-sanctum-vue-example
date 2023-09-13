<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StorePasswordTokenRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function store(StorePasswordTokenRequest $request): JsonResponse
    {
        $status = Password::sendResetLink([
            'email' => $request->validated('email')
        ]);

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => __($status)], 500);
    }
}
