@extends('Template::layouts.frontend')
@section('content')
    <main class="banner-body bg--section">
        <div class="container">
            <div class="banner-section overflow-hidden">
                @include('Template::partials.left_category_menu')
                @include('Template::sections.banner_sliders')
                @include('Template::sections.banner_promotional')
            </div>
        </div>
        @include('Template::sections.banner_categories')
    </main>

    @include('Template::sections.invite')

    @if ($offers->count() > 0)
        @include('Template::sections.offers')
    @endif

    @if ($featuredProducts->count() > 0)
        @include('Template::sections.featured_products')
    @endif

    @if ($latestProducts->count() > 0)
        @include('Template::sections.latest_products')
    @endif

    @if ($featuredSeller->count() > 0)
        @include('Template::sections.featured_seller')
    @endif

    @include('Template::sections.invite_seller')

    @if ($topBrands->count() > 0)
        @include('Template::sections.brands')
    @endif

    @if ($topSellingProducts->count() > 0)
        @include('Template::sections.trending_products')
    @endif

    @include('Template::sections.subscribe')
@endsection
