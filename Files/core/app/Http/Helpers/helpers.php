<?php

use App\Constants\Status;
use App\Lib\GoogleAuthenticator;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use App\Lib\Captcha;
use App\Lib\ClientInfo;
use App\Lib\CurlRequest;
use App\Lib\FileManager;
use App\Models\Language;
use App\Notify\Notify;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Laramin\Utility\VugiChugi;

function systemDetails()
{
    $system['name'] = 'ecoma';
    $system['version'] = '2.0';
    $system['build_version'] = '5.1.4';
    return $system;
}

function slug($string)
{
    return Str::slug($string);
}

function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = (int) ($min - 1) . '9';
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function activeTemplate($asset = false)
{
    $template = session('template') ?? gs('active_template');
    if ($asset) return 'assets/templates/' . $template . '/';
    return 'templates.' . $template . '.';
}

function activeTemplateName()
{
    $template = session('template') ?? gs('active_template');
    return $template;
}

function siteLogo($type = null)
{
    $name = $type ? "/logo_$type.png" : '/logo.png';
    return getImage(getFilePath('logoIcon') . $name);
}
function siteFavicon()
{
    return getImage(getFilePath('logoIcon') . '/favicon.png');
}

function loadReCaptcha()
{
    return Captcha::reCaptcha();
}

function loadCustomCaptcha($width = '100%', $height = 46, $bgColor = '#003')
{
    return Captcha::customCaptcha($width, $height, $bgColor);
}

function verifyCaptcha()
{
    return Captcha::verify();
}

function loadExtension($key)
{
    $extension = Extension::where('act', $key)->where('status', Status::ENABLE)->first();
    return $extension ? $extension->generateScript() : '';
}

function getTrx($length = 12)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getAmount($amount, $length = 2)
{
    $amount = round($amount ?? 0, $length);
    return $amount + 0;
}

function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false, $currencyFormat = true)
{
    $separator = '';
    if ($separate) {
        $separator = ',';
    }
    $printAmount = number_format($amount, $decimal, '.', $separator);
    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        } else {
            $printAmount = rtrim($printAmount, '0');
        }
    }
    if ($currencyFormat) {
        if (gs('currency_format') == Status::CUR_BOTH) {
            return gs('cur_sym') . $printAmount . ' ' . __(gs('cur_text'));
        } elseif (gs('currency_format') == Status::CUR_TEXT) {
            return $printAmount . ' ' . __(gs('cur_text'));
        } else {
            return gs('cur_sym') . $printAmount;
        }
    }
    return $printAmount;
}


function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet)
{
    return "https://api.qrserver.com/v1/create-qr-code/?data=$wallet&size=300x300&ecc=m";
}

function keyToTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}


function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}


function strLimit($title = null, $length = 10)
{
    return Str::limit($title, $length);
}


function getIpInfo()
{
    $ipInfo = ClientInfo::ipInfo();
    return $ipInfo;
}


function osBrowser()
{
    $osBrowser = ClientInfo::osBrowser();
    return $osBrowser;
}


function getTemplates()
{
    $param['purchasecode'] = env("PURCHASECODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
    $url = VugiChugi::gttmp() . systemDetails()['name'];
    $response = CurlRequest::curlPostContent($url, $param);
    if ($response) {
        return $response;
    } else {
        return null;
    }
}


function getPageSections($arr = false)
{
    $jsonUrl = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }
    return $sections;
}


function getImage($image, $size = null)
{
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }
    if ($size) {
        return route('placeholder.image', $size);
    }
    return asset('assets/images/default.png');
}


function notify($user, $templateName, $shortCodes = null, $sendVia = null, $createLog = true, $pushImage = null)
{
    $globalShortCodes = [
        'site_name' => gs('site_name'),
        'site_currency' => gs('cur_text'),
        'currency_symbol' => gs('cur_sym'),
    ];

    if (gettype($user) == 'array') {
        $user = (object) $user;
    }

    $shortCodes = array_merge($shortCodes ?? [], $globalShortCodes);

    $notify = new Notify($sendVia);
    $notify->templateName = $templateName;
    $notify->shortCodes = $shortCodes;
    $notify->user = $user;
    $notify->createLog = $createLog;
    $notify->pushImage = $pushImage;
    $notify->userColumn = isset($user->id) ? $user->getForeignKey() : 'user_id';
    $notify->send();
}

function getPaginate($paginate = null)
{
    if (!$paginate) {
        $paginate = gs('paginate_number');
    }
    return $paginate;
}

function paginateLinks($data, $view = null)
{
    return $data->appends(request()->all())->links($view);
}


function menuActive($routeName, $type = null, $param = null)
{
    if ($type == 3) $class = 'side-menu--open';
    elseif ($type == 2) $class = 'sidebar-submenu__open';
    else $class = 'active';

    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) return $class;
        }
    } elseif (request()->routeIs($routeName)) {
        if ($param) {
            $routeParam = array_values(@request()->route()->parameters ?? []);
            if (strtolower(@$routeParam[0]) == strtolower($param)) return $class;
            else return;
        }
        return $class;
    }
}


function fileUploader($file, $location, $size = null, $old = null, $thumb = null, $filename = null)
{
    $fileManager = new FileManager($file);
    $fileManager->path = $location;
    $fileManager->size = $size;
    $fileManager->old = $old;
    $fileManager->thumb = $thumb;
    $fileManager->filename = $filename;
    $fileManager->upload();
    return $fileManager->filename;
}

function fileManager()
{
    return new FileManager();
}

function getFilePath($key)
{
    return fileManager()->$key()->path;
}

function getFileSize($key)
{
    return fileManager()->$key()->size;
}

function getFileExt($key)
{
    return fileManager()->$key()->extensions;
}

function getFileThumb($key)
{
    return fileManager()->$key()->thumb;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    if (!$lang) {
        $lang = getDefaultLang();
    }

    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}


function showDateTime($date, $format = 'Y-m-d h:i A')
{
    if (!$date) {
        return '-';
    }
    $lang = session()->get('lang');
    if (!$lang) {
        $lang = getDefaultLang();
    }

    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

function getDefaultLang()
{
    return Language::where('is_default', Status::YES)->first()->code ?? 'en';
}


function getContent($dataKeys, $singleQuery = false, $limit = null, $orderById = false)
{

    $templateName = activeTemplateName();
    if ($singleQuery) {
        $content = Frontend::where('tempname', $templateName)->where('data_keys', $dataKeys)->orderBy('id', 'desc')->first();
    } else {
        $article = Frontend::where('tempname', $templateName);
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id', 'desc')->get();
        }
    }
    return $content;
}

function verifyG2fa($user, $code, $secret = null)
{
    $authenticator = new GoogleAuthenticator();
    if (!$secret) {
        $secret = $user->tsc;
    }
    $oneCode = $authenticator->getCode($secret);
    $userCode = $code;
    if ($oneCode == $userCode) {
        $user->tv = Status::YES;
        $user->save();
        return true;
    } else {
        return false;
    }
}


function urlPath($routeName, $routeParam = null)
{
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path = str_replace($basePath, '', $url);
    return $path;
}


function showMobileNumber($number)
{
    $length = strlen($number);
    return substr_replace($number, '***', 2, $length - 4);
}

function showEmailAddress($email)
{
    $endPosition = strpos($email, '@') - 1;
    return substr_replace($email, '***', 1, $endPosition);
}


function getRealIP()
{
    $ip = $_SERVER["REMOTE_ADDR"];
    //Deep detect ip
    if (filter_var(@$_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    }
    if (filter_var(@$_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }

    return $ip;
}


function appendQuery($key, $value)
{
    return request()->fullUrlWithQuery([$key => $value]);
}

function dateSort($a, $b)
{
    return strtotime($a) - strtotime($b);
}

function dateSorting($arr)
{
    usort($arr, "dateSort");
    return $arr;
}

function gs($key = null)
{
    $general = Cache::get('GeneralSetting');
    if (!$general) {
        $general = GeneralSetting::first();
        Cache::put('GeneralSetting', $general);
    }
    if ($key) return @$general->$key;
    return $general;
}
function isImage($string)
{
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $fileExtension = pathinfo($string, PATHINFO_EXTENSION);
    if (in_array($fileExtension, $allowedExtensions)) {
        return true;
    } else {
        return false;
    }
}

function isHtml($string)
{
    if (preg_match('/<.*?>/', $string)) {
        return true;
    } else {
        return false;
    }
}


function convertToReadableSize($size)
{
    preg_match('/^(\d+)([KMG])$/', $size, $matches);
    $size = (int)$matches[1];
    $unit = $matches[2];

    if ($unit == 'G') {
        return $size . 'GB';
    }

    if ($unit == 'M') {
        return $size . 'MB';
    }

    if ($unit == 'K') {
        return $size . 'KB';
    }

    return $size . $unit;
}


function frontendImage($sectionName, $image, $size = null, $seo = false)
{
    if ($seo) {
        return getImage('assets/images/frontend/' . $sectionName . '/seo/' . $image, $size);
    }
    return getImage('assets/images/frontend/' . $sectionName . '/' . $image, $size);
}


function buildResponse($remark, $status, $notify, $data = null)
{
    $response = [
        'remark' => $remark,
        'status' => $status,
    ];
    $message = [];
    if ($notify instanceof \Illuminate\Support\MessageBag) {
        $message['error']  = collect($notify)->map(function ($item) {
            return $item[0];
        })->values()->toArray();
    } else {
        $message = [$status => collect($notify)->map(function ($item) {
            if (is_string($item)) {
                return $item;
            }
            if (count($item) > 1) {
                return $item[1];
            }
            return $item[0];
        })->toArray()];
    }
    $response['message'] = $message;
    if ($data) {
        $response['data'] = $data;
    }
    return response()->json($response);
}

function responseSuccess($remark, $notify, $data = null)
{
    return buildResponse($remark, 'success', $notify, $data);
}

function responseError($remark, $notify, $data = null)
{
    return buildResponse($remark, 'error', $notify, $data);
}

function calculateDiscount($amount, $type, $base_price)
{
    if ($type == 1) {
        $discount = $amount;
    } else {
        $discount = ($amount * $base_price) / 100;
    }
    return $discount;
}

function makeHtmlElement($tag, $class, $content, $addionalClass = '')
{
    return "<$tag class='badge $addionalClass badge--$class'>" . trans($content) . "</$tag>";
}

function moveElement(&$array, $oldIndex, $newIndex)
{
    if ($oldIndex == $newIndex) {
        // No need to move if the old and new indices are the same
        return;
    }
    // Remove the element from the old index
    $element = array_splice($array, $oldIndex, 1)[0];

    // Insert the element at the new index
    array_splice($array, $newIndex, 0, [$element]);
}

function checkWishList($productId)
{
    $wishlist = array_keys(session()->get('wishlist') ?? []);
    return in_array($productId, $wishlist);
}

function checkCompareList($productId)
{
    $compare = array_keys(session()->get('compare') ?? []);
    return in_array($productId, $compare);
}

function numberShortFormat($n)
{
    $n_format = 0;
    $suffix = '';
    if ($n > 0 && $n < 1000) {
        // 1 - 999
        $n_format = floor($n);
    } else if ($n >= 1000 && $n < 1000000) {
        // 1k-999k
        $n_format = floor($n / 1000);
        $suffix = 'K+';
    } else if ($n >= 1000000 && $n < 1000000000) {
        // 1m-999m
        $n_format = floor($n / 1000000);
        $suffix = 'M+';
    } else if ($n >= 1000000000 && $n < 1000000000000) {
        // 1b-999b
        $n_format = floor($n / 1000000000);
        $suffix = 'B+';
    } else if ($n >= 1000000000000) {
        // 1t+
        $n_format = floor($n / 1000000000000);
        $suffix = 'T+';
    }

    return [$n_format, $suffix];
}

function seller()
{
    return auth()->guard('seller')->user();
}


function combinations($arrays, $i = 0)
{
    if (sizeof($arrays) == 1) {
        foreach ($arrays[0] as $arr) {
            $temp_array[]    = $arr;
            $final_array[]   =  $temp_array;
            unset($temp_array);
        }
        return  $final_array;
    }
    if (!isset($arrays[$i])) {
        return array();
    }
    if ($i == count($arrays) - 1) {
        return $arrays[$i];
    }
    // get combinations from subsequent arrays
    $tmp = combinations($arrays, $i + 1);
    $result = array();
    // concat each array from tmp with each element from $arrays[$i]
    foreach ($arrays[$i] as $v) {
        foreach ($tmp as $t) {
            $result[] = is_array($t) ?
                array_merge(array($v), $t) :
                array($v, $t);
        }
    }
    return $result;
}


function getSeoContents($collection, $imageData, $imageColumnName)
{
    $seoContents['keywords']            = $collection->meta_keywords ?? [];
    $seoContents['description']         = htmlspecialchars_decode($collection->meta_description);
    $seoContents['social_title']        = htmlspecialchars_decode($collection->meta_title);
    $seoContents['social_description']  = htmlspecialchars_decode($collection->meta_description);
    $seoContents['image']               = getImage($imageData['path'] . '/' . $collection->$imageColumnName, $imageData['size']);
    $seoContents['image_size']          = $imageData['size'];
    return (object) $seoContents;
}

function ratings($number)
{
    $html = '';
    $review = floor($number);
    $extra = abs($number - $review);
    $extraDone = 0;
    for ($i = 1; $i <= 5; $i++) {
        if ($review < $i) {
            if ($extra > 0 && $extraDone == 0) {
                $html .= '<i class="las la-star-half-alt fill"></i>';
                $extraDone = 1;
            } else {
                $html .= '<i class="lar la-star"></i>';
            }
        } else {
            $html .= '<i class="las la-star fill"></i>';
        }
    }
    return $html;
}

function displayAvgRating($rating)
{
    $ratingCount   =  $rating->count();
    $sumOfRating   =  $rating->sum('rating');

    if ($ratingCount == 0) {
        $ratingCount = 1;
    }

    $rating_avg    =  $sumOfRating / $ratingCount;
    $prec          = round($rating_avg, 2) - intval($rating_avg);
    $result        = '';

    if ($prec > 0.25) {
        $rating_avg = intval($rating_avg) + 0.5;
    }

    if ($prec > 0.75) {
        $rating_avg = intval($rating_avg) + 1;
    }

    for ($i = 0; $i < intval($rating_avg); $i++) {
        $result .= '<i class="la la-star"></i>';
    }

    if ($rating_avg - intval($rating_avg) == 0.5) {
        $i++;
        $result .= '<i class="las la-star-half-alt"></i>';
    }

    for ($k = 0; $k < 5 - $i; $k++) {
        $result .= '<i class="lar la-star"></i>';
    }
    return $result;
}

function sellerSidebarVariation()
{
    /// for sidebar
    $variation['sidebar'] = '';
    //for selector
    $variation['selector'] = 'capsule--rounded';
    //for overlay
    $variation['overlay'] = 'bg--dark';
    $variation['opacity'] = 'overlay--opacity-4';

    return $variation;
}

function paginate($items, $perPage = 5, $page = null,  $options = [])
{
    $page = $page ?: (Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);

    $items = $items instanceof Illuminate\Database\Eloquent\Collection ? $items : Illuminate\Database\Eloquent\Collection::make($items);
    return new Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [

        'path' => Illuminate\Pagination\Paginator::resolveCurrentPath(),
        'pageName' => 'page',

    ]);
}


function getAvatar($image, $clean = '')
{
    return file_exists($image) && is_file($image) ? asset($image) . $clean : getImage(getFilePath('avatar'), getFileSize('avatar'));
}
