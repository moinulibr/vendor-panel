<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExternalAccessUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedRoles): Response
    {
        $user = $request->user();

        // 1. Check User Authenticated & Active Status
        if (!$user || !$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is inactive or token is invalid.'
            ], Response::HTTP_FORBIDDEN);
        }

        // Default Allowed Roles if not passed in route
        $roles = count($allowedRoles) > 0 ? $allowedRoles : ['sr', 'retailer'];

        // 2. Validate User Type
        if (in_array($user->user_type, $roles)) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized: Your role does not have access to this resource.'
        ], Response::HTTP_FORBIDDEN);
    }
}
