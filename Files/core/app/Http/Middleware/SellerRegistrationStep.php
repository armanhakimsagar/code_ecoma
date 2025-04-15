<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SellerRegistrationStep
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $seller = seller();
        if (!$seller->profile_complete) {
            if ($request->is('api/*')) {
                $notify[] = 'Please complete your profile to go next';
                return response()->json([
                    'remark' => 'profile_incomplete',
                    'status' => 'error',
                    'message' => ['error' => $notify],
                ]);
            } else {
                return to_route('seller.data');
            }
        }
        return $next($request);
    }
}
