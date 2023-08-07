<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Models\User;
use App\Helper\Helper;
use App\Events\GuestSignup;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use App\Services\ProfilePictureService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\{Hash, Cache};
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\{SignUpRequest, LoginRequest, ForgotPasswordRequest};

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

        Cache::put('user' . $user->id, $user, now()->addHour());

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token
        ], Response::HTTP_OK);
    }

    public function register(SignUpRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->has('account_number') && $request->has('bank_code')) {
            try {
                //create paystack subaccount
                $data = [
                    'business_name' => $request->name,
                    'bank_code' => $request->bank_code,
                    'account_number' => $request->account_number,
                    'percentage_charge' => 20
                ];
                $payService = app(PaymentService::class);

                $result = $payService->createSubaccount($data);
                if (!is_array($result)) {
                    throw new Exception($result->getMessage(), 1);
                }
                $data = array_merge($request->validated(), ['subaccount_code' => $result['subaccount_code']]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Problem uploading account details',
                    'data' => $th->getMessage()
                ], 302);
            }
        }

        // Logic for handling user registration
        $user = User::create($data);

        if ($request->hasFile('profile_picture')) 
        {
            $user->addMediaFromRequest('profile_picture')->toMediaCollection('avatars');
        } else {

            // Add random Image
            app(ProfilePictureService::class)->assignRandomProfilePicture($user);
            
        }

        event(new GuestSignup($user)); // @phpstan-ignore-line

        return response()->json([
            'message' => 'User created successfully',
            'user' => new UserResources($user)
        ], Response::HTTP_CREATED);
    }

    public function logout(): JsonResponse
    {
        Cache::forget('user');
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
