@if ($topSellingProducts->count())
    <section class="cash-on-shop-section padding-bottom padding-top-half overflow-hidden">
        <div class="container">
            <div class="section-header-2 left-style">
                <h4 class="title pr-0">@lang('Top Selling Products')</h4>
            </div>

            <div class="row g-3 justify-content-center">
                @foreach ($topSellingProducts as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="best-sell-item">
                            <div class="best-sell-inner">
                                <div class="thumb">
                                    <a href="{{ route('product.detail', $item->slug) }}"><img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->main_image, getFileSize('product')) }}" alt="@lang('products-sell')"></a>
                                </div>
                                <div class="content">
                                    <h6 class="title">
                                        <h6 class="title">
                                            <a href="{{ route('product.detail', $item->slug) }}">{{ __($item->name) }}</a>
                                        </h6>
                                    </h6>
                                    <div class="ratings-area justify-content-between">
                                        <div class="ratings">
                                            @php echo displayAvgRating($item->reviews) @endphp
                                        </div>
                                        <span class="ml-2 mr-auto">({{ $item->reviews->count() }})</span>
                                    </div>
                                    <a href="{{ route('product.detail', $item->slug) }}" class="read-more cl-1">@lang('View Details')<i class="las la-long-arrow-alt-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
