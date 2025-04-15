@foreach ($products as $product)
    @if ($class)
        <div class="{{ $class }}">
    @endif
    @php
        if ($product->offer && $product->offer->activeOffer) {
            $discount = calculateDiscount($product->offer->activeOffer->amount, $product->offer->activeOffer->discount_type, $product->base_price);
        } else {
            $discount = 0;
        }
        $wCk = checkWishList($product->id);
        $cCk = checkCompareList($product->id);
    @endphp
    <div class="product-item-2 {{ @$noMargin ? 'm-0' : '' }}">
        <div class="product-item-2-inner wish-buttons-in">
            <ul class="wish-react">
                <li>
                    <a href="javascript:void(0)" title="@lang('Add To Wishlist')" class="add-to-wish-list {{ $wCk ? 'active' : '' }}" data-id="{{ $product->id }}"><i class="lar la-heart"></i></a>
                </li>
                <li>

                    <a href="javascript:void(0)" title=" @lang('Compare')" class="add-to-compare {{ $cCk ? 'active' : '' }}" data-id="{{ $product->id }}"><i class="las la-sync-alt"></i></a>
                </li>
            </ul>
            <div class="product-thumb">
                <a href="{{ route('product.detail', $product->slug) }}">
                    <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$product->main_image, getFileSize('product')) }}" alt="@lang('flash')">
                </a>
            </div>
            <div class="product-content">
                <div class="product-before-content">
                    <h6 class="title">
                        <a href="{{ route('product.detail', $product->slug) }}">{{ __($product->name) }}</a>
                    </h6>
                    <div class="ratings-area justify-content-between">
                        <div class="d-flex gap-1">
                            <div class="ratings">
                                @php echo displayAvgRating($product->reviews) @endphp
                            </div>
                            <span>({{ $product->reviews->count() }})</span>
                        </div>

                        <div class="price">
                            @if ($discount > 0)
                                {{ gs('cur_sym') }}{{ getAmount($product->base_price - $discount, 2) }}
                                <del>{{ getAmount($product->base_price, 2) }}</del>
                            @else
                                {{ gs('cur_sym') }}{{ getAmount($product->base_price, 2) }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="product-after-content">
                    <button data-product="{{ $product->id }}" class="cmn-btn btn-sm quick-view-btn">
                        @lang('View')
                    </button>
                    <div class="price">
                        @if ($discount > 0)
                            {{ gs('cur_sym') }}{{ $product->base_price - $discount }}
                            <del>{{ getAmount($product->base_price, 2) }}</del>
                        @else
                            {{ gs('cur_sym') }}{{ getAmount($product->base_price, 2) }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($class)
        </div>
    @endif
@endforeach
