@extends('admin.layouts.app')
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
                                    <th>@lang('Review At')</th>
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
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary view-btn" data-user="{{ __($review->user->username) }}" data-rating="{{ $review->rating }}" data-review="{{ $review->review }}" data-user_link="{{ route('admin.users.detail', $review->user->id) }}">
                                                    <i class="la la-desktop"></i>@lang('View')
                                                </button>

                                                @if ($review->trashed())
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.products.review.status', $review->id) }}" data-question="Are you sure to restore this review?">
                                                        <i class="la la-redo"></i>@lang('Restore')
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-question="Are you sure to delete this review?" data-action="{{ route('admin.products.review.status', $review->id) }}">
                                                        <i class="la la-trash"></i>@lang('Delete')
                                                    </button>
                                                @endif
                                            </div>
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
                        <li class="list-group-item d-flex flex-wrap justify-content-between px-0">
                            <span>@lang('Customer')</span>
                            <a href="" id="user-detail">
                                <span id="name"></span>
                            </a>
                        </li>

                        <li class="list-group-item d-flex flex-wrap justify-content-between px-0">
                            <span>@lang('Rating')</span>
                            <span id="rating"></span>
                        </li>

                        <li class="list-group-item d-flex flex-wrap justify-content-between px-0 gap-2">
                            <span>@lang('Review')</span>
                            <span id="review"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />

    @if (request()->routeIs('admin.products.reviews'))
        <a href="{{ route('admin.products.reviews.trashed') }}" class="btn btn-outline--danger"><i class="la la-trash-alt"></i>@lang('Trashed')</a>
    @else
        <x-back route="{{ route('admin.products.reviews') }}" />
    @endif
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {

            $('.view-btn').on('click', function() {
                var modal = $('#viewModal');

                modal.find('#name').text($(this).data('user'));
                modal.find('#rating').text($(this).data('rating'));
                modal.find('#review').text($(this).data('review'));
                modal.find('#user-detail').attr('href', $(this).data('user_link'));

                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
