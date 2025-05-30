<div class="row product-details-wrapper">
    <div class="col-lg-5 variant-images">
        <div class="sync1 owl-carousel owl-theme">
            @if ($product->productImages->count() == 0)
                <div class="thumbs">
                    <img src="{{ getImage(getFilePath('product') . '/' . @$product->main_image, getFileSize('product')) }}" alt="@lang('products-details')">
                </div>
            @else
                @foreach ($product->productImages as $item)
                    <div class="thumbs">
                        <img src="{{ getImage(getFilePath('product') . '/' . @$item->image, getFileSize('product')) }}" alt="@lang('products-details')">
                    </div>
                @endforeach
            @endif
        </div>

        <div class="sync2 owl-carousel owl-theme mt-2">
            @if ($product->productImages->count() > 1)
                @foreach ($product->productImages as $item)
                    <div class="thumbs">
                        <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->image, getFileSize('product')) }}" alt="@lang('products-details')">
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="col-lg-7">
        <div class="product-details-content product-details">
            <h4 class="title mt-3">{{ __($product->name) }}</h4>

            @if ($product->show_in_frontend && $product->track_inventory)
                @php $quantity = $product->stocks->sum('quantity'); @endphp
                <div data-stocks="{{ $product->stocks }}" class="badge badge--{{ $quantity > 0 ? 'success' : 'danger' }} stock-status">
                    @lang('In Stock') (<span class="stock-qty">{{ $quantity }}</span>)
                </div>
            @endif

            <div class="ratings-area justify-content-start">
                <div class="ratings">
                    @php echo displayAvgRating($product->reviews) @endphp
                </div>
                <span class="ml-2 mr-auto">({{ __($product->reviews->count()) }})</span>
            </div>

            <div class="price">
                @if ($discount > 0)
                    {{ gs('cur_sym') }}<span class="special_price">{{ getAmount($product->base_price - $discount) }}</span>
                    <del>{{ gs('cur_sym') }}</del><del class="price-data">{{ getAmount($product->base_price) }}</del>
                @else
                    {{ gs('cur_sym') }}<span class="price-data">{{ getAmount($product->base_price) }}</span>
                @endif
            </div>

            <p>
                @php echo __($product->summary) @endphp
            </p>

            @forelse ($attributes as $attr)
                @php $attr_data = \App\Models\AssignProductAttribute::productAttributes($product->id, $attr->product_attribute_id); @endphp
                @if ($attr->productAttribute->type == 1)
                    <div class="product-size-area attr-area">
                        <span class="caption">{{ $attr->productAttribute->name_for_user }}</span>
                        @foreach ($attr_data as $data)
                            <div class="product-single-size attribute-btn" data-type="1" data-ti="{{ $product->track_inventory }}" data-discount={{ $discount }} data-attr_count="{{ $attributes->count() }}" data-id="{{ $data->id }}" data-product_id="{{ $product->id }}" data-price="{{ $data->extra_price }}" data-base_price="{{ getAmount($product->base_price) }}">{{ $data->value }}</div>
                        @endforeach
                    </div>
                @endif
                @if ($attr->productAttribute->type == 2)
                    <div class="product-color-area attr-area">
                        <span class="caption">{{ $attr->productAttribute->name_for_user }}</span>
                        @foreach ($attr_data as $data)
                            <div class="product-single-color attribute-btn" data-type="2" data-ti="{{ $product->track_inventory }}" data-discount={{ $discount }} data-attr_count="{{ $attributes->count() }}" data-id="{{ $data->id }}" data-product_id="{{ $product->id }}" data-bg="{{ $data->value }}" data-price="{{ $data->extra_price }}" data-base_price="{{ getAmount($product->base_price) }}"></div>
                        @endforeach
                    </div>
                @endif
                @if ($attr->productAttribute->type == 3)
                    <div class="product-color-area attr-area">
                        <span class="caption">{{ $attr->productAttribute->name_for_user }}</span>
                        @foreach ($attr_data as $data)
                            <div class="product-single-color attribute-btn bg_img" data-type="3" data-ti="{{ $product->track_inventory }}" data-discount={{ $discount }} data-attr_count="{{ $attributes->count() }}" data-id="{{ $data->id }}" data-product_id="{{ $product->id }}" data-price="{{ $data->extra_price }}" data-base_price="{{ getAmount($product->base_price) }}" data-background="{{ getImage(getFilePath('attribute') . '/' . @$data->value, getFileSize('attribute')) }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach

            <div class="cart-and-coupon mt-3">
                <div class="attr-data">
                </div>
                <div class="cart-plus-minus quantity">
                    <div class="cart-decrease qtybutton dec">
                        <i class="las la-minus"></i>
                    </div>
                    <input type="number" name="quantity" step="1" min="1" value="1" class="integer-validation">
                    <div class="cart-increase qtybutton inc">
                        <i class="las la-plus"></i>
                    </div>
                </div>

                <div class="add-cart">
                    <button type="button" class="qv-btn cart-add-btn" data-id="{{ $product->id }}">@lang('Add To Cart')</button>
                </div>
            </div>
            <div>
                <p class="acc">
                    <b>
                        @lang('Categories'):
                    </b>
                    @forelse ($product->categories as $category)
                        <a href="{{ route('products.category', ['id' => $category->id, 'slug' => slug($category->name)]) }}">{{ __($category->name) }}</a>
                        @if (!$loop->last)
                            /
                        @endif
                    @empty
                        @lang('N/A')
                    @endforelse
                </p>
                <p>
                    <b>@lang('Model'):</b> {{ __($product->model) }}
                </p>
                <p>
                    <b>@lang('Brand'):</b> {{ __($product->brand->name ?? 'N/A') }}
                </p>

                <p>
                    <b>@lang('SKU'):</b> <span class="product-sku">{{ $product->sku ?? __('Not Available') }}</span>
                </p>

                @if ($product->meta_keywords)
                    <p>
                        <b>
                            @lang('Tags'):
                        </b>
                        @foreach ($product->meta_keywords as $tag)
                            <a href="">{{ __($tag) }}</a>
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </p>
                @endif
                <div class="add-cart">
                    <a href="{{ route('product.detail', $product->slug) }}" class="qv-btn">@lang('View More')</a>
                </div>
            </div>
        </div>
    </div>
</div>
