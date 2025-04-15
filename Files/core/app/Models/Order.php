<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];


    public function appliedCoupon()
    {
        return $this->hasOne(AppliedCoupon::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class, 'order_id', 'id')->latest()->withDefault();
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderDetail::class, 'order_id', 'id');
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function orderDetail()
    {
        return $this->hasManyThrough(OrderDetail::class, SubOrder::class)->where('sub_orders.status', '!=', Status::SUBORDER_REJECTED);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function getAmountAttribute()
    {
        return $this->total_amount - $this->shipping_charge;
    }


    public function scopePending($query)
    {
        return $query->where('status', Status::ORDER_PENDING)->whereIn('payment_status', [Status::PAYMENT_SUCCESS, Status::COD]);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', Status::PAYMENT_INITIATE);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', Status::PAYMENT_SUCCESS);
    }

    public function scopeCod($query)
    {
        return $query->where('payment_status', Status::COD);
    }

    public function scopeValid($query)
    {
        return $query->where('payment_status', '!=', Status::PAYMENT_INITIATE);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', Status::ORDER_PROCESSING);
    }

    public function scopeDispatched($query)
    {
        return $query->where('status', Status::ORDER_DISPATCHED);
    }

    public function scopeReadyToDeliver($query)
    {
        return $query->where('status', Status::ORDER_READY_TO_DELIVER);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', Status::ORDER_DELIVERED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', Status::ORDER_CANCELED);
    }

    public function statusBadge($addtionalClass = '')
    {
        if ($this->status == Status::ORDER_PENDING) {
            return makeHtmlElement('span', 'warning', 'Pending', $addtionalClass);
        } elseif ($this->status == Status::ORDER_PROCESSING) {
            return makeHtmlElement('span', 'primary', 'Processing', $addtionalClass);
        } elseif ($this->status == Status::ORDER_DISPATCHED) {
            return makeHtmlElement('span', 'dark', 'Dispatched', $addtionalClass);
            return makeHtmlElement('span', 'primary', 'Processing', $addtionalClass);
        } elseif ($this->status == Status::ORDER_READY_TO_DELIVER) {
            return makeHtmlElement('span', 'dark', 'Ready to Deliver', $addtionalClass);
        } elseif ($this->status == Status::ORDER_DELIVERED) {
            return makeHtmlElement('span', 'success', 'Delivered', $addtionalClass);
        } else {
            return makeHtmlElement('span', 'danger', 'Cancelled', $addtionalClass);
        }
    }

    public function paymentBadge($addtionalClass = '')
    {
        if ($this->payment_status == Status::PAYMENT_SUCCESS) {
            return makeHtmlElement('span', 'success', 'Paid', $addtionalClass);
        } elseif ($this->payment_status == Status::COD) {
            return makeHtmlElement('span', 'warning', 'COD', $addtionalClass);
        } else {
            return makeHtmlElement('span', 'danger', 'Unpaid', $addtionalClass);
        }
    }

    public function autoCancel()
    {
        $this->status = Status::ORDER_CANCELED;
        $this->save();

        if ($this->user) {
            notify($this->user, 'ORDER_CANCELLATION_CONFIRMATION', [
                'site_name' => gs('sitename'),
                'order_id'  => $this->order_number
            ]);
        }
    }
}
