<div class="sync1 owl-carousel owl-theme">
    @foreach ($images as $item)
        <div class="thumbs">
            <img class="zoom_img" src="{{ getImage(getFilePath('product') . '/' . @$item->image, getFileSize('product')) }}" alt="@lang('products-details')">
        </div>
    @endforeach
</div>

<div class="sync2 owl-carousel owl-theme mt-2">
    @foreach ($images as $item)
        <div class="thumbs">
            <img src="{{ getImage(getFilePath('product') . '/' . @$item->image, getFileSize('product')) }}" alt="@lang('products-details')">
        </div>
    @endforeach
</div>
