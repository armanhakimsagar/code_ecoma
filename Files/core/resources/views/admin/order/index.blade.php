@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Order ID') | @lang('Time')</th>
                                    <th>@lang('Customer')</th>

                                    @if (!request()->routeIs('admin.order.cod'))
                                        <th>@lang('Payment Via')</th>
                                    @endif

                                    <th>@lang('Amount')</th>

                                    @if (request()->routeIs('admin.order.all'))
                                        <th>@lang('Status')</th>
                                    @endif

                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">#{{ @$item->order_number }}</span> <br>
                                            <small>{{ showDateTime($item->created_at) }}</small>
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.users.detail', $item->user->id) }}">{{ $item->user->username }}</a>
                                        </td>

                                        @if (!request()->routeIs('admin.order.cod'))
                                            <td>
                                                @if ($item->payment_status == 2)
                                                    <strong class="text--warning"><abbr data-bs-toggle="tooltip" title="@lang('Cash On Delivery')">
                                                            {{ @$deposit->gateway->name ?? trans('COD') }}</abbr>
                                                    </strong>
                                                @elseif($item->deposit)
                                                    <strong class="text--primary">{{ $item->deposit->gateway->name }}</strong>
                                                @endif
                                            </td>
                                        @endif

                                        <td>
                                            <b>{{ showAmount($item->total_amount) }}</b>
                                        </td>

                                        @if (request()->routeIs('admin.order.all'))
                                            <td>
                                                @php echo $item->statusBadge(); @endphp
                                            </td>
                                        @endif

                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.order.details', $item->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-desktop"></i>@lang('Detail')
                                                </a>

                                                @if (!request()->routeIs('admin.order.notpaid'))
                                                    @if ($item->status == Status::ORDER_PENDING)
                                                        <button type="button" class="btn btn-sm btn-outline--success approveBtn" data-action="{{ Status::ORDER_PROCESSING }}" data-id='{{ $item->id }}' title="@lang('Mark as Processing')">
                                                            <i class="la la-check"></i>@lang('Processing')
                                                        </button>
                                                    @elseif ($item->status == Status::ORDER_READY_TO_DELIVER)
                                                        <button type="button" class="btn btn-sm btn-outline--success approveBtn" data-action="{{ Status::ORDER_DISPATCHED }}" data-id='{{ $item->id }}' title="@lang('Mark as Dispatched')">
                                                            <i class="la la-truck"></i>@lang('Dispatch')
                                                        </button>
                                                    @elseif($item->status == Status::ORDER_DISPATCHED)
                                                        <button type="button" class="btn btn-outline--success approveBtn" data-action="{{ Status::ORDER_DELIVERED }}" data-id='{{ $item->id }}' title="@lang('Mark as Delivered')">
                                                            <i class="la la-check"></i>@lang('Deliver')
                                                        </button>
                                                    @endif

                                                    @if ($item->status == Status::ORDER_PENDING)
                                                        <button type="button" class="btn btn-sm btn-outline--danger approveBtn" data-action="{{ Status::ORDER_CANCELED }}" data-id="{{ $item->id }}">
                                                            <i class="la la-ban"></i>@lang('Cancel')
                                                        </button>
                                                    @elseif($item->status == Status::ORDER_CANCELED)
                                                        <button type="button" class="btn btn-sm btn-outline--dark approveBtn" data-action="{{ Status::ORDER_PENDING }}" data-id="{{ $item->id }}"><i class="la la-reply"></i>@lang('Retake')</button>
                                                    @endif
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

                @if ($orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- DELIVERY METHOD MODAL --}}
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.order.status') }}" method="POST" id="deliverPostForm">
                    @csrf
                    <input type="hidden" name="id" id="oid">
                    <input type="hidden" name="action" id="action">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">@lang('Confirmation Alert')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="question"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Order ID" />
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                $('#oid').val($(this).data('id'));
                var action = $(this).data('action');

                $('#action').val(action);

                if (action == @json(Status::ORDER_PROCESSING)) {
                    $('.question').text("@lang('Are you sure to mark the order as processing?')");
                } else if (action == @json(Status::ORDER_READY_TO_DELIVER)) {
                    $('.question').text("@lang('Are you sure to mark the order as ready to deliver?')");
                } else if (action == @json(Status::ORDER_DISPATCHED)) {
                    $('.question').text("@lang('Are you sure to mark the order as dispatched?')");
                } else if (action == @json(Status::ORDER_DELIVERED)) {
                    $('.question').text("@lang('Are you sure to mark the order as delivered?')");
                } else if (action == @json(Status::ORDER_CANCELED)) {
                    $('.question').text("@lang('Are you sure to cancel this order?')");
                } else {
                    $('.question').text("@lang('Are you sure to retake this order?')");
                }
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
