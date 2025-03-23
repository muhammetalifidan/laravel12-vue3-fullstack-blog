<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    /**
     * Register a new user.
     * @param \App\Http\Requests\RegisterRequest $request
     * @return JsonResponse|mixed
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'data' => [
                'token' => $token,
            ],
        ], 201);
    }
}
