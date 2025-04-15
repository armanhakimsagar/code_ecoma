<?php

namespace App\Models;

use App\Traits\BelongsToSeller;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use BelongsToSeller;

    public function subOrder(){
        return $this->belongsTo(SubOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
