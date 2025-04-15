<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    public function remark()
    {
        if ($this->type == 1) {
            return 'Updated By admin';
        } elseif ($this->type == 3) {
            return 'Updated by seller';
        } elseif ($this->type == 4) {
            return 'Adjustment';
        } else {
            return 'Sold';
        }
    }

    public static function updateStock($carts)
    {
        foreach ($carts as $cart) {
            $attr   = $cart->attributes ? @$cart->attributes['attributes'] : null;
            if ($cart->product->track_inventory) {
                $stock  = ProductStock::where('product_id', $cart->product_id)->where('attributes', $attr)->first();
                if ($stock) {
                    $stock->quantity   -= $cart->quantity;
                    $stock->save();

                    $log = new StockLog();
                    $log->stock_id  = $stock->id;
                    $log->quantity  = -$cart->quantity;
                    $log->type      = 2;
                    $log->save();
                }
            }
        }
    }

    public static function restoreStock($id, $isSubOrder = false)
    {
        if ($isSubOrder) {
            $order = SubOrder::where('id', $id)->with('orderDetail')->first();
        } else {
            $order = Order::where('id', $id)->with('orderDetail')->first();
        }

        if ($order && $order->orderDetail->count()) {
            foreach ($order->orderDetail as $orderDetail) {
                $productStock = ProductStock::where('product_id', $orderDetail->product_id)->where('attributes', $orderDetail->product_attributes)->first();
                if ($productStock) {
                    $productStock->quantity   += $orderDetail->quantity;
                    $productStock->save();

                    $stockLog = new StockLog();
                    $stockLog->stock_id = $productStock->id;
                    $stockLog->quantity = $orderDetail->quantity;
                    $stockLog->type = 4;
                    $stockLog->save();
                }
            }
        }
    }
}
