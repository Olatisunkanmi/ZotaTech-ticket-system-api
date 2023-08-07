<?php

namespace App\Http\Controllers\api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\{UserResources};
use Exception;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{

    /**
     * Get all users.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            //code...
            $users = Helper::saveToCache('users', User::all(), now()->addHour());

            return response()->json([
                'message' => 'Retrieved successfully',
                'data' => $users
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Users not found'], 404);
        }
    }


    /**
     * Get a specific user.
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        try {
            //code...
            $user = Helper::getFromCache('user', $id);


            if (!$user) {
                $user = User::findOrFail($id);
                $user = Helper::saveToCache('users', $id, now()->addHour());
            }

            return response()->json([
                'message' => 'User Found',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Update the specified user in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            // Retrieve the user from the cache if available
            $cachedUser = Helper::getFromCache('users', $id);

            if ($cachedUser) {
                $user = $cachedUser;
            } else {
                // Retrieve the user from the database if not found in cache
                $user = User::findOrFail($id);
            }
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

            $user->update($data->except('profile_picture'));

            if ($request->hasFile('profile_picture')) {
                $user->clearMediaCollection('avatars');
                $user->addMediaFromRequest('profile_picture')->toMediaCollection('avatars');
                $user->save();
            };

            Helper::updateCache('users', $id, $user, now()->addHour(1));


            return response()->json([
                'message' => 'User updated successfully',
                'data' => new UserResources($user)
            ], 200);
        } catch (\Throwable $th) {
            // User not found
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    /**
     * Remove the specified user from storage.
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            //code...
            $user = Helper::getFromCache('users', $id);

            if ($user) {
                Helper::deleteFromCache('users', $id);
                $user->delete();
            }

            if (!$user) {
                $user = User::find($id);

                if ($user) {
                    $user->delete();
                }
            }

            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function getEvents(string $id)
    {
        try {
            //code...

            $user = User::findOrFail($id);
            $events = $user->events;

            return response()->json([
                'message' => 'Retrieved successfully',
                'data' => $events
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
