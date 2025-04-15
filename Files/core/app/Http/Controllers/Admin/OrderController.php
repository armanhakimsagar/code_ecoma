<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\Seller;
use App\Models\SellLog;
use App\Models\StockLog;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function allOrders($userId = null)
    {
        $pageTitle     = "All Orders";
        $orders        = $this->orderData(userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function codOrders($userId = null)
    {
        $pageTitle     = "Cash On Delivery Orders";
        $orders        = $this->orderData('cod', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function pending($userId = null)
    {
        $pageTitle     = "Pending Orders";
        $orders        = $this->orderData('pending', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function processing($userId = null)
    {
        $pageTitle     = "Orders on Processing";
        $orders        = $this->orderData('processing', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function readyToPickup()
    {
        $pageTitle = 'Ready to Pickup Sub Orders';

        $subOrders = SubOrder::ReadyToPickup()->searchable(['order_number', 'order:order_number', 'orderDetail'])->with('order.user')->orderBy('order_id', 'DESC')->paginate(getPaginate());
        return view('admin.order.suborder.ready_to_pickup', compact('pageTitle', 'subOrders'));
    }

    public function readyToDeliver($userId = null)
    {
        $pageTitle = 'Ready to Deliver Orders';
        $orders  = $this->orderData('readyToDeliver', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function dispatched($userId = null)
    {
        $pageTitle     = "Orders Dispatched";
        $orders        = $this->orderData('dispatched', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function canceledOrders($userId = null)
    {
        $pageTitle     = "Canceled Orders";
        $orders        = $this->orderData('canceled', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function deliveredOrders($userId = null)
    {
        $pageTitle     = "Delivered Orders";
        $orders        = $this->orderData('delivered', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    private function orderData($scope = null, $userId = null)
    {
        $query = Order::valid();

        if ($scope) {
            $query->$scope();
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if (request()->search) {
            $query->searchable(['order_number']);
        }

        if (request()->self_order) {
            $query->whereHas('orderDetail', function ($query2) {
                $query2->where('seller_id', 0);
            });
        }

        return $query->with(['user', 'deposit', 'deposit.gateway', 'subOrders'])->orderBy('id', 'DESC')->paginate(getPaginate());
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            'action' => ['required', Rule::in([Status::ORDER_PENDING, Status::ORDER_DELIVERED, Status::ORDER_PROCESSING, Status::ORDER_DISPATCHED,  Status::ORDER_CANCELED])],
        ]);

        $order = Order::with('subOrders', 'orderDetail.product')->findOrFail($request->id);

        // if all suborders are rejected then admin can't retake the order
        if ($request->action == Status::ORDER_PENDING && !$order->subOrders->where('status', '!=', Status::SUBORDER_REJECTED)->count()) {
            $notify[] = ['error', 'You can\'t retake this order'];
            return back()->withNotify($notify);
        }

        if ($order->status == Status::ORDER_DELIVERED) {
            $notify[] = ['error', 'This order has already been delivered'];
            return back()->withNotify($notify);
        }

        if (in_array($request->action, [Status::ORDER_DELIVERED, Status::ORDER_DISPATCHED])) {
            if ($order->subOrders->whereIn('status', [Status::SUBORDER_PENDING, Status::SUBORDER_PROCESSING, Status::SUBORDER_READY_TO_PICKUP])->count()) {
                $notify[] = ['error', 'There exist incomplete sub orders'];
                return back()->withNotify($notify);
            }

            if (!$order->subOrders->where('status', Status::SUBORDER_DELIVERED)->count()) {
                $notify[] = ['error', 'No sub orders exist'];
                return back()->withNotify($notify);
            }
        }

        $order->status = $request->action;

        if ($request->action == Status::ORDER_PROCESSING) {
            $action = 'Processing';
        } elseif ($request->action == Status::ORDER_DISPATCHED) {
            $action = 'Dispatched';
        } elseif ($request->action == Status::ORDER_DELIVERED) {
            $action = 'Delivered';

            if ($order->payment_status != Status::PAYMENT_SUCCESS) {
                $order->deposit->status = Status::PAYMENT_SUCCESS;
                $order->deposit->save();
            }

            foreach ($order->orderDetail as $detail) {
                $commission  = ($detail->total_price * gs('product_commission')) / 100;
                $finalAmount = $detail->total_price - $commission;

                $detail->product->sold += $detail->quantity;
                $detail->product->save();

                if ($detail->seller_id != 0) {
                    $seller = Seller::findOrFail($detail->seller_id);
                    $seller->balance += $finalAmount;
                    $seller->save();
                }

                $sellLog = new SellLog();
                $sellLog->seller_id       = $detail->seller_id;
                $sellLog->product_id      = $detail->product_id;
                $sellLog->order_id        = $order->order_number;
                $sellLog->qty             = $detail->quantity;
                $sellLog->product_price   = $detail->total_price;
                $sellLog->product_commission = gs('product_commission');
                $sellLog->after_commission = $detail->seller_id == 0 ? 0 : $finalAmount;
                $sellLog->save();
            }
        } elseif ($request->action == Status::ORDER_CANCELED) {
            $action = 'Cancelled';
            // update order stock
            StockLog::restoreStock($order->id);
        } elseif ($request->action == Status::ORDER_PENDING) {
            $action = 'Pending';

            // check order stock and then update order stock for retake order again
            foreach ($order->orderDetail as $key => $orderDetail) {
                $product = $orderDetail->product;

                if ($product->track_inventory) {
                    $stock = ProductStock::where('product_id', $orderDetail->product_id)->where('attributes', $orderDetail->product_attributes)->first();
                    if ($stock) {
                        if ($stock->quantity < $orderDetail->quantity) {
                            $notify[] = ['error', 'Some products in this order are out of stock'];
                            return back()->withNotify($notify);
                        }

                        $stock->quantity   -= $orderDetail->quantity;
                        $stock->save();

                        $log = new StockLog();
                        $log->stock_id  = $stock->id;
                        $log->quantity  = -$orderDetail->quantity;
                        $log->type      = 2;
                        $log->save();
                    }
                }
            }
        }

        $notify[] = ['success', 'Order status changed to ' . $action];
        $order->save();

        $shortCodes = [
            'site_name' => gs('sitename'),
            'order_id'  => $order->order_number
        ];

        if ($request->action == Status::ORDER_PROCESSING) {
            $act = 'ORDER_ON_PROCESSING_CONFIRMATION';
        } elseif ($request->action == Status::ORDER_DISPATCHED) {
            $act = 'ORDER_DISPATCHED_CONFIRMATION';
        } elseif ($request->action == Status::ORDER_DELIVERED) {
            $act = 'ORDER_DELIVERY_CONFIRMATION';
        } elseif ($request->action == Status::ORDER_CANCELED) {
            $act = 'ORDER_CANCELLATION_CONFIRMATION';
        } elseif ($request->action == Status::ORDER_PENDING) {
            $act = 'ORDER_RETAKE_CONFIRMATION';
        }

        notify($order->user, $act, $shortCodes);
        return back()->withNotify($notify);
    }

    public function orderDetails($id)
    {
        $order = Order::with('user', 'subOrders.seller', 'subOrders.orderDetail.product')->findOrFail($id);
        $pageTitle = 'Order Details';
        return view('admin.order.order_details', compact('order', 'pageTitle'));
    }

    public function invoice($id)
    {
        $order = Order::with('user', 'deposit', 'deposit.gateway', 'orderDetail', 'appliedCoupon')->findOrFail($id);
        $pageTitle = 'Invoice of #' . $order->order_number;
        return view('admin.order.invoice', compact('order', 'pageTitle'));
    }

    public function printInvoice($id)
    {
        $order = Order::with('user', 'deposit', 'deposit.gateway', 'orderDetail', 'appliedCoupon')->findOrFail($id);
        $pageTitle = 'Print Invoice';
        return view('admin.order.print_invoice', compact('pageTitle', 'order'));
    }

    public function adminSellsLog()
    {
        $pageTitle     = "My Sales";
        $logs          = SellLog::searchable(['order_id'])->where('seller_id', 0)->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.order.sell_log', compact('pageTitle', 'logs'));
    }

    public function sellerSellsLog()
    {
        $pageTitle     = "Seller Sales Log";
        $logs          = SellLog::searchable(['order_id'])->where('seller_id', '!=', 0)->orderBy('id', 'DESC')->paginate(getPaginate());

        return view('admin.order.sell_log', compact('pageTitle', 'logs'));
    }

    public function refundOrder(Request $request, $id)
    {
        $request->validate([
            'is_refunded' => 'required|in:1'
        ]);

        $order = Order::where('payment_status', Status::PAYMENT_SUCCESS)->where('status', Status::ORDER_CANCELED)->findOrFail($id);
        $order->is_refunded = Status::YES;
        $order->save();

        $notify[] = ['success', 'Refund status updated successfully'];
        return back()->withNotify($notify);
    }

    public function refundSubOrder(Request $request, $id)
    {
        $request->validate([
            'is_refunded' => 'required|in:1'
        ]);

        $suborder = SubOrder::orderNotCanceled()->with('order')->findOrfail($id);
        if ($suborder->order->payment_status != Status::PAYMENT_SUCCESS) {
            $notify[] = ['error', 'Invalid Order'];
            return back()->withNotify($notify);
        }

        $suborder->is_refunded = Status::YES;
        $suborder->save();

        $notify[] = ['success', 'Refund status updated successfully'];
        return back()->withNotify($notify);
    }
}
