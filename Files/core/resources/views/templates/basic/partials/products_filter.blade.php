<div class="row mb-30-none page-main-content" id="grid-view">
    @include($activeTemplate . 'partials.product_card', ['products' => $products, 'class' => 'col-lg-4 col-sm-6 grid-control mb-30', 'noMargin' => true])
    @if ($products->count() == 0)
        <div class="col-lg-12 mb-30">
            @include($activeTemplate . 'partials.empty_page', ['message' => __($emptyMessage)])
        </div>
    @endif
</div>

{{ $products->appends(['perpage' => @$perpage, 'brand' => @$brand, 'category_id' => @$category_id, 'min' => @$min, 'max' => @$max])->links() }}
