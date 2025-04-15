<!-- Featured Section Starts Here -->
<div class="featured-section padding-bottom-half padding-top-half oh">
    <div class="container">
        <div class="section-header-2">
            <h3 class="title">@lang('Our Latest Products')</h3>
        </div>
        <div class="row g-4">
            @include($activeTemplate . 'partials.product_card', ['products' => $latestProducts, 'class' => 'col-sm-6 col-xl-3', 'noMargin' => true])
        </div>
    </div>
</div>
<!-- Featured Section Ends Here -->
