<?php

namespace App\Http\Controllers\api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\{UserResources};
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
                'message' => 'User index',
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
            $user = Helper::getFromCache('users', $id);


            if (!$user) {
                $user = User::findOrFail($id);
                $user = Helper::saveToCache('users', $id, now()->addHour());
            }

            return response()->json([
                'message' => 'User show',
                'data' => new UserResources($user)
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
    public function update(Request $request,string $id)
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

            $user->update($request->all());
            Helper::updateCache('users', $id, $user, now()->addHour());


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
}
