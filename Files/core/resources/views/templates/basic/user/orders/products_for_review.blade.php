@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="dashboard-section padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-xl-3">
                    <div class="dashboard-menu">
                        @include('Template::user.partials.dp')
                        <ul>
                            @include('Template::user.partials.sidebar')
                        </ul>
                    </div>
                </div>
                <div class="col-xl-9">
                    <table class="regular-table section-bg">
                        <thead class="bg--base">
                            <tr>
                                <th class="text-white">@lang('Products')</th>
                                <th class="text-white pe-4 text-end">@lang('Review')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('product.detail', $item->slug) }}" class="cart-item mw-100 p-2">
                                            <div class="cart-img">
                                                <img src="{{ getImage(getFilePath('product') . '/thumb_' . $item->main_image, getFileSize('product')) }}" alt="@lang('cart')">
                                            </div>

                                            <div class="cart-cont">
                                                <h6 class="title">{{ $item->name }}</h6>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if ($item->userReview)
                                            <button type="button" class="cmn-btn" disabled>@lang('Reviewed')</button>
                                        @else
                                            <button type="button" class="cmn-btn review-btn" data-pid="{{ $item->id }}">@lang('Review Now')</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%">
                                        {{ __($emptyMessage) }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($products->hasPages())
                        <div class="product-pagination">
                            <div class="theme-paggination-block">
                                {{ paginateLinks($products) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="reviewModal" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Review')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="add-review">
                        <form action="{{ route('user.product.review.submit') }}" method="POST" class="review-form rating row">
                            @csrf
                            <input type="hidden" name="pid" value="">
                            <div class="review-form-group mb-20 col-md-6 d-flex flex-wrap">
                                <label class="review-label mb-0 mr-3">@lang('Your Rating') :</label>
                                <div class="rating-form-group">
                                    <label class="star-label">
                                        <input type="radio" name="rating" value="1" />
                                        <span class="icon"><i class="las la-star"></i></span>
                                    </label>
                                    <label class="star-label">
                                        <input type="radio" name="rating" value="2" />
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                    </label>
                                    <label class="star-label">
                                        <input type="radio" name="rating" value="3" />
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                    </label>
                                    <label class="star-label">
                                        <input type="radio" name="rating" value="4" />
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                    </label>
                                    <label class="star-label">
                                        <input type="radio" name="rating" value="5" />
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                        <span class="icon"><i class="las la-star"></i></span>
                                    </label>
                                </div>
                            </div>
                            <div class="review-form-group mb-20 col-12 d-flex flex-wrap">
                                <label class="review-label" for="review-comments">@lang('Say Something about This Product')</label>
                                <textarea name="review" class="review-input rounded--5 border--1" id="review-comments" required></textarea>
                            </div>
                            <div class="review-form-group mb-20 col-12 d-flex flex-wrap">
                                <button type="submit" class="submit-button rounded--5 btn--sm ml-auto">@lang('Submit Review')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.review-btn').on('click', function() {
                var modal = $('#reviewModal');
                modal.find('input[name=pid]').val($(this).data('pid'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .cart-item .cart-img {
            height: 50px;
            width: 50px;
            padding: 5px;
        }
    </style>
@endpush
