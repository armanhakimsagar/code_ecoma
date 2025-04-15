<?php

namespace App\Traits;

use App\Models\AssignProductAttribute;
use App\Models\Cart;

trait CartManager
{

    public function getItems()
    {
        return Cart::where('user_id', auth()->id())->orWhere('session_id', session('sesison_id'))->with(['product' => function ($q) {
            return $q->active();
        }, 'product.categories'])->get();
    }

    public function getCartSubTotal($carts = null)
    {
        $carts = $carts ?? $this->getItems();

        $subtotal = 0;
        foreach ($carts as $cart) {
            if ($cart->attributes != null) {
                $price = AssignProductAttribute::priceAfterAttribute($cart->product, $cart->attributes);
            } else {
                if ($cart->product->offer && $cart->product->offer->activeOffer) {
                    $price = $cart->product->base_price - calculateDiscount($cart->product->offer->activeOffer->amount, $cart->product->offer->activeOffer->discount_type, $cart->product->base_price);
                } else {
                    $price = $cart->product->base_price;
                }
            }
            $subtotal += $price * $cart->quantity;
        }

        return $subtotal;
    }
}
