<?php

namespace App\Http\Controllers\Seller;

use App\Constants\Status;
use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Product;
use App\Models\SellLog;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Rules\FileTypeValidate;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\SubOrder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class SellerController extends Controller
{

    public function home()
    {
        $pageTitle = 'Dashboard';
        $seller    = seller();

        $order['all']           = SubOrder::belongsToSeller()->orderNotCanceled()->count();
        $order['pending']       = SubOrder::belongsToSeller()->orderNotCanceled()->pending()->count();
        $order['processing']    = SubOrder::belongsToSeller()->orderNotCanceled()->processing()->count();
        $order['readyToPickup'] = SubOrder::belongsToSeller()->orderNotCanceled()->readyToPickup()->count();
        $order['delivered']     = SubOrder::belongsToSeller()->orderNotCanceled()->delivered()->count();
        $order['rejected']      = SubOrder::belongsToSeller()->orderNotCanceled()->rejected()->count();

        $product['total']       = Product::belongsToSeller()->active()->count();
        $product['approved']    = Product::belongsToSeller()->active()->count();
        $product['pending']     = Product::belongsToSeller()->pending()->count();
        $product['total_sold']  = Product::belongsToSeller()->active()->sum('sold');

        $sale['last_seven_days']    = SellLog::belongsToSeller()->where('created_at', '>=', Carbon::today()->subDays(7))->sum('after_commission');
        $sale['last_fifteen_days']  = SellLog::belongsToSeller()->where('created_at', '>=', Carbon::today()->subDays(15))->sum('after_commission');
        $sale['last_thirty_days']   = SellLog::belongsToSeller()->where('created_at', '>=', Carbon::today()->subDays(30))->sum('after_commission');

        $withdraw['total'] = Withdrawal::belongsToSeller()->where('status', 1)->sum('amount');
        $withdraw['pending'] = Withdrawal::belongsToSeller()->where('status', 2)->count();
        $withdraw['approved'] = Withdrawal::belongsToSeller()->where('status', 1)->count();

        $latestOrders = SubOrder::belongsToSeller()->orderNotCanceled()->pending()->with(['order', 'order.user'])->orderBy('id', 'DESC')->take(7)->get();
        return view('seller.dashboard', compact('pageTitle', 'order', 'sale', 'withdraw', 'product', 'latestOrders'));
    }

    public function sellLogs()
    {
        $pageTitle     = "Sales Log";
        $logs          = SellLog::searchable(['order_id'])->where('seller_id', seller()->id)->with('order:id,order_number')->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('seller.sales.index', compact('pageTitle', 'logs'));
    }

    public function shop()
    {
        $pageTitle  = 'Manage Shop';
        $seller     = seller();
        $shop       = $seller->shop;
        return view('seller.shop', compact('pageTitle', 'seller', 'shop'));
    }

    public function shopUpdate(Request $request)
    {
        $seller         = seller();
        $shop           = Shop::where('seller_id', $seller->id)->first();
        $logoValidation = $coverValidation = 'required';

        if ($shop) {
            $logoValidation     = $shop->logo ? 'nullable' : 'required';
            $coverValidation    = $shop->cover ? 'nullable' : 'required';
        }

        $rules = [
            'name'                  => 'required|string|max:40',
            'phone'                 => 'required|string|max:40',
            'address'               => 'required|string|max:600',
            'opening_time'          => 'nullable|date_format:H:i',
            'closing_time'          => 'nullable|date_format:H:i',
            'meta_title'            => 'nullable|string|max:191',
            'meta_description'      => 'nullable|string|max:191',
            'meta_keywords'         => 'nullable|array',
            'meta_keywords.array.*' => 'string',
            'social_links'          => 'nullable|array',
            'social_links.*.name'   => 'required_with:social_links|string',
            'social_links.*.icon'   => 'required_with:social_links|string',
            'social_links.*.link'   => 'required_with:social_links|string',

            'image'                 => [$logoValidation, 'max:2048',  new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'cover_image'           => [$coverValidation,  new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ];

        $messages = [
            'name.required' => 'The name field is required',
            'phone.required' => 'The phone field is required',
            'address.required' => 'The address field is required',
            'social_links.*.name.required_with'   => 'All social name is required',
            'social_links.*.icon.required_with'   => 'All social icon is required',
            'cover_image.required'                => 'Cover photo is required'
        ];

        $request->validate($rules, $messages, ['image' => 'logo']);

        if ($shop) {
            $message = 'The shop updated successfully';
        } else {
            $shop = new Shop();
            $message = 'The shop created successfully';
        }


        if ($request->hasFile('image')) {
            $location       = getFilePath('sellerShopLogo');
            $shop->logo     = fileUploader($request->image, $location, null, @$shop->logo);
        }

        if ($request->hasFile('cover_image')) {
            $location       = getFilePath('sellerShopCover');
            $size           = getFileSize('sellerShopCover');
            $shop->cover    = fileUploader($request->cover_image, $location, $size, @$seller->cover_image);
        }

        $shop->name              = $request->name;
        $shop->seller_id         = $seller->id;
        $shop->phone             = $request->phone;
        $shop->address           = $request->address;
        $shop->opens_at          = $request->opening_time;
        $shop->closed_at         = $request->closing_time;
        $shop->meta_title        = $request->meta_title;
        $shop->meta_description  = $request->meta_description;
        $shop->meta_keywords     = $request->meta_keywords ?? null;
        $shop->social_links      = $request->social_links ?? null;
        $shop->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function profile()
    {
        $pageTitle  = "Profile Setting";
        $seller     = seller();
        return view('seller.profile', compact('pageTitle', 'seller'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
            'address'   => 'nullable|string',
            'state'     => 'nullable|string',
            'zip'       => 'nullable|string',
            'city'      => 'nullable|string',
            'image'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);

        $seller = seller();

        $seller->firstname  = $request->firstname;
        $seller->lastname   = $request->lastname;
        $seller->state      = $request->state;
        $seller->city       = $request->city;
        $seller->zip        = $request->zip;
        $seller->address    = $request->address;

        if ($request->hasFile('image')) {
            $location       = getFilePath('sellerProfile');
            $size           = getFileSize('sellerProfile');
            $seller->image = fileUploader($request->image, $location, $size, $seller->image);
        }

        $seller->save();

        $notify[] = ['success', 'Profile updated successfully.'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle  = 'Change password';
        $seller     = seller();
        return view('seller.password', compact('pageTitle', 'seller'));
    }

    public function submitPassword(Request $request)
    {
        $passwordValidation    = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password'  => 'required',
            'password'          => ['required', 'confirmed', $passwordValidation]
        ]);

        $seller = seller();

        if (!Hash::check($request->current_password, $seller->password)) {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }

        $password = Hash::make($request->password);
        $seller->password = $password;
        $seller->save();

        $notify[] = ['success', 'Password changes successfully.'];
        return back()->withNotify($notify);
    }


    public function transactions()
    {
        $pageTitle      = "Transaction Logs";
        $transactions   = Transaction::searchable(['trx'])->where('seller_id', seller()->id)->orderBy('id', 'desc')->paginate(getPaginate());
        return view('seller.transactions', compact('pageTitle', 'transactions'));
    }

    public function kycForm()
    {
        if (seller()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('seller.home')->withNotify($notify);
        }

        if (seller()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('seller.home')->withNotify($notify);
        }

        $pageTitle = 'KYC Validation';
        $form = Form::where('act', 'kyc')->first();
        return view('seller.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $seller = seller();
        $pageTitle = 'KYC Data';
        abort_if($seller->kv == Status::VERIFIED, 403);
        return view('seller.kyc.info', compact('pageTitle', 'seller'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->firstOrFail();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $seller = seller();
        foreach (@$seller->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $sellerData = $formProcessor->processFormData($request, $formData);
        $seller->kyc_data = $sellerData;
        $seller->kyc_rejection_reason = null;
        $seller->kv = Status::KYC_PENDING;
        $seller->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('seller.home')->withNotify($notify);
    }

    public function show2faForm()
    {
        $general    = GeneralSetting::first();
        $ga         = new GoogleAuthenticator();
        $seller     = seller();
        $secret     = $ga->createSecret();
        $qrCodeUrl  = $ga->getQRCodeGoogleUrl($seller->username . '@' . $general->sitename, $secret);
        $pageTitle  = 'Two Factor Security';
        return view('seller.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $seller = seller();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);

        $response = verifyG2fa($seller, $request->code, $request->key);
        if ($response) {
            $seller->tsc = $request->key;
            $seller->ts = Status::ENABLE;
            $seller->save();
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $seller       = seller();
        $response   = verifyG2fa($seller, $request->code);

        if ($response) {
            $seller->tsc = null;
            $seller->ts = Status::DISABLE;
            $seller->save();

            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }


    public function sellerData()
    {
        $seller = seller();

        if ($seller->profile_complete == Status::YES) {
            return to_route('seller.home');
        }

        $pageTitle  = 'Seller Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::seller.seller_data', compact('pageTitle', 'seller', 'countries', 'mobileCode'));
    }

    public function sellerDataSubmit(Request $request)
    {
        $seller = seller();

        if ($seller->profile_complete == Status::YES) {
            return to_route('seller.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:sellers|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('sellers')->where('dial_code', $request->mobile_code)],
        ]);


        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $seller->country_code = $request->country_code;
        $seller->mobile       = $request->mobile;
        $seller->username     = $request->username;


        $seller->address = $request->address;
        $seller->city = $request->city;
        $seller->state = $request->state;
        $seller->zip = $request->zip;
        $seller->country_name = @$request->country;
        $seller->dial_code = $request->mobile_code;

        $seller->profile_complete = Status::YES;
        $seller->save();

        return to_route('seller.home');
    }

    public function withdrawChartData(Request $request)
    {
        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }

        $withdrawals = Withdrawal::approved()
            ->where('seller_id', seller()->id)
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'amount'     => getAmount($withdrawals->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }

        $data = collect($data);

        $report['created_on']   = $data->pluck('created_on');
        $report['data'] = [
            [
                'name' => 'Withdrawn',
                'data' => $data->pluck('amount')
            ]
        ];

        return response()->json($report);
    }

    private function getAllDates($startDate, $endDate)
    {
        $dates = [];
        $currentDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('d-F-Y');
            $currentDate->modify('+1 day');
        }

        return $dates;
    }

    private function  getAllMonths($startDate, $endDate)
    {
        if ($endDate > now()) {
            $endDate = now()->format('Y-m-d');
        }

        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $months = [];

        while ($startDate <= $endDate) {
            $months[] = $startDate->format('F-Y');
            $startDate->modify('+1 month');
        }

        return $months;
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }
}
