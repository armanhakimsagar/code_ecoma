<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;
use Illuminate\Http\Request;

class KycMiddleware
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
        if ($request->is('api/*') && ($seller->kv == Status::KYC_UNVERIFIED || $seller->kv == Status::KYC_PENDING)) {
            $notify[] = 'You are unable to withdraw due to KYC verification';
            return response()->json([
                'remark'=>'kyc_verification',
                'status'=>'error',
                'message'=>['error'=>$notify],
            ]);
        }
        if ($seller->kv == Status::KYC_UNVERIFIED) {
            $notify[] = ['error','You are not KYC verified. For being KYC verified, please provide these information'];
            return to_route('seller.kyc.form')->withNotify($notify);
        }
        if ($seller->kv == Status::KYC_PENDING) {
            $notify[] = ['warning','Your documents for KYC verification is under review. Please wait for admin approval'];
            return to_route('seller.home')->withNotify($notify);
        }
        return $next($request);
    }
}
