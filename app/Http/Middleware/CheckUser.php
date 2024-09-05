<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\{JsonResponse, RedirectResponse, Response };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        $user = Auth::guard('user')->user();

        if ($user) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => "Unauthorized",
            'data' => array()
        ],401);

    }
}
