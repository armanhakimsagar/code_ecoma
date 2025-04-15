<?php
namespace App\Traits;

trait BelongsToSeller
{
    public function scopeBelongsToSeller($query, $relation = null){
        if($relation){
            return $query->whereHas($relation, function($q2){
                $q2->where('seller_id', seller()->id);
            });
        }

        return $query->where('seller_id', seller()->id);
    }
}
