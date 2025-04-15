<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\BelongsToSeller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, BelongsToSeller;

    protected $guarded = ['id'];

    protected $casts = [
        'extra_descriptions' => 'array',
        'specification' => 'array',
        'meta_keywords' => 'array'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_categories', 'product_id', 'category_id');
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offers_products', 'product_id', 'offer_id');
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupons_products', 'product_id', 'coupon_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function offer()
    {
        return $this->hasOne(OffersProduct::class, 'product_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function assignAttributes()
    {
        return $this->hasMany(AssignProductAttribute::class, 'product_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function userReview()
    {
        return $this->hasOne(ProductReview::class, 'product_id')->where('user_id', auth()->id());
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productPreviewImages()
    {
        return $this->hasMany(ProductImage::class)->where('assign_product_attribute_id', 0);
    }

    public function productVariantImages()
    {
        return $this->hasMany(ProductImage::class)->where('assign_product_attribute_id', '!=', 0);
    }

    public function scopeSellers($query)
    {
        return $query->where('seller_id', auth()->guard('seller')->user()->id);
    }
    public function scopeActive($query)
    {
        return $query->where('status', Status::ENABLE);
    }
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::YES);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::DISABLE);
    }

    public function scopePublishable($query)
    {
        return $query->active()->where(function ($q2) {
            $q2->where('has_variants', Status::NO)->orWhereHas('assignAttributes');
        });
    }


    public static function topSales($limit = 6)
    {
        return self::leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('sub_orders', 'order_details.sub_order_id', '=', 'sub_orders.id')
            ->leftJoin('orders', 'sub_orders.order_id', '=', 'orders.id')
            ->selectRaw('products.*, COALESCE(sum(order_details.quantity),0) total')
            ->where('orders.payment_status', '!=', '0')
            ->groupBy('products.id')
            ->with('reviews')
            ->orderBy('total', 'desc')
            ->limit($limit)
            ->get();
    }
}
