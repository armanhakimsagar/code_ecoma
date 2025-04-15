<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\SellLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function userTransaction(Request $request, $userId = null)
    {
        $pageTitle = 'Transaction Logs';
        $remarks = Transaction::whereNotNull('user_id')->distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::whereNotNull('user_id')->searchable(['trx', 'user:username'])->filter(['trx_type', 'remark'])->dateFilter()->orderBy('id', 'desc')->with('user');

        if ($userId) {
            $transactions = $transactions->where('user_id', $userId);
        }

        $transactions = $transactions->paginate(getPaginate());
        return view('admin.reports.transactions.index', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function sellerTransaction(Request $request, $userId = null)
    {
        $pageTitle = 'Transaction Logs';
        $remarks = Transaction::whereNotNull('seller_id')->distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::whereNotNull('seller_id')->searchable(['trx', 'seller:username'])->filter(['trx_type', 'remark'])->dateFilter()->orderBy('id', 'desc')->with('seller');

        if ($userId) {
            $transactions = $transactions->where('seller_id', $userId);
        }

        $transactions = $transactions->paginate(getPaginate());
        return view('admin.reports.transactions.index', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function userLoginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::whereNotNull('user_id')->orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.logins.index', compact('pageTitle', 'loginLogs'));
    }

    public function userLoginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::whereNotNull('user_id')->where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins.index', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function sellerLoginHistory(Request $request)
    {
        $pageTitle = 'Seller Login History';
        $loginLogs = UserLogin::whereNotNull('seller_id')->orderBy('id', 'desc')->searchable(['seller:username'])->dateFilter()->with('seller')->paginate(getPaginate());
        return view('admin.reports.logins.index', compact('pageTitle', 'loginLogs'));
    }

    public function sellerLoginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::whereNotNull('seller_id')->where('user_ip', $ip)->orderBy('id', 'desc')->with('seller')->paginate(getPaginate());
        return view('admin.reports.logins.index', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function userNotificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs = NotificationLog::whereNotNull('user_id')->orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.notifications.index', compact('pageTitle', 'logs'));
    }

    public function sellerNotificationHistory(Request $request)
    {
        $pageTitle = 'Seller Notification History';
        $logs = NotificationLog::whereNotNull('seller_id')->orderBy('id', 'desc')->searchable(['seller:username'])->dateFilter()->with('seller')->paginate(getPaginate());
        return view('admin.reports.notifications.index', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.notifications.email_details', compact('pageTitle', 'email'));
    }

    public function commissionLogs()
    {
        $pageTitle     = "My Commission Log";
        $logs          = SellLog::searchable(['order_id'])->where('seller_id', '!=', 0)->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.commission_log', compact('pageTitle', 'logs'));
    }

    public function orderLogs($userId)
    {
        $user = User::findOrFail($userId);
        $pageTitle = 'Order Logs of ' . $user->fullname;
        $orders     =  Order::where('user_id', $user->id)->where('payment_status', '!=', Status::PAYMENT_INITIATE)->searchable(['order_number'])->dateFilter()->with('deposit')->paginate(getPaginate());
        return view('admin.reports.orders', compact('pageTitle', 'user', 'orders'));
    }
}
