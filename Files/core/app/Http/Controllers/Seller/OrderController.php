<?php

namespace App\Http\Controllers\Seller;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\StockLog;
use App\Models\SubOrder;

class OrderController extends Controller
{
    public function all()
    {
        $pageTitle      = "All Orders";
        $orders         = $this->getOrders();
        return view('seller.order.index', compact('pageTitle', 'orders'));
    }

    public function pending()
    {
        $pageTitle      = "Pending Orders";
        $orders         = $this->getOrders('pending');

        return view('seller.order.index', compact('pageTitle', 'orders'));
    }

    public function processing()
    {
        $pageTitle      = 'Processing Orders';
        $orders         = $this->getOrders('processing');

        return view('seller.order.index', compact('pageTitle', 'orders'));
    }

    public function readyToPickUp()
    {
        $pageTitle      = 'Ready To Pickup Orders';
        $orders         = $this->getOrders('readyToPickup');

        return view('seller.order.index', compact('pageTitle', 'orders'));
    }

    public function delivered()
    {
        $pageTitle      = 'Delivered Orders';
        $orders         = $this->getOrders('delivered');

        return view('seller.order.index', compact('pageTitle', 'orders'));
    }

    public function rejected()
    {
        $pageTitle     = "Rejected Orders";
        $orders        = $this->getOrders('rejected');

        return view('seller.order.index', compact('pageTitle', 'orders'));
    }

    public function orderDetails($id)
    {
        $suborder  = SubOrder::valid()->belongsToSeller()->with('order.user', 'order.shippingMethod', 'orderDetail.product')->findOrFail($id);
        $pageTitle = 'Order Details';
        return view('seller.order.details', compact('pageTitle', 'suborder'));
    }

    public function markAsProcessing($id)
    {
        $suborder = SubOrder::valid()->orderNotCanceled()->pending()->belongsToSeller()->with('order.user')->findOrFail($id);
        $suborder->status = Status::SUBORDER_PROCESSING;
        $suborder->save();

        $order = $suborder->order;
        if ($order->status == Status::ORDER_PENDING) {
            $order->status = Status::ORDER_PROCESSING;
            $order->save();

            if ($order->user) {
                notify($order->user, 'ORDER_ON_PROCESSING_CONFIRMATION', [
                    'site_name' => gs('sitename'),
                    'order_id'  => $order->order_number
                ]);
            }
        }

        $notify[] = ['success', 'Order marked as processing successfully'];
        return back()->withNotify($notify);
    }

    public function markAsReadyToPickUp($id){
        $suborder = SubOrder::valid()->orderNotCanceled()->processing()->belongsToSeller()->with('order.user')->findOrFail($id);
        $suborder->status = Status::SUBORDER_READY_TO_PICKUP;
        $suborder->save();

        $notify[] =['success', 'Order marked as ready for pickup successfully'];
        return back()->withNotify($notify);
    }

    public function reject($id)
    {
        $suborder = SubOrder::valid()->orderNotCanceled()->pending()->belongsToSeller()->with('orderDetail.product')->findOrFail($id);
        $suborder->status = Status::SUBORDER_REJECTED;
        $suborder->save();

        $order = Order::with('subOrders', 'user')->find($suborder->order_id);

        // update order amount
        $order->total_amount -= $suborder->total_amount;
        $order->save();

        // update product stock
        StockLog::restoreStock($suborder->id, true);


        // notify user
        if (@$order->user) {
            $products = $suborder->orderDetail->map(function ($item, $key) {
                return $item->product->name . ' (' . $item->quantity . ')';
            })->join(', ');

            notify($order->user, 'ORDER_ITEM_CANCELED', [
                'order_number' => $suborder->order_number,
                'products' => $products
            ]);
        }

        // check all suborders, if all suborders had been rejected then the order should be canceled automatically
        if ($order->subOrders->where('status', Status::SUBORDER_REJECTED)->count() == $order->subOrders->count()) {
            $order->autoCancel();
        }

        // admin notification
        $adminNotification = new AdminNotification();
        $adminNotification->seller_id = seller()->id;
        $adminNotification->title = 'Seller rejected the order #' . $suborder->order_number;
        $adminNotification->click_url = urlPath('admin.order.details', $suborder->order_id);
        $adminNotification->save();

        $notify[] = ['success', 'Order has been rejected successfully'];
        return back()->withNotify($notify);
    }

    private function getOrders($scope = null)
    {
        $query = SubOrder::valid()->orderNotCanceled()->belongsToSeller();

        if ($scope) {
            $query->$scope();
        }

        return $query->searchable(['order_number'])->orderBy('id', 'DESC')->with('order', 'orderDetail.product')->withSum('orderDetail as total_products', 'quantity')->paginate(getPaginate());
    }
}
