<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,array ...$requiredScopes): Response
    {
        $user = $request->user();
        $userScopes = $user->token()->scopes;

        foreach ($requiredScopes as $scope) {
            if (in_array($scope, $userScopes)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'You are not authorized to access this resource'
        ], Response::HTTP_FORBIDDEN);
    }
}
