<h4 class="title">@lang('Your Wishlist')</h4>

@forelse ($data as $item)
    @php
        if ($item->product->offer && $item->product->offer->activeOffer) {
            $discount = calculateDiscount($item->product->offer->activeOffer->amount, $item->product->offer->activeOffer->discount_type, $item->product->base_price);
        } else {
            $discount = 0;
        }
    @endphp

    <div class="single-product-item">
        <div class="thumb">
            <img src="{{ getImage(getFilePath('product') . '/' . @$item->product->main_image, getFileSize('product')) }}" alt="@lang('shop')">
        </div>
        <a href="javascript:void(0)" class="remove-wishlist remove-item-button" data-page="0" data-id="{{ $item->id }}" data-pid="{{ $item->product->id }}"><i class="la la-times"></i></a>

        <div class="content">
            <h4 class="title"><a class="cl-white" href="{{ route('product.detail', $item->product->slug) }}">{{ strLimit(__($item->product->name), 35) }}</a></h4>
            <div class="price">
                @if ($discount > 0)
                    <span class="pprice">{{ gs('cur_sym') }}{{ getAmount($item->product->base_price - $discount) }}</span>
                    <del class="dprice">{{ getAmount($item->product->base_price) }}</del>
                @else
                    <span class="pprice">{{ gs('cur_sym') }}{{ getAmount($item->product->base_price) }}</span>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="single-product-item no_data justify-content-center">
        <div class="no_data-thumb w-50 mb-2 text-white">
            <i class="la la-heart-broken la-10x"></i>
        </div>
        <h6 class="cl-white">@lang('Products not found')</h6>
    </div>
@endforelse

@if ($data->count())
    <div class="btn-wrapper text-center">
        <a href="{{ route('wishlist') }}" class="qv-btn bg btn-block">
            @if ($more)
                @lang('And') {{ $more }} @lang('More')
            @else
                @lang('View Wishlist')
            @endif
        </a>
    </div>
@endif
