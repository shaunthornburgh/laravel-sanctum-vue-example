<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;

class RegistrationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     */
    public function store(StoreUserRequest $request): Response
    {
        $user = User::create($request->validated());

        return response([
            'user' => new UserResource($user),
            'token' => $user->createToken('AppToken')->plainTextToken
        ], 201);
    }
}
