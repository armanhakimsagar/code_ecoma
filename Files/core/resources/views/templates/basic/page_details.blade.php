@extends($activeTemplate .'layouts.frontend')

@section('content')

<!-- Product Single Section Starts Here -->
<div class="category-section padding-bottom padding-top">
    <div class="container">
        <div class="product-details-wrapper">
                @php echo $pageDetails->data_values->description; @endphp
        </div>
    </div>
</div>

@endsection
