<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StockLog;
use App\Models\SubOrder;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function all()
    {
        $pageTitle      = "All Orders";
        $orders         = $this->getOrders();
        return view('admin.order.suborder.index', compact('pageTitle', 'orders'));
    }

    public function pending()
    {
        $pageTitle      = "Pending Orders";
        $orders         = $this->getOrders('pending');

        return view('admin.order.suborder.index', compact('pageTitle', 'orders'));
    }

    public function processing()
    {
        $pageTitle      = 'Processing Orders';
        $orders         = $this->getOrders('processing');

        return view('admin.order.suborder.index', compact('pageTitle', 'orders'));
    }

    public function pickedUp()
    {
        $pageTitle      = 'Picked Up Orders';
        $orders         = $this->getOrders('delivered');

        return view('admin.order.suborder.index', compact('pageTitle', 'orders'));
    }

    public function rejected()
    {
        $pageTitle     = "Rejected Orders";
        $orders        = $this->getOrders('rejected');

        return view('admin.order.suborder.index', compact('pageTitle', 'orders'));
    }

    public function detail($id)
    {
        $suborder  = SubOrder::admin()->valid()->with('order.user', 'order.shippingMethod', 'orderDetail.product')->findOrFail($id);
        $pageTitle = 'Order Details';
        return view('admin.order.suborder.details', compact('pageTitle', 'suborder'));
    }

    public function markAsProcessing($id)
    {
        $suborder = SubOrder::admin()->valid()->orderNotCanceled()->pending()->with('order.user')->findOrFail($id);
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

    public function markAsPickedUp($id)
    {
        $suborder = SubOrder::valid()->orderNotCanceled()->findOrFail($id);

        if($suborder->seller_id && $suborder->status != Status::SUBORDER_READY_TO_PICKUP){
            $notify[] =['error', 'Suborder is not ready for pickup'];
            return back()->withNotify($notify);
        }

        if(!$suborder->seller_id && $suborder->status != Status::SUBORDER_PROCESSING){
            $notify[] = ['error', 'Suborder should be in prcessing'];
            return back()->withNotify($notify);
        }

        $suborder->status = Status::SUBORDER_DELIVERED;
        $suborder->save();

        // check if all suborders has been delivered update the order to ready to deliver
        $hasUndelivered = SubOrder::where('order_id', $suborder->order_id)->whereNotIn('status', [Status::SUBORDER_DELIVERED, Status::SUBORDER_REJECTED])->count();

        if (!$hasUndelivered) {
            $order = $suborder->order;
            $order->status = Status::ORDER_READY_TO_DELIVER;
            $order->save();

            if ($order->user) {
                notify($order->user, 'ORDER_READY_FOR_DELIVER', [
                    'site_name' => gs('sitename'),
                    'order_id'  => $order->order_number
                ]);
            }
        }

        $notify[] = ['success', 'Order marked as picked up successfully'];
        return back()->withNotify($notify);
    }

    public function reject($id)
    {
        $suborder = SubOrder::admin()->valid()->orderNotCanceled()->pending()->with('orderDetail.product')->findOrFail($id);
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

        $notify[] = ['success', 'Order has been rejected successfully'];
        return back()->withNotify($notify);
    }

    private function getOrders($scope = null)
    {
        $query = SubOrder::admin()->valid()->orderNotCanceled();

        if ($scope) {
            $query->$scope();
        }

        return $query->orderBy('id', 'DESC')->with('order', 'orderDetail.product')->withSum('orderDetail as total_products', 'quantity')->paginate(getPaginate());
    }
}
