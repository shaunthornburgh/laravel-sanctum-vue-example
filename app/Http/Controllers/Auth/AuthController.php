<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreAuthRequest;
use App\Http\Resources\UserResource;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAuthRequest $request
     * @return JsonResponse
     */
    public function store(StoreAuthRequest $request): JsonResponse
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();

            return response()->json([
                'user' => new UserResource(Auth::user()),
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }
}
