<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAccessType
{
    /**
     * Handle an incoming request.
     * 
     * Usage in routes:
     * middleware('access.type:1') // Only Internal Staff
     * middleware('access.type:2') // Only External Users
     */
    public function handle(Request $request, Closure $next, int ...$allowedAccessTypes): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated access.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Check if user account is allowed
        if (!in_array($user->access_type, $allowedAccessTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. You do not have permission for this portal.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
