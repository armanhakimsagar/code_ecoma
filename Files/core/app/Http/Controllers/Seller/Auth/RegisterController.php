<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Seller;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Sign Up as Seller";
        return view('Template::seller.auth.register', compact('pageTitle'));
    }

    public function register(Request $request)
    {
        if (!gs('registration')) {
            $notify[] = ['error', 'Registration not allowed'];
            return back()->withNotify($notify);
        }

        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function guard()
    {
        return Auth::guard('seller');
    }

    protected function validator(array $data)
    {
        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }

        $validate     = Validator::make($data, [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:sellers',
            'password'  => ['required', 'confirmed', $passwordValidation],
            'captcha'   => 'sometimes|required',
            'agree'     => $agree
        ], [
            'firstname.required' => 'The first name field is required',
            'lastname.required' => 'The last name field is required'
        ]);

        return $validate;
    }


    protected function create(array $data)
    {

        $seller = new Seller();
        $seller->firstname    = @$data['firstname'] ?? null;
        $seller->lastname     = @$data['lastname'] ?? null;
        $seller->email        = strtolower(trim($data['email']));
        $seller->password     = Hash::make($data['password']);


        $seller->status = Status::USER_ACTIVE;
        $seller->ev = gs('ev') ? Status::NO : Status::YES;
        $seller->sv = gs('sv') ? Status::NO : Status::YES;
        $seller->ts = Status::DISABLE;
        $seller->tv = Status::ENABLE;
        $seller->save();

        $adminNotification = new AdminNotification();
        $adminNotification->seller_id = $seller->id;
        $adminNotification->title = 'New seller registered';
        $adminNotification->click_url = urlPath('admin.sellers.detail', $seller->id);
        $adminNotification->save();

        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip', $ip)->first();
        $sellerLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $sellerLogin->longitude =  $exist->longitude;
            $sellerLogin->latitude =  $exist->latitude;
            $sellerLogin->city =  $exist->city;
            $sellerLogin->country_code = $exist->country_code;
            $sellerLogin->country =  $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $sellerLogin->longitude =  @implode(',', $info['long']);
            $sellerLogin->latitude =  @implode(',', $info['lat']);
            $sellerLogin->city =  @implode(',', $info['city']);
            $sellerLogin->country_code = @implode(',', $info['code']);
            $sellerLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $sellerLogin->seller_id = $seller->id;
        $sellerLogin->user_ip =  $ip;

        $sellerLogin->browser = @$userAgent['browser'];
        $sellerLogin->os = @$userAgent['os_platform'];
        $sellerLogin->save();


        return $seller;
    }

    public function checkSeller(Request $request)
    {
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = Seller::where('email', $request->email)->exists();
            $exist['type'] = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data'] = Seller::where('mobile', $request->mobile)->where('dial_code', $request->mobile_code)->exists();
            $exist['type'] = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data'] = Seller::where('username', $request->username)->exists();
            $exist['type'] = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        return redirect()->route('seller.home');
    }
}
