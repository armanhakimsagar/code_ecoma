@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <!-- Product Single Section Starts Here -->
    <div class="category-section padding-bottom padding-top">
        <div class="container">
            <div class="product-details-wrapper justify-content-center">
                @php
                    echo $policy->data_values->details;
                @endphp
            </div>
        </div>
    </div>
@endsection
