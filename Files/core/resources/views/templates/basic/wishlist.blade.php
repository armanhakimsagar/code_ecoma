@extends('Template::layouts.frontend')

@section('content')
    <div class="cart-section padding-bottom padding-top">
        <div class="container">
            <table class="cart-table section-bg  mb-0">
                <thead>
                    <tr class="table-head">
                        <th scope="col">@lang('Product')</th>
                        <th scope="col">@lang('Availability')</th>
                        <th scope="col">@lang('Price')</th>
                        <th scope="col">@lang('Action')</th>
                    </tr>
                </thead>

                <tbody class="cart-table-body">
                    @forelse ($wishlist_data as $item)
                        @php
                            if ($item->activeOffer) {
                                $discount = calculateDiscount($item->product->activeOffer->amount, $item->product->activeOffer->discount_type, $item->product->base_price);
                            } else {
                                $discount = 0;
                            }

                            $price = $item->product->base_price - $discount;

                            $stock_qty = $item->product->stocks->sum('quantity');
                        @endphp
                        <tr>
                            <td data-label="@lang('Product')">
                                <a href="{{ route('product.detail', $item->product->slug) }}" class="cart-item mw-100">
                                    <div class="cart-img">
                                        <img src="{{ getImage(getFilePath('product') . '/' . @$item->product->main_image, getFileSize('product')) }}" alt="@lang('cart')">
                                    </div>
                                    <div class="cart-cont">
                                        <h6 class="title">{{ __($item->product->name) }}</h6>
                                    </div>
                                </a>
                            </td>

                            <td data-label="@lang('Availability')">
                                @if ($item->product->track_inventory)
                                    @if ($stock_qty > 0)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endif
                                @else
                                    <i class="fas fa-check text-success"></i>
                                @endif

                            </td>

                            <td data-label="@lang('Price')">
                                {{ gs('cur_sym') }}{{ getAmount($price, 2) }}
                            </td>

                            <td data-label="@lang('Action')">
                                <span class="edit remove-wishlist" data-id="{{ $item->id }}">
                                    <i class="las la-trash-alt"></i>
                                </span>

                                <a href="javascript:void(0)" data-product="{{ $item->product_id }}" data-id="{{ $item->id }}" class="quick-view-btn">
                                    <span class="edit add-cart">
                                        <i class="las la-cart-plus"></i>
                                    </span>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="100%">
                                {{ __($emptyMessage) }}
                            </td>
                        </tr>
                </tbody>
                @endforelse

            </table>

            <div class="cart-total section-bg rounded--5">

                <div class="d-flex flex-wrap align-items-center">
                    <div class="apply-coupon-code">
                        @if ($wishlist_data->count() > 1)
                            <a href="javascript:void(0)" class="btn btn-danger remove-all-btn" data-label="@lang('Are you sure to remove all product?')" data-id="0" data-bs-toggle="modal" data-bs-target="#deleteModal">@lang('Remove All')</a>
                        @endif
                    </div>

                    <div class="checkout ml-auto">
                        <a href="{{ route('home') }}" class="theme cmn--btn">@lang('Continue Shopping')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--section end-->
@endsection
