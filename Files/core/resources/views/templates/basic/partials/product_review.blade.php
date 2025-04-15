@if ($reviews->count())
    @foreach ($reviews as $item)
        <div class="review-item">
            <div class="thumb">
                <img src="{{ getAvatar(getFilePath('userProfile') . '/' . $item->user->image, getFileSize('userProfile')) }}" alt="@lang('review')">
            </div>
            <div class="content">
                <div class="entry-meta">
                    <h6 class="posted-on">
                        <a href="javascript:void(0)">{{ $item->user->fullname }}</a>
                        <span>{{ diffForHumans($item->created_at) }}</span>
                    </h6>
                    <div class="ratings">
                        @php echo ratings($item->rating) @endphp
                    </div>
                </div>
                <div class="entry-content">
                    <p>{{ __($item->review) }}</p>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="alert cl-title alert--base" role="alert">
        <strong>@lang('No reviews yet for this product')</strong>
    </div>
@endif

@if ($reviews->currentPage() != $reviews->lastPage())
    <div id="load_more" class="mt-4">
        <button type="button" name="load_more_button" class="cmn-btn btn-block" id="load_more_button" data-url="{{ $reviews->nextPageUrl() }}">@lang('Load More')</button>
    </div>
@endif
