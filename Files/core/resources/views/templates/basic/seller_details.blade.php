@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <!-- Vendor Sections Here -->
    <section class="vendor-profile padding-bottom-half">
        <div class="container">
            <div class="vendor__single__item">
                <div class="vendor__single__item-thumb">
                    <img src="{{ getImage(getFilePath('sellerShopCover') . '/' . $seller->shop->cover, getFileSize('sellerShopCover')) }}" alt="vendor">
                </div>
                <div class="vendor__single__item-content">
                    <div class="vendor__single__author">
                        <div class="thumb">
                            <img src="{{ getImage(getFilePath('sellerShopLogo') . '/' . $seller->shop->logo) }}" alt="vendor">
                        </div>
                        <div class="content">
                            <div class="title__area">
                                <h4 class="title">{{ $seller->shop->name }}</h4>
                                @if (!empty($seller->shop->social_links))
                                    @php
                                        $socials = json_decode(json_encode($seller->shop->social_links));
                                    @endphp
                                    <ul class="social__icons">
                                        @foreach ($socials as $item)
                                            <li>
                                                <a target="_blank" href="{{ $item->link }}">@php echo $item->icon; @endphp</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="content-area">
                                <ul>
                                    <li>
                                        <i class="las la-map-marker-alt">{{ $seller->shop->address }}</i>
                                    </li>
                                    <li>
                                        <i class="las la-phone"></i> {{ $seller->shop->phone }}
                                    </li>
                                    <li>
                                        <i class="las la-envelope"></i>{{ $seller->email }}
                                    </li>
                                    @if ($seller->shop->opens_at)
                                        <li>
                                            <i class="las la-door-open"></i>@lang('Opens at :'){{ showDateTime($seller->shop->opens_at, 'h:i a') }}
                                        </li>
                                    @endif

                                    @if ($seller->shop->closed_at)
                                        <li>
                                            <i class="las la-door-closed"></i>@lang('Closed at :'){{ showDateTime($seller->shop->closed_at, 'h:i a') }}
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Vendor Sections Here -->

    <!-- Vendor Products Sections Here -->
    <section class="vendor-products padding-bottom">
        <div class="container">
            <div class="section-header-2">
                <h4 class="title mr-auto">@lang('Seller Products')</h4>
            </div>
            <div class="row g-2 justify-content-center">
                @if ($products->count())
                    @include('Template::partials.product_card', ['products' => $products, 'class' => 'col-lg-3 col-sm-6 grid-control mb-30'])
                @else
                    <div class="col-12 col-sm-4 col-md-3 col-lg-2 col-xxl-8-item text-center">
                        <h6>@lang('No Product Yet')</h6>
                    </div>
                @endif

            </div>
            @if ($products->hasPages())
                <div class="row justify-content-center">
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xxl-8-item">
                        {{ paginateLinks($products) }}
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Vendor Products Sections Here -->
@endsection
