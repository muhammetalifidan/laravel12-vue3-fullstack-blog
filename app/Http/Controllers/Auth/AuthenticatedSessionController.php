<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    /**
     * Login a user.
     * @param \App\Http\Requests\LoginRequest $request
     * @return JsonResponse|mixed
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $token = $request->user()->createToken('auth-token')->plainTextToken;

        event(new UserLoggedIn($request->user()));

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
            ],
        ], 200);
    }

    /**
     * Logout a user.
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }
}
