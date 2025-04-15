@php
    $categories = \App\Models\Category::where('is_special', Status::YES)->get();
@endphp

<div class="container mt-4">
    <div class="overflow-hidden">
        <div class="related--slider-wrapper">
            <div class="related-slider owl-carousel owl-theme">
                @foreach ($categories as $item)
                    <a href="{{ route('products.category', ['id' => $item->id, 'slug' => slug($item->name)]) }}" class="d-block related-slide-item">
                        <div class="mb-10 overflow-hidden rounded">
                            <img src="{{ getImage(getFilePath('category') . '/' . @$item->image, getFileSize('category')) }}" class="w-100" alt="products-hot">
                        </div>
                        <span class="line-limitation-1 text-center">{{ __($item->name) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
