<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $guarded  = ['id'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class, 'stock_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public static function showAvailableStock($productId, $attribute){
        return self::where('product_id', $productId)->where('attributes', $attribute)->first();
    }

    public static function getStockData($productId, $attribute){
        $a = self::where('product_id', $productId)->where('attributes', $attribute)->first();
        if ($a) {
            return $a;
        }
        return false;
    }
}
