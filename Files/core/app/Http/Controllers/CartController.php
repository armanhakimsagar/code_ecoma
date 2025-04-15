<?php

namespace App\Http\Controllers;

use App\Models\AssignProductAttribute;
use App\Models\Cart;
use App\Models\ShippingMethod;
use App\Models\Product;
use App\Models\ProductStock;
use App\Traits\CartManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use CartManager;

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity'  => 'required|numeric|gt:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $product = Product::findOrFail($request->product_id);
        $userid = auth()->id() ?? null;

        $attributes  = AssignProductAttribute::where('product_id', $request->product_id)->distinct('product_attribute_id')->with('productAttribute')->get(['product_attribute_id']);

        if ($attributes->count() > 0) {
            $count = $attributes->count();
            $validator = Validator::make($request->all(), [
                'attributes' => "required|array|min:$count"
            ], [
                'attributes.required' => 'Product variants must be selected',
                'attributes.min' => 'All product variants must be selected'
            ]);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $selectedAttributes = [];

        $sessionId = session()->get('session_id');

        if ($sessionId == null) {
            session()->put('session_id', uniqid());
            $sessionId = session()->get('session_id');
        }

        $selectedAttributes = $request['attributes'] ?? null;

        if ($selectedAttributes != null) {
            sort($selectedAttributes);
            $selectedAttributes = (json_encode($selectedAttributes));
        }

        if ($userid != null) {
            $cart = Cart::where('user_id', $userid)->where('product_id', $request->product_id)->where('attributes', $selectedAttributes)->first();
        } else {
            $cart = Cart::where('session_id', $sessionId)->where('product_id', $request->product_id)->where('attributes', $selectedAttributes)->first();
        }

        //Check Stock Status
        if ($product->track_inventory) {
            $stock = ProductStock::showAvailableStock($request->product_id, $selectedAttributes);

            $stock_qty = $stock->quantity ?? 0;
            if ($request->quantity > $stock_qty) {
                return response()->json(['error' => 'Quantity exceeded availability']);
            }
        }

        if ($cart) {
            $cart->quantity  += $request->quantity;
            if (isset($stock_qty) && $cart->quantity > $stock_qty) {
                return response()->json(['error' => 'Sorry, You have already added maximum amount of stock']);
            }

            $cart->save();
        } else {
            $cart = new Cart();
            $cart->user_id    = auth()->id() ?? null;
            $cart->seller_id  = $product->seller_id;
            $cart->session_id = $sessionId;
            $cart->attributes = json_decode($selectedAttributes);
            $cart->product_id = $request->product_id;
            $cart->quantity   = $request->quantity;
            $cart->save();
        }

        return response()->json(['success' => 'Added to Cart']);
    }

    public function getCart()
    {
        $carts = $this->getItems();
        $subtotal = $this->getCartSubTotal($carts);

        $latest        = $carts->take(3);
        $more          = $carts->count() - count($latest);
        $emptyMessage  = 'No product in your cart';
        $coupon        = session('coupon');

        return view('Template::partials.cart_items', ['data' => $latest, 'subtotal' => $subtotal, 'emptyMessage' => $emptyMessage, 'more' => $more, 'coupon' => $coupon]);
    }

    public function getCartTotal()
    {
        $carts = $this->getItems();
        return $carts->count();
    }

    public function shoppingCart()
    {
        $pageTitle     = 'My Cart';
        $data = $this->getItems();
        $emptyMessage  = 'Cart is empty';
        return view('Template::cart', compact('pageTitle', 'data', 'emptyMessage'));
    }

    public function updateCartItem(Request $request, $id)
    {
        if (session()->has('coupon')) {
            return response()->json(['error' => 'You have applied a coupon on your cart. If you want to delete any item form your cart please remove the coupon first.']);
        }

        $cart_item = Cart::findorFail($id);

        $attributes = $cart_item->attributes ?? null;
        if ($attributes !== null) {
            sort($attributes);
            $attributes = json_encode($attributes);
        }
        if ($cart_item->product->show_in_frontend && $cart_item->product->track_inventory) {
            $stock_qty  = ProductStock::showAvailableStock($cart_item->product_id, $attributes);

            if ($request->quantity > $stock_qty) {
                return response()->json(['error' => 'Sorry! your requested amount of quantity is not available in our stock', 'qty' => $stock_qty]);
            }
        }

        if ($request->quantity == 0) {
            return response()->json(['error' => 'Quantity must be greater than  0']);
        }
        $cart_item->quantity = $request->quantity;
        $cart_item->save();
        return response()->json(['success' => 'Quantity updated']);
    }

    public function removeCartItem($id)
    {

        if (session()->has('coupon')) {
            return response()->json(['error' => 'You have applied a coupon on your cart. If you want to delete any item form your cart please remove the coupon first.']);
        }

        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['error' => 'Item not found']);
        }

        $cart->delete();
        return response()->json(['success' => 'Item deleted successfully']);
    }

    public function checkout()
    {
        $userid    = auth()->id() ?? null;

        if ($userid) {
            $data = Cart::where('user_id', $userid)->get();
        } else {
            $data = Cart::where('session_id', session('session_id'))->get();
        }
        if ($data->count() == 0) {
            $notify[] = ['success', 'No product in your cart'];
            return back()->withNotify($notify);
        }
        $shippingMethods = ShippingMethod::active()->orderBy('name')->get();
        $pageTitle = 'Checkout';
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::checkout', compact('pageTitle', 'shippingMethods', 'countries'));
    }

    public function getCartShippingCharge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_method_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $shippingMethod = ShippingMethod::active()->where('id', $request->shipping_method_id)->first();

        if (!$shippingMethod) {
            return response()->json([
                'status' => false,
                'message' => 'Shipping method not found'
            ]);
        }

        return response()->json([
            'status' => true,
            'charge' => $shippingMethod->charge
        ]);
    }
}
