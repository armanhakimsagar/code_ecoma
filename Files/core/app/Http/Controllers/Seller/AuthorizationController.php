<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Constants\Status;

class AuthorizationController extends Controller
{
    protected function checkCodeValidity($seller,$addMin = 2)
    {
        if (!$seller->ver_code_send_at){
            return false;
        }
        if ($seller->ver_code_send_at->addMinutes($addMin) < Carbon::now()) {
            return false;
        }
        return true;
    }

    public function authorizeForm()
    {
        $user = auth()->guard('seller')->user();
        if (!$user->status) {
            $pageTitle = 'Banned';
            $type = 'ban';
        }elseif(!$user->ev) {
            $type = 'email';
            $pageTitle = 'Verify Email';
            $notifyTemplate = 'EVER_CODE';
        }elseif (!$user->sv) {
            $type = 'sms';
            $pageTitle = 'Verify Mobile Number';
            $notifyTemplate = 'SVER_CODE';
        }elseif (!$user->tv) {
            $pageTitle = '2FA Verification';
            $type = '2fa';
        }else{
            return to_route('seller.home');
        }

        if (!$this->checkCodeValidity($user) && ($type != '2fa') && ($type != 'ban')) {
            $user->ver_code = verificationCode(6);
            $user->ver_code_send_at = Carbon::now();
            $user->save();
            notify($user, $notifyTemplate, [
                'code' => $user->ver_code
            ],[$type]);
        }

        return view('Template::seller.auth.authorization.'.$type, compact('user', 'pageTitle'));

    }

    public function sendVerifyCode($type)
    {
        $seller = auth()->guard('seller')->user();

        if ($this->checkCodeValidity($seller)) {
            $targetTime = $seller->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $targetTime - time();
            throw ValidationException::withMessages(['resend' => 'Please try after ' . $delay . ' seconds']);
        }

        $seller->ver_code = verificationCode(6);
        $seller->ver_code_send_at = Carbon::now();
        $seller->save();

        if ($type == 'email') {
            $type = 'email';
            $notifyTemplate = 'EVER_CODE';
        } else {
            $type = 'sms';
            $notifyTemplate = 'SVER_CODE';
        }

        notify($seller, $notifyTemplate, [
            'code' => $seller->ver_code
        ],[$type]);

        $notify[] = ['success', 'Verification code sent successfully'];
        return back()->withNotify($notify);
    }

    public function emailVerification(Request $request)
    {
        $request->validate([
            'code'=>'required'
        ]);

        $seller = seller();

        if ($seller->ver_code == $request->code) {
            $seller->ev = Status::VERIFIED;
            $seller->ver_code = null;
            $seller->ver_code_send_at = null;
            $seller->save();

            return redirect()->intended(route('seller.home'));
        }
        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    public function mobileVerification(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);


        $seller = auth()->guard('seller')->user();
        if ($seller->ver_code == $request->code) {
            $seller->sv = Status::VERIFIED;
            $seller->ver_code = null;
            $seller->ver_code_send_at = null;
            $seller->save();
            return redirect()->intended(route('seller.home'));
        }
        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    public function g2faVerification(Request $request)
    {
        $seller = auth()->guard('seller')->user();
        $request->validate([
            'code' => 'required',
        ]);
        $response = verifyG2fa($seller,$request->code);
        if ($response) {
            return redirect()->intended(route('seller.home'));
        }else{
            $notify[] = ['error','Wrong verification code'];
            return back()->withNotify($notify);
        }
    }
}
