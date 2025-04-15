@extends($activeTemplate . 'layouts.frontend')

@section('content')

    <!-- Product Single Section Starts Here -->
    <div class="category-section padding-bottom-half padding-top oh">
        <div class="container">
            <div class="row product-details-wrapper">
                <div class="col-lg-5 variant-images">
                    <div class="sync1 owl-carousel owl-theme">
                        <div class="thumbs">
                            <img class="zoom_img" src="{{ getImage(getFilePath('product') . '/' . @$product->main_image, getFileSize('product')) }}" alt="@lang('products-details')">
                        </div>
                        @foreach ($images as $item)
                            <div class="thumbs">
                                <img class="zoom_img" src="{{ getImage(getFilePath('product') . '/' . @$item->image, getFileSize('product')) }}" alt="@lang('products-details')">
                            </div>
                        @endforeach
                    </div>

                    @if ($images->count())
                        <div class="sync2 owl-carousel owl-theme mt-2">
                            <div class="thumbs">
                                <img src="{{ getImage(getFilePath('product') . '/' . @$product->main_image, getFileSize('product')) }}" alt="@lang('products-details')">
                            </div>
                            @foreach ($images as $item)
                                <div class="thumbs">
                                    <img src="{{ getImage(getFilePath('product') . '/' . @$item->image, getFileSize('product')) }}" alt="@lang('products-details')">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-lg-7">
                    <div class="product-details-content product-details">
                        <h4 class="title">{{ __($product->name) }}</h4>

                        <div class="ratings-area justify-content-start">
                            <div class="ratings">
                                @php echo displayAvgRating($product->reviews) @endphp
                            </div>
                            <span class="ml-2 mr-auto">({{ __($product->reviews->count()) }})</span>
                        </div>
                        @if ($product->show_in_frontend && $product->track_inventory)
                            @php $quantity = $product->stocks->sum('quantity'); @endphp
                            <div data-stocks="{{ $product->stocks }}" class="badge badge--{{ $quantity > 0 ? 'success' : 'danger' }} stock-status">@lang('In Stock') (<span class="stock-qty">{{ $quantity }}</span>)</div>
                        @endif

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

                            @php $attr_data = $attr_data = \App\Models\AssignProductAttribute::productAttributes($product->id, $attr->product_attribute_id); @endphp
                            @if ($attr->productAttribute->type == 1)
                                <div class="product-size-area attr-area">
                                    <span class="caption">{{ __($attr->productAttribute->name_for_user) }}</span>
                                    @foreach ($attr_data as $data)
                                        <div class="product-single-size attribute-btn" data-type="1" data-discount={{ $discount }} data-ti="{{ $product->track_inventory }}" data-attr_count="{{ $attributes->count() }}" data-id="{{ $data->id }}" data-product_id="{{ $product->id }}" data-price="{{ $data->extra_price }}" data-base_price="{{ $product->base_price }}">{{ $data->value }}</div>
                                    @endforeach
                                </div>
                            @endif
                            @if ($attr->productAttribute->type == 2)
                                <div class="product-color-area attr-area">
                                    <span class="caption">{{ __($attr->productAttribute->name_for_user) }}</span>
                                    @foreach ($attr_data as $data)
                                        <div class="product-single-color attribute-btn" data-type="2" data-ti="{{ $product->track_inventory }}" data-discount={{ $discount }} data-attr_count="{{ $attributes->count() }}" data-id="{{ $data->id }}" data-product_id="{{ $product->id }}" data-bg="{{ $data->value }}" data-price="{{ $data->extra_price }}" data-base_price="{{ $product->base_price }}"></div>
                                    @endforeach
                                </div>
                            @endif
                            @if ($attr->productAttribute->type == 3)
                                <div class="product-color-area attr-area">
                                    <span class="caption">{{ __($attr->productAttribute->name_for_user) }}</span>
                                    @foreach ($attr_data as $data)
                                        <div class="product-single-color attribute-btn bg_img" data-type="3" data-ti="{{ $product->track_inventory }}" data-discount={{ $discount }} data-attr_count="{{ $attributes->count() }}" data-id="{{ $data->id }}" data-product_id="{{ $product->id }}" data-price="{{ $data->extra_price }}" data-base_price="{{ $product->base_price }}" data-background="{{ getImage(getFilePath('attribute') . '/' . $data->value, getFileSize('attribute')) }}">
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
                                <input type="number" name="quantity" min="1" value="1" class="integer-validation">
                                <div class="cart-increase qtybutton inc">
                                    <i class="las la-plus"></i>
                                </div>
                            </div>

                            <div class="add-cart">
                                <button type="submit" class="cmn-btn cart-add-btn text-white" data-id="{{ $product->id }}">@lang('Add To Cart')</button>
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

                            @if ($product->seller && $product->seller->shop)
                                <p>
                                    <b>
                                        @lang('Seller'):
                                    </b>
                                    <a href="{{ route('seller.details', [$product->seller->id, slug($product->seller->shop->name)]) }}" class="text--base">{{ $product->seller->shop->name }}</a>
                                </p>
                            @endif

                            <p class="product-share">
                                <b>@lang('Share'):</b>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" title="@lang('Facebook')" target="blank">

                                    <i class="fab fa-facebook"></i>
                                </a>

                                <a href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __($product->name) }}&media={{ getImage('assets/images/product/' . @$product->main_image) }}" title="@lang('Pinterest')" target="blank">

                                    <i class="fab fa-pinterest-p"></i>
                                </a>

                                <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title=my share text&amp;summary=dit is de linkedin summary" title="@lang('Linkedin')" target="blank">

                                    <i class="fab fa-linkedin"></i>
                                </a>

                                <a href="https://twitter.com/intent/tweet?text={{ __($product->name) }}%0A{{ url()->current() }}" title="@lang('Twitter')" target="blank">

                                    <i class="fab fa-twitter"></i>
                                </a>
                            </p>
                            @php
                                $wCk = checkWishList($product->id);
                            @endphp
                            <p class="product-details-wishlist">
                                <b>@lang('Add To Wishlist'): </b>
                                <a href="javascript:void(0)" title="@lang('Add To Wishlist')" class="add-to-wish-list {{ $wCk ? 'active' : '' }}" data-id="{{ $product->id }}"><span class="wish-icon"></span></a>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Single Section Ends Here -->

    <!-- Product Single Section Starts Here -->
    <div class="products-description padding-bottom padding-top-half">
        <div class="container">

            <ul class="nav nav-tabs">
                <li>
                    <a href="#description" data-bs-toggle="tab">@lang('Description')</a>
                </li>

                <li>
                    <a href="#specification" class="active" data-bs-toggle="tab">@lang('Specification')</a>
                </li>

                <li>
                    <a href="#video" data-bs-toggle="tab">@lang('Video')</a>
                </li>

                <li>
                    <a href="#reviews" data-bs-toggle="tab">@lang('Reviews')({{ __($product->reviews->count()) }})</a>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade" id="description">
                    <div class="description-item">
                        @if ($product->description)
                            <p>
                                @lang($product->description)
                            </p>
                        @else
                            <div class="alert cl-title alert--base" role="alert">
                                @lang('No Description For This Product')
                            </div>
                        @endif
                    </div>

                    @if ($product->extra_descriptions)
                        <div class="description-item">
                            @foreach ($product->extra_descriptions as $item)
                                <h5>{{ __($item['key']) }}</h5>
                                <p>
                                    @php
                                        echo __($item['value']);
                                    @endphp
                                </p>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade show active" id="specification">
                    <div class="specification-wrapper">
                        @if ($product->specification)
                            <h5 class="title">@lang('Specification')</h5>
                            <div class="table-wrapper">
                                <table class="specification-table">
                                    @foreach ($product->specification as $item)
                                        <tr>
                                            <th>{{ __($item['name']) }}</th>
                                            <td>{{ __($item['value']) }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @else
                            <div class="alert cl-title alert--base" role="alert">
                                @lang('No Specification For This Product')
                            </div>
                        @endif
                    </div>
                </div>

                <div class="tab-pane fade" id="video">
                    @if ($product->video_link && $product->video_link != '')
                        <iframe width="560" height="315" src="{{ $product->video_link }}" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    @else
                        <div class="alert cl-title alert--base" role="alert">
                            @lang('No Video For This Product')
                        </div>
                    @endif
                </div>

                <div class="tab-pane fade" id="reviews">
                    <div class="review-area">

                    </div>
                </div>
            </div>
            @if ($relatedProducts)
                <div class="related-products mt-5">
                    <h5 class="title bold mb-3 mb-lg-4">@lang('Related Products')</h5>
                    <div class="m--15 oh">
                        <div class="related-products-slider owl-carousel owl-theme">
                            @include('Template::partials.product_card', ['products' => $relatedProducts, 'class' => ''])
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            var pid = '{{ $product->id }}';
            loadReviews(pid);

            function loadReviews(pid, url = "{{ route('product.review.load.more') }}") {
                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        pid: pid
                    },
                    success: function(data) {
                        $('#load_more_button').remove();
                        $('.review-area').append(data);
                    }
                });
            }
            $(document).on('click', '#load_more_button', function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
                $('#load_more_button').html(`<b>{{ __('Loading') }} <i class="fa fa-spinner fa-spin"></i> </b>`);
                loadReviews(pid, url);
            });

        })(jQuery)
    </script>
@endpush
