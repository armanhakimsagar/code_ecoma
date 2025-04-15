<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\BelongsToSeller;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
    use BelongsToSeller;

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function scopeAdmin($query)
    {
        return $query->where('seller_id', 0);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::SUBORDER_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', Status::SUBORDER_PROCESSING);
    }

    public function scopeReadyToPickup($query)
    {
        return $query->where('status', Status::SUBORDER_READY_TO_PICKUP);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', Status::SUBORDER_DELIVERED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Status::SUBORDER_REJECTED);
    }

    public function scopeValid($query)
    {
        return $query->whereHas('order', function ($q1) {
            $q1->where('payment_status', '!=', Status::PAYMENT_INITIATE);
        });
    }

    public function scopeOrderNotCanceled($query)
    {
        return $query->whereHas('order', function ($q1) {
            $q1->where('status', '!=', Status::ORDER_CANCELED);
        });
    }

    public function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $isAdminRoute = request()->routeIs('admin.*');

                return match ($attributes['status']) {
                    Status::SUBORDER_PENDING => '<span class="badge badge--warning">Pending</span>',
                    Status::SUBORDER_PROCESSING => '<span class="badge badge--dark">Processing</span>',
                    Status::SUBORDER_READY_TO_PICKUP => '<span class="badge badge--warning">Ready to Pickup</span>',
                    Status::SUBORDER_DELIVERED => '<span class="badge badge--success">' . ($isAdminRoute ? 'Picked Up' : 'Delivered') . '</span>',
                    Status::SUBORDER_REJECTED => '<span class="badge badge--danger">Rejected</span>',
                    default => '<span class="badge badge--secondary">Unknown</span>',
                };
            }
        );
    }
}
