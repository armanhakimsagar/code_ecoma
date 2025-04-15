<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\StockLog;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\AssignProductAttribute;

class ProductStockController extends Controller
{
    public function stockCreate($product_id)
    {
        $product = Product::where('seller_id', seller()->id)->find($product_id);

        if ($product->track_inventory == 0) {
            $notify[] = ['error', 'Inventory tracking is disabled for this product'];
            return back()->withNotify($notify);
        }
        $pageTitle             = 'Manage Inventory';

        $assigned_attributes    = AssignProductAttribute::where('product_id', $product_id)->with(['productAttribute'])->get()->groupBy('product_attribute_id');

        foreach ($assigned_attributes as $attributes) {
            foreach ($attributes as $attribute) {
                $attr_array[] =  $attribute->id . '-' . $attribute->productAttribute->name_for_user . '-' . $attribute->name;
            }
            $attr_data[] = $attr_array;
            unset($attr_array);
        }
        if (isset($attr_data)) {
            $combinations =  combinations($attr_data);
        } else {
            $combinations = [];
        }

        $data = [];

        foreach ($combinations as $key => $combination) {
            unset($attr_id);
            $result = '';
            $temp_result = [];
            foreach ($combination as $attribute) {
                $temp       = [];
                $exp        = explode('-', $attribute);
                $result    .= $exp[1] . ' : ' . $exp[2];
                $attr_id[]  = $exp[0];

                if (end($combination) != $attribute) {
                    $result .= ' - ';
                }

                $attr_val = json_encode($attr_id);
            }

            $stock                      = ProductStock::getStockData($product->id, $attr_val);
            $data[$key]['combination']  = $result;
            $data[$key]['attributes']   = $attr_val;
            $data[$key]['sku']          = $stock['sku'] ?? null;
            $data[$key]['quantity']     = $stock['quantity'] ?? 0;
            $data[$key]['stock_id']     = $stock['id'] ?? 0;
        }

        return view('seller.products.stock.create', compact('pageTitle', 'data', 'product'));
    }

    public function stockAdd(Request $request, $id)
    {
        $request->validate([
            'attr'          => 'sometimes|required|string',
            'quantity'      => 'required|numeric|min:0',
            'sku'           => 'sometimes|required|string|max:100',
            'type'          => 'required|numeric|between:1,2',
        ]);

        $seller = seller();

        $product = Product::where('seller_id', $seller->id)->findOrFail($id);

        $attributes = $request->attr == 'null' ? null : $request->attr;

        if ($attributes) {
            $attributes = json_decode($attributes);
            sort($attributes);
            $attributes = json_encode($attributes);
        }

        if ($request->type == 1) {
            $qty = $request->quantity;
        } else {
            $qty = -$request->quantity;
        }

        $stock = ProductStock::where('product_id', $product->id)->where('attributes', $attributes)->first();
        if ($stock) {
            //check sku in product table
            $checkSKU = Product::where('sku', $request->sku)->where('id', '!=', $id)->first();

            if ($checkSKU) {
                $notify[] = ['error', 'This SKU has already been taken'];
                return back()->withNotify($notify);
            } else {
                $checkSKU = ProductStock::where('product_id', '!=', $id)->where('attributes', '!=', $attributes)->where('sku', $request->sku)->first();
                if ($checkSKU) {
                    $notify[] = ['error', 'This SKU has already been taken'];
                    return back()->withNotify($notify);
                } else {
                    $stock->product_id = $id;
                    $stock->attributes = $attributes;
                    $stock->sku        = $request->sku;
                    $stock->quantity   += $qty;
                    $stock->save();
                }
            }
        } else {
            //check sku
            $checkSKU = Product::where('sku', $request->sku)->where('id', '!=', $id)->with('stocks')->orWhereHas('stocks', function ($q) use ($request) {
                $q->where('sku', $request->sku);
            })->first();

            if ($checkSKU) {
                $notify[] = ['error', 'This SKU has already been taken'];
                return back()->withNotify($notify);
            }

            $stock = new ProductStock();
            $stock->product_id = $id;
            $stock->attributes = $attributes;
            $stock->sku        = $request->sku;
            $stock->quantity   = $request->quantity;
            $stock->save();
        }

        if ($qty != 0) {
            $log = new StockLog;
            $log->stock_id  = $stock->id;
            $log->quantity  = $qty;
            $log->type      = 3;
            $log->save();
        }

        $notify[] = ['success', 'Product stock updated successfully'];
        return back()->withNotify($notify);
    }

    public function stockLog($id)
    {
        $productStock  = ProductStock::whereHas('product', function ($q) {
            $q->where('seller_id', seller()->id);
        })->find($id);

        if(!$productStock){
            $notify[] = ['error', 'No inventory created yet'];
            return back()->withNotify($notify);
        }

        $pageTitle     = "Stock logs for SKU:" . @$productStock->sku;
        $stockLogs = StockLog::where('stock_id', $productStock->id)->orderBy('id', 'desc')->paginate(getPaginate());

        return view('seller.products.stock.log', compact('pageTitle', 'productStock', 'stockLogs'));
    }
}
