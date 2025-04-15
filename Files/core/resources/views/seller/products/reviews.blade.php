@extends('seller.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Product')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Rating')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>{{ __($review->product->name) }}</td>
                                        <td>{{ __($review->user->username) }}</td>
                                        <td>{{ __($review->rating) }}</td>
                                        <td>{{ diffForHumans($review->created_at) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary view-btn" data-user="{{ __($review->user->username) }}" data-rating="{{ $review->rating }}" data-review="{{ $review->review }}"><i class="la la-desktop"></i>@lang('Detail')</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($reviews->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($reviews) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Product Review')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span class="fw-bold">@lang('Customer')</span>
                            <span id="name"></span>
                        </li>

                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span class="fw-bold">@lang('Rating')</span>
                            <span id="rating"></span>
                        </li>

                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span class="fw-bold">@lang('Review')</span>
                            <span id="review"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.view-btn').on('click', function() {
                var modal = $('#viewModal');
                modal.find('#name').text($(this).data('user'));
                modal.find('#rating').text($(this).data('rating'));
                modal.find('#review').text($(this).data('review'));
                modal.modal('show');
            });

            $('.image-popup').magnificPopup({
                type: 'image'
            });
        })(jQuery);
    </script>
@endpush

@push('breadcrumb-plugins')
    <x-search-form />
@endpush
