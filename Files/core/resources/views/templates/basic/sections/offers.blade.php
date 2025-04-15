@foreach ($offers as $offer)
    <section class="hot-deal-section padding-bottom-half padding-top-half overflow-hidden border-bottom">
        <div class="container">
            <div class="section-header-2 mb-3">
                <h4 class="title">{{ __($offer->name) }}</h4>
                <span class="bg--base px-2 py-1"> @lang('Ends') {{ diffForHumans($offer->end_date) }}</span>
            </div>
            <div class="row g-4">
                @include('Template::partials.product_card', ['products' => $offer->products, 'class' => 'col-sm-6 col-xl-3', 'noMargin' => true])
            </div>
        </div>
    </section>
@endforeach
