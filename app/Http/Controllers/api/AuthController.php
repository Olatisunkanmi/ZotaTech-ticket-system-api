<?php

namespace App\Http\Controllers\api;

use App\Events\GuestSignup;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{Hash, Cache};
use App\Http\Requests\{SignUpRequest, LoginRequest, ForgotPasswordRequest};
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    // Methods for authentication functionality

    public function login(LoginRequest $request): JsonResponse
    {
        // Logic for handling user login
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->generateUserRole();

        Cache::put('user' . $user->id, $user, now()->addHour(1));

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token
        ], Response::HTTP_OK);
    }

    public function register(SignUpRequest $request): JsonResponse
    {
        // Logic for handling user registration
        $user = User::create($request->validated());

        event(new GuestSignup($user));

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], Response::HTTP_CREATED);
    }

    public function logout(): JsonResponse
    {

        Cache::forget('user' . auth()->user()->id);
        // Logic for handling user logout
        auth()->logout();

        return response()->json([
            'message' => 'User logged out successfully'
        ], Response::HTTP_OK);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        // Logic for handling forgot password
        $email  = $request->validated();

        $token = Helper::generateToken();

        return response()->json([
            'message' => 'Token has been sent to your email',
            'token' => $token
        ], Response::HTTP_OK);
    }
}
