@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <!-- Category Section Starts Here -->
    <div class="category-section padding-bottom padding-top">
        <div class="container">
            <div class="row">
                @forelse ($brands as $item)
                    <div class="col-md-2 mb-4">
                        <div class="cate-item">
                            <a href="{{ route('products.brand', ['id' => $item->id, 'slug' => slug($item->name)]) }}" class="cate-inner">
                                <img class="w-100" src="{{ getImage(getFilePath('brand') . '/' . $item->logo, getFileSize('brand')) }}" alt="{{ $item->name }}">
                            </a>
                        </div>
                    </div>
                @empty
                    @if ($brands->count() == 0)
                        <div class="col-lg-12 mb-30">
                            @include($activeTemplate . 'partials.empty_message', ['message' => __($emptyMessage)])
                        </div>
                    @endif
                @endforelse
            </div>

            {{ paginateLinks($brands) }}

        </div>
    </div>
    <!-- Category Section Ends Here -->
@endsection
