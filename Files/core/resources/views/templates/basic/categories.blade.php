@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- Category Section Starts Here -->
    <div class="category-section padding-bottom padding-top">
        <div class="container">
            <div class="row">
                @forelse ($categories as $item)
                    <div class="col-md-2 mb-4">
                        <div class="cate-item">
                            <a href="{{ route('products.category', ['id' => $item->id, 'slug' => slug($item->name)]) }}" class="cate-inner">
                                <img class="w-100" src="{{ getImage(getFilePath('category') . '/' . $item->image, getFileSize('category')) }}" alt="{{ __($item->name) }}">
                                <span class="line-limitation-1">{{ $item->name }}</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="col-lg-12 mb-30">
                            @include($activeTemplate . 'partials.empty_message', ['message' => __($emptyMessage)])
                        </div>
                    </div>
                @endforelse
            </div>
            {{ paginateLinks($categories) }}
        </div>
    </div>
    <!-- Category Section Ends Here -->
@endsection
