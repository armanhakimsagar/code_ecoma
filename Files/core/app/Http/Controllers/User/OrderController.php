<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Models\Order;
use App\Models\AppliedCoupon;
use App\Models\Coupon;
use App\Models\Deposit;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\AssignProductAttribute;
use App\Models\Product;
use App\Models\ProductReview;
use App\Http\Controllers\Controller;
use App\Models\StockLog;
use App\Models\SubOrder;
use App\Traits\CartManager;

class OrderController extends Controller
{
    use CartManager;

    public function orders($type)
    {
        $pageTitle = ucfirst($type) . ' Orders';
        $emptyMessage = 'No order yet';
        $query = Order::valid()->where('user_id', auth()->id());

        if ($type == 'pending') {
            $query = $query->where('status', Status::ORDER_PENDING);
        } elseif ($type == 'processing') {
            $query = $query->where('status', Status::ORDER_PROCESSING);
        } elseif ($type == 'dispatched') {
            $query = $query->where('status', Status::ORDER_DISPATCHED);
        } elseif ($type == 'completed') {
            $query = $query->where('status', Status::ORDER_DELIVERED);
        } elseif ($type == 'canceled') {
            $query = $query->where('status', Status::ORDER_CANCELED);
        }

        $orders = $query->latest()->paginate(getPaginate());
        return view('Template::user.orders.index', compact('pageTitle', 'orders', 'emptyMessage', 'type'));
    }

    public function orderDetails($order_number)
    {
        $pageTitle = 'Order Details';
        $order = Order::where('order_number', $order_number)->where('user_id', auth()->id())->with('deposit', 'orderDetail', 'appliedCoupon')->first();

        return view('Template::user.orders.details', compact('order', 'pageTitle'));
    }

    public function confirmOrder(Request $request, $type)
    {
        $general = gs();
        /* Type 1 (Order for Customer) Type 2 (Order as Gift) */

        $request->validate([
            'shipping_method'   => 'required|integer',
            'firstname'         => 'required',
            'lastname'          => 'required',
            'mobile'            => 'required',
            'email'             => 'required|email',
            'address'           => 'required',
            'city'              => 'required',
            'state'             => 'required',
            'zip'               => 'required',
            'country'           => 'required',
            'payment'           => 'required|in:1,2'
        ]);

        if (!gs('cod') && $request->payment == Status::COD) {
            $notify[] = ['error', 'Cash on delivery is not available now'];
            return back()->withNotify($notify);
        }

        $cartData = $this->getItems();

        if ($cartData->isEmpty()) {
            $notify[] = ['error', 'Your cart is empty'];
            return back()->withNotify($notify);
        }

        $user = auth()->user();
        $couponAmount      = 0;
        $couponCode        = null;
        $cartTotal         = 0;
        $productCategories = [];

        foreach ($cartData as $cart) {
            $productCategories[] = $cart->product->categories->pluck('id')->toArray();

            if ($cart->product->offer && $cart->product->offer->activeOffer) {
                $offerAmount = calculateDiscount($cart->product->offer->activeOffer->amount, $cart->product->offer->activeOffer->discount_type, $cart->product->base_price);
            } else {
                $offerAmount = 0;
            }

            if ($cart->attributes != null) {
                $attr_item                   = AssignProductAttribute::productAttributesDetails($cart->attributes);
                $attr_item['offer_amount'] = $offerAmount;
                $subtotal                   = (($cart->product->base_price + $attr_item['extra_price']) - $offerAmount) * $cart->quantity;
                unset($attr_item['extra_price']);
            } else {
                $details['variants']        = null;
                $details['offer_amount']    = $offerAmount;
                $subtotal                  = ($cart->product->base_price  - $offerAmount) * $cart->quantity;
            }
            $cartTotal += $subtotal;
        }

        $productCategories = array_unique(array_merge(...$productCategories));

        if (session('coupon')) {
            $coupon = Coupon::running()->where('coupon_code', session('coupon')['code'])->with('categories')->first();

            // Check Minimum Subtotal
            if ($cartTotal < $coupon->minimum_spend) {
                return response()->json(['error' => "Sorry you have to order minimum amount of $coupon->minimum_spend $general->cur_text"]);
            }

            // Check Maximum Subtotal
            if ($coupon->maximum_spend != null && $cartTotal > $coupon->maximum_spend) {
                return response()->json(['error' => "Sorry you have to order maximum amount of $coupon->maximum_spend $general->cur_text"]);
            }

            //Check Limit Per Coupon
            if ($coupon->appliedCoupons->count() >= $coupon->usage_limit_per_coupon) {
                return response()->json(['error' => "Sorry your Coupon has exceeded the maximum usage limit"]);
            }

            //Check Limit Per User
            if ($coupon->appliedCoupons->where('user_id', $user->id)->count() >= $coupon->usage_limit_per_user) {
                return response()->json(['error' => "Sorry you have already reached the maximum usage limit for this coupon"]);
            }

            if ($coupon) {
                $couponCategories = $coupon->categories->pluck('id')->toArray();
                $couponProducts = $coupon->products->pluck('id')->toArray();
                $cartProducts = $cartData->pluck('product_id')->unique()->toArray();

                if (empty(array_intersect($couponProducts, $cartProducts))) {
                    if (empty(array_intersect($productCategories, $couponCategories))) {
                        $notify[] = ['error', 'The coupon is not available for some products on your cart'];
                        return back()->withNotify($notify);
                    }
                }

                if ($coupon->discount_type == 1) {
                    $couponAmount = $cartTotal > $coupon->coupon_amount ? $coupon->coupon_amount : $cartTotal;
                } else {
                    $couponAmount = ($cartTotal * $coupon->coupon_amount) / 100;
                }

                $couponCode = $coupon->coupon_code;
            }
        }

        $shippingMethod = ShippingMethod::find($request->shipping_method);
        $shippingAddress   = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'mobile'    => $request->mobile,
            'country'   => $request->country,
            'city'      => $request->city,
            'state'     => $request->state,
            'zip'       => $request->zip,
            'address'   => $request->address,
        ];

        $order                      = new Order();
        $order->order_number        = getTrx();
        $order->user_id             = $user->id;
        $order->shipping_address    = json_encode($shippingAddress);
        $order->shipping_method_id  = $request->shipping_method;
        $order->shipping_charge     = $shippingMethod->charge;
        $order->order_type          = $type;
        $order->payment_status      = $request->payment == Status::COD ? Status::COD : Status::PAYMENT_INITIATE;
        $order->save();

        foreach ($cartData->groupBy('seller_id') as $key => $sellerCarts) {
            $suborderTotal = 0;

            $suborder = new SubOrder();
            $suborder->order_id = $order->id;
            $suborder->seller_id = $key;
            $suborder->order_number = getTrx();
            $suborder->save();

            foreach ($sellerCarts as $cart) {
                $orderDetail                  = new OrderDetail();
                $orderDetail->sub_order_id    = $suborder->id;
                $orderDetail->product_id      = $cart->product_id;
                $orderDetail->quantity        = $cart->quantity;
                $orderDetail->base_price      = $cart->product->base_price;

                $offerAmount = 0;
                if ($cart->product->offer && $cart->product->offer->activeOffer) {
                    $offerAmount = calculateDiscount($cart->product->offer->activeOffer->amount, $cart->product->offer->activeOffer->discount_type, $cart->product->base_price);
                }

                if ($cart->attributes != null) {
                    $attr_item                            = AssignProductAttribute::productAttributesDetails($cart->attributes);
                    $attr_item['offer_amount']            = $offerAmount;
                    $subtotal                             = (($cart->product->base_price + $attr_item['extra_price']) - $offerAmount) * $cart->quantity;
                    $orderDetail->total_price             = $subtotal;
                    unset($attr_item['extra_price']);
                    $orderDetail->details                 = json_encode($attr_item);
                    $orderDetail->product_attributes      = json_encode($cart->attributes);
                } else {
                    $details['variants']        = null;
                    $details['offer_amount']    = $offerAmount;
                    $subtotal                   = ($cart->product->base_price  - $offerAmount) * $cart->quantity;
                    $orderDetail->total_price   = $subtotal;
                    $orderDetail->details       = json_encode($details);
                }

                $orderDetail->save();

                $suborderTotal += $subtotal;
            }

            $suborder->total_amount = $suborderTotal;
            $suborder->save();
        }

        $order->total_amount =  getAmount($cartTotal - $couponAmount + $order->shipping_charge);
        $order->save();

        session()->put('order_number', $order->order_number);

        if ($couponCode != null) {
            $appliedCoupon = new AppliedCoupon();
            $appliedCoupon->user_id    = $user->id;
            $appliedCoupon->coupon_id  = $coupon->id;
            $appliedCoupon->order_id   = $order->id;
            $appliedCoupon->amount     = $couponAmount;
            $appliedCoupon->save();

            session()->forget('coupon');
        }

        if ($request->payment == 1) {
            return to_route('user.deposit.index');
        } else {
            $deposit = Deposit::where('user_id', $user->id)->where('order_id', $order->id)->first();

            if (!$deposit) {
                $deposit = new Deposit();
                $deposit->user_id = $user->id;
            }

            $deposit->method_code        = 0;
            $deposit->order_id           = $order->id;
            $deposit->method_currency    = $general->cur_text;
            $deposit->amount             = $order->total_amount;
            $deposit->charge             = 0;
            $deposit->rate               = 0;
            $deposit->final_amount       = getAmount($order->total_amount);
            $deposit->btc_amount         = 0;
            $deposit->btc_wallet         = "";
            $deposit->trx                = getTrx();
            $deposit->status             = Status::PAYMENT_PENDING;
            $deposit->save();

            // here, update stock logs
            StockLog::updateStock($cartData);

            session()->forget('session_id');
            $cartData->each->delete();


            $notify[] = ['success', 'Your order has been placed successfully'];
            return to_route('user.home')->withNotify($notify);
        }
    }

    public function productsReview()
    {
        $productIds = OrderDetail::whereHas('subOrder', function ($subOrder) {
            $subOrder->whereHas('order', function ($order) {
                $order->where('user_id', auth()->id())->where('status', Status::ORDER_DELIVERED);
            });
        })->pluck('product_id')->toArray();
        $products  =  Product::whereIn('id', $productIds)->with('userReview')->paginate();

        $pageTitle = 'Review Products';
        return view('Template::user.orders.products_for_review', compact('pageTitle', 'products'));
    }

    public function addReview(Request $request)
    {
        $request->validate([
            'pid'       => 'required|string',
            'review'    => 'required|string',
            'rating'    => 'required|numeric',
        ]);

        $user = auth()->user();

        $product = Product::find($request->pid);
        if (!$product) {
            $notify[] = ['error', 'Product not found'];
            return back()->withNotify($notify);
        }

        // check user has purchased this product or not
        $checkOrder =  OrderDetail::whereHas('subOrder', function ($subOrder) {
            $subOrder->whereHas('order', function ($order) {
                $order->where('user_id', auth()->id())->where('status', Status::ORDER_DELIVERED);
            });
        })->where('product_id', $product->id)->exists();

        if (!$checkOrder) {
            $notify[] = ['error', 'You have to purchase this product before review'];
            return back()->withNotify($notify);
        }

        $alreadyReviewed = ProductReview::where('user_id', $user->id)->where('product_id', $request->pid)->exists();

        if ($alreadyReviewed) {
            $notify[] = ['error', 'You have already reviewed this product'];
            return back()->withNotify($notify);
        }

        $productReview = new ProductReview();
        $productReview->user_id = $user->id;
        $productReview->product_id = $request->pid;
        $productReview->review = $request->review;
        $productReview->rating = $request->rating;
        $productReview->save();

        $notify[] = ['success', 'Review added successfully'];
        return back()->withNotify($notify);
    }
}
