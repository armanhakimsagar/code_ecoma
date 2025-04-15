<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function trackOrder()
    {
        $pageTitle = 'Order Tracking';
        return view('Template::order_track', compact('pageTitle'));
    }

    public function getOrderTrackData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|max:160',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $order = Order::valid()->where('order_number', $request->order_number)->first();
        if ($order) {
            return response()->json([
                'success' => true,
                'pending' => in_array($order->status, [Status::ORDER_PENDING, Status::ORDER_DELIVERED, Status::ORDER_PROCESSING, Status::ORDER_DISPATCHED, Status::ORDER_READY_TO_DELIVER]),
                'processing' => in_array($order->status, [Status::ORDER_DELIVERED, Status::ORDER_PROCESSING, Status::ORDER_DISPATCHED, Status::ORDER_READY_TO_DELIVER]),
                'ready_to_deliver' => in_array($order->status, [Status::ORDER_DELIVERED, Status::ORDER_DISPATCHED, Status::ORDER_READY_TO_DELIVER]),
                'dispatched' => in_array($order->status, [Status::ORDER_DELIVERED, Status::ORDER_DISPATCHED]),
                'delivered' => $order->status == Status::ORDER_DELIVERED,
                'canceled' => $order->status == Status::ORDER_CANCELED
            ]);
        } else {
            $notify = 'No order found';
            return response()->json(['success' => false, 'message' => $notify]);
        }
    }
}
