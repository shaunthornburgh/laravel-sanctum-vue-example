<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreAuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAuthRequest $request
     * @return Response
     */
    public function store(StoreAuthRequest $request): Response
    {
        $user = User::where('email', $request->validated('email'))->first();

        if (!$user || !Hash::check($request->validated('password'), $user->password)) {
            return response([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        return response([
            'user' => new UserResource($user),
            'token' => $user->createToken('AppToken')->plainTextToken
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): Response
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged out'
        ], 200);
    }
}
