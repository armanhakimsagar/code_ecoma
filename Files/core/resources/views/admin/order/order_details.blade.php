@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        @if ($order->payment_status == Status::PAYMENT_SUCCESS && $order->subOrders->where('status', Status::SUBORDER_REJECTED)->where('is_refunded', Status::NO)->count())
            <div class="card bl--5 border--danger">
                <div class="card-body">
                    <p class="text--danger">@lang('The customer has already been paid for this order. However, some suborders within this order have been rejected. Please note that no refunds will be issued for the rejected products, as per our policy.')</p>
                </div>
            </div>
        @elseif($order->payment_status == Status::PAYMENT_SUCCESS && $order->status == Status::ORDER_CANCELED && !$order->is_refunded)
            <div class="card bl--5 border--danger">
                <div class="card-body">
                    <p class="text--danger">
                        @lang('The customer has already made the payment for this order. However, as per our policy, you must issue a full refund upon cancellation.')
                        <a href="javascript:void(0)" class="text--dark refundOrderBtn">@lang('Refund Now.')</a>
                    </p>
                </div>
            </div>
        @endif
        <div class="col-xl-8">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div>
                                    <h5 class="card-title mb-0">@lang('Progress')</h5>
                                    <small class="text-muted">@lang('Current Order Status')</small>
                                </div>
                                <div>
                                    <h6>@lang('Order No') #{{ $order->order_number }}</h6>
                                    <div class="text-end">
                                        @php echo $order->statusBadge() @endphp
                                    </div>
                                </div>
                            </div>
                            <div class="status_box_wrapper">
                                <div class="status_box_container">
                                    <div class="status_box">
                                        <div class="icon">
                                            <i class="las la-spinner"></i>
                                        </div>
                                        <span class="title">@lang('Pending')</span>
                                        <span class="bar @if ($order->status != Status::ORDER_CANCELED) complete @endif"></span>
                                    </div>

                                    <div class="status_box">
                                        <div class="icon">
                                            <i class="las la-cog"></i>
                                        </div>
                                        <span class="title">@lang('Processing')</span>
                                        <span class="bar @if (in_array($order->status, [Status::ORDER_PROCESSING, Status::ORDER_READY_TO_DELIVER, Status::ORDER_DISPATCHED, Status::ORDER_DELIVERED])) complete @endif"></span>
                                    </div>

                                    <div class="status_box">
                                        <div class="icon">
                                            <i class="las la-truck-loading"></i>
                                        </div>
                                        <span class="title">@lang('Ready To Deliver')</span>
                                        <span class="bar @if (in_array($order->status, [Status::ORDER_READY_TO_DELIVER, Status::ORDER_DISPATCHED, Status::ORDER_DELIVERED])) complete @endif"></span>
                                    </div>

                                    <div class="status_box">
                                        <div class="icon">
                                            <i class="las la-truck-pickup"></i>
                                        </div>
                                        <span class="title">@lang('Dispatched')</span>
                                        <span class="bar @if (in_array($order->status, [Status::ORDER_DISPATCHED, Status::ORDER_DELIVERED])) complete @endif"></span>
                                    </div>

                                    <div class="status_box">
                                        <div class="icon">
                                            <i class="las la-hand-holding"></i>
                                        </div>
                                        <span class="title">@lang('Delivered')</span>
                                        <span class="bar @if ($order->status == Status::ORDER_DELIVERED) complete @endif"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <h5 class="card-title mb-0">@lang('Products')</h5>
                                <small class="text-muted">@lang('Ordered Items Information')</small>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered order-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Seller')</th>
                                            <th>@lang('Product')</th>
                                            <th>@lang('Quantity')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Price')</th>
                                            <th>@lang('Sub Total')</th>
                                            <th>@lang('Total')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->subOrders as $key => $subOrder)
                                            @php
                                                $isEven = $key % 2;
                                                $totalRow = $subOrder->orderDetail->count();
                                            @endphp

                                            @foreach ($subOrder->orderDetail as $index => $orderDetail)
                                                <tr class="@if (!$isEven) odd @endif">
                                                    @if ($index == 0)
                                                        <td rowspan="{{ $totalRow }}">
                                                            <div class="seller-info">
                                                                @if ($subOrder->seller)
                                                                    <p class="name">{{ __($subOrder->seller->fullname) }}</p>
                                                                    <a href="{{ route('admin.sellers.detail', $subOrder->seller_id) }}" class="link">@lang('View Details')</a>
                                                                @else
                                                                    <p class="name">@lang('Admin')</p>
                                                                @endif
                                                            </div>

                                                            <div class="order_info">
                                                                <p class="fw-bold mt-2">#{{ $subOrder->order_number }}</p>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <div class="product-item">
                                                            <div class="thumb">
                                                                <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$orderDetail->product->main_image, getFileSize('product')) }}" alt="@lang('product-image')">
                                                            </div>

                                                            <div class="content">
                                                                <span class="name">
                                                                    {{ strLimit(__(@$orderDetail->product->name), 20) }}
                                                                </span>
                                                                @if ($orderDetail->details)
                                                                    @php
                                                                        $details = json_decode($orderDetail->details);
                                                                    @endphp
                                                                    @if ($details->variants)
                                                                        <ul class="variants">
                                                                            @foreach ($details->variants as $variant)
                                                                                <li>{{ __($variant->value) }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @else
                                                                        <small class="lh-1 text-muted">@lang('No Variants')</small>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $orderDetail->quantity }}</td>

                                                    @if ($index == 0)
                                                        <td rowspan="{{ $totalRow }}">
                                                            @php echo $subOrder->statusBadge @endphp

                                                            @if ($order->status != Status::ORDER_CANCELED && $subOrder->seller_id && $subOrder->status == Status::SUBORDER_READY_TO_PICKUP)
                                                                <a href="javascript:void(0)" class="refund_link mt-1 d-block confirmationBtn" data-question="@lang('Are you sure the order has been picked up?')" data-action="{{ route('admin.suborder.mark.as.picked.up', $subOrder->id) }}"><i class="las la-truck-load"></i>@lang('Picked Up')</a>
                                                            @endif

                                                            @if ($order->status != Status::ORDER_CANCELED && !$subOrder->seller_id && !in_array($subOrder->status, [Status::SUBORDER_DELIVERED, Status::SUBORDER_REJECTED]))
                                                                <div class="dropdown mt-1">
                                                                    <a href="javascript:void(0)" class="refund_link dropdown-toggle" type="button" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        @lang('Change Status')
                                                                    </a>

                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                        @if ($subOrder->status == Status::SUBORDER_PENDING)
                                                                            <a href="javascript:void(0)" class="confirmationBtn dropdown-item" data-question="@lang('Are you sure to mark the order as processing?')" data-action="{{ route('admin.suborder.mark.as.processing', $subOrder->id) }}"><i class="las la-check-double"></i> @lang('Mark As Processing')</a>

                                                                            <a href="javascript:void(0)" class="confirmationBtn dropdown-item" data-question="@lang('Are you sure to mark the suborder as rejected?')" data-action="{{ route('admin.suborder.reject', $subOrder->id) }}"><i class="las la-times-circle"></i> @lang('Reject')</a>
                                                                        @elseif($subOrder->status == Status::SUBORDER_PROCESSING)
                                                                            <a href="javascript:void(0)" class="confirmationBtn dropdown-item" data-question="@lang('Are you sure to mark the order as picked up?')" data-action="{{ route('admin.suborder.mark.as.picked.up', $subOrder->id) }}"><i class="las la-check-double"></i> @lang('Mark As Picked Up')</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($order->payment_status == Status::PAYMENT_SUCCESS && $subOrder->status == Status::SUBORDER_REJECTED && $subOrder->is_refunded == Status::NO)
                                                                <a href="javascript:void(0)" class="refund_link mt-1 d-block refundBtn" data-suborder_id="{{ $subOrder->id }}" data-amount="{{ showAmount($subOrder->total_amount) }}">@lang('Refund Now')</a>
                                                            @elseif($order->payment_status == Status::PAYMENT_SUCCESS && $subOrder->status == Status::SUBORDER_REJECTED && $subOrder->is_refunded == Status::YES)
                                                                <small class="text-muted mt-1 d-block">@lang('Refunded')</small>
                                                            @endif
                                                        </td>
                                                    @endif

                                                    <td>{{ gs('cur_sym') . showAmount($orderDetail->base_price, currencyFormat: false) }}</td>
                                                    <td>{{ gs('cur_sym') . showAmount($orderDetail->total_price, currencyFormat: false) }}</td>

                                                    @if ($index == 0)
                                                        <td rowspan="{{ $totalRow }}">
                                                            {{ gs('cur_sym') . showAmount($subOrder->total_amount, currencyFormat: false) }}
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center mb-3">
                                <div>
                                    <h5 class="card-title mb-0">@lang('Payment') @php echo $order->paymentBadge() @endphp</h5>
                                    <small class="text-muted">@lang('Order Payment Summary')</small>
                                </div>
                                <div>
                                    <a href="{{ route('admin.order.invoice', $order->id) }}" class="btn btn-sm btn-outline--primary"><i class="las la-file-invoice"></i>@lang('Invoice')</a>
                                </div>
                            </div>
                            <div class="summary_wrapper">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                    <span class="title">@lang('Subtotal')</span>
                                    <span class="value">{{ gs('cur_sym') . showAmount($order->orderDetail->sum('total_price'), currencyFormat: false) }}</span>
                                </div>

                                @if ($order->coupon_amount > 0)
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                        <span class="title">@lang('Coupon Amount')</span>
                                        <span class="value">{{ gs('cur_sym') . showAmount($order->coupon_amount, currencyFormat: false) }}</span>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                    <span class="title">@lang('Shipping Charge')</span>
                                    <span class="value">{{ gs('cur_sym') . showAmount($order->shipping_charge, currencyFormat: false) }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 total_amount">
                                    <span class="title fw-bold">@lang('Total')</span>
                                    <span class="value fw-bold">{{ gs('cur_sym') . showAmount($order->total_amount, currencyFormat: false) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <h5 class="card-title mb-0">@lang('Customer')</h5>
                                <small class="text-muted">@lang('Customer and Shipping Information')</small>
                            </div>

                            <div class="summary_wrapper">
                                <div class="inner_wrapper">
                                    <h6 class="title">
                                        <i class="lar la-user-circle"></i> @lang('Customer Information')
                                    </h6>
                                    <div class="details">
                                        <li>
                                            @lang('Name'): <a href="{{ route('admin.users.detail', $order->user_id) }}" class="user_link">{{ $order->user->fullname }}</a>
                                        </li>
                                        <li>
                                            @lang('Email'): {{ $order->user->email }}
                                        </li>
                                        <li>
                                            @lang('Mobile'): +{{ $order->user->mobileNumber }}
                                        </li>
                                    </div>
                                </div>
                                @php
                                    $shippingAddress = $order->shipping_address ? json_decode($order->shipping_address) : null;
                                @endphp

                                @if ($shippingAddress)
                                    <div class="inner_wrapper mt-10">
                                        <h6 class="title">
                                            <i class="las la-home"></i> @lang('Shipping Address')
                                        </h6>
                                        <div class="details">
                                            <li>@lang('Name'): {{ $shippingAddress->firstname . ' ' . $shippingAddress->lastname }}</li>
                                            <li>@lang('Mobile'): +{{ $shippingAddress->mobile }}</li>
                                            <li>@lang('City'): {{ $shippingAddress->city }}</li>
                                            <li>@lang('State'): {{ $shippingAddress->state }}</li>
                                            <li>@lang('Zip'): {{ $shippingAddress->zip }}</li>
                                            <li>@lang('Address'): {{ $shippingAddress->address }}</li>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($order->status != Status::ORDER_DELIVERED)
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
    @endif

    @if ($order->payment_status == Status::PAYMENT_SUCCESS)
        <div id="refundModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                        <button type="button" class="close p-0" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form method="POST" id="refundForm">
                        @csrf
                        <div class="modal-body">
                            <div class="text-center mb-2">
                                @lang('You have to pay') <span class="refundAmount text--danger fw-bold"></span>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_refunded" value="1" id="is_refunded" required>
                                <label class="form-check-label" for="is_refunded">
                                    @lang('Have you completed the refund process?')
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn--dark h-40" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--primary h-40">@lang('Yes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    @if ($order->payment_status != Status::PAYMENT_INITIATE)
        @if ($order->status == Status::ORDER_PENDING)
            <button type="button" class="btn btn-sm btn-outline--success approveBtn" data-action="{{ Status::ORDER_PROCESSING }}" data-id="{{ $order->id }}">
                <i class="la la-check-double"></i>@lang('Mark as Processing')
            </button>
        @elseif($order->status == Status::ORDER_READY_TO_DELIVER)
            <button type="button" class="btn btn-outline--success approveBtn" data-action="{{ Status::ORDER_DISPATCHED }}" data-id="{{ $order->id }}">
                <i class="la la-check-double"></i>@lang('Mark as Dispatched')
            </button>
        @elseif($order->status == Status::ORDER_DISPATCHED)
            <button type="button" class="btn btn-outline--success approveBtn" data-action="{{ Status::ORDER_DELIVERED }}" data-id="{{ $order->id }}">
                <i class="la la-check-double"></i>@lang('Mark as Delivered')
            </button>
        @endif

        @if ($order->status == Status::ORDER_PENDING)
            <button type="button" class="btn btn-sm btn-outline--danger approveBtn" data-action="{{ Status::ORDER_CANCELED }}" data-id="{{ $order->id }}">
                <i class="la la-ban"></i>@lang('Cancel')
            </button>
        @elseif($order->status == Status::ORDER_CANCELED && $order->subOrders->where('status', '!=', Status::SUBORDER_REJECTED)->count())
            <button type="button" class="btn btn-sm btn-outline--dark approveBtn" data-action="0" data-id="{{ $order->id }}"><i class="la la-reply"></i>@lang('Retake')</button>
        @endif
    @endif

    <x-back route="{{ route('admin.order.all') }}" />
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

            $('.refundBtn').on('click', function() {
                const modal = $('#refundModal');
                let data = $(this).data();
                let url = "{{ route('admin.order.suborder.refund', ':id') }}".replace(':id', data.suborder_id);

                modal.find('.refundAmount').text(data.amount);
                modal.find('#refundForm').attr('action', url);
                modal.modal('show');
            });

            $('.refundOrderBtn').on('click', function() {
                const modal = $('#refundModal');
                let data = $(this).data();
                let url = "{{ route('admin.order.refund', $order->id) }}";

                modal.find('.refundAmount').text("{{ showAmount($order->total_amount) }}");
                modal.find('#refundForm').attr('action', url);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        .status_box_wrapper {
            width: 100%;
            overflow-x: auto;
            padding: 16px;
            background: #fafafa;
        }

        .status_box_container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: nowrap;
        }

        .status_box {
            flex: 1;
            min-width: 180px;
            background: #fff;
            padding: 16px;
            text-align: center;
            border-radius: 5px;
        }

        .status_box .icon {
            font-size: 24px;
            margin-bottom: 5px;
            color: #5b6e88;
        }

        .status_box .title {
            display: block;
            font-weight: 600;
        }

        .status_box .bar {
            display: block;
            height: 5px;
            background: #ddd;
            margin-top: 10px;
            border-radius: 5px;
        }

        .status_box .bar.complete {
            background-color: rgba(40, 199, 111);
        }

        .summary_wrapper {
            padding: 10px;
            background-color: #fafafa;
            border-radius: 5px;
            font-size: 13px;
        }

        .summary_wrapper .total_amount {
            border-top: 1px solid #dfdfdf;
            padding-top: 5px;
        }

        .summary_wrapper .inner_wrapper {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
        }

        .summary_wrapper .inner_wrapper .title {
            padding-bottom: 5px;
        }

        .summary_wrapper .inner_wrapper .details {
            padding-left: 22px;
        }

        .summary_wrapper .inner_wrapper .details li:not(:last-child) {
            margin-bottom: 4px;
        }

        .user_link {
            color: #5b6e88;
            text-decoration: underline;
        }

        .user_link:hover,
        .refund_link:hover {
            color: #324663;
            text-decoration: underline;
        }

        .order-table tbody tr.odd {
            background: #fdfdfd;
        }

        .order-table tr td:not(:last-child),
        .order-table tr th:not(:last-child) {
            text-align: center !important;
        }

        .order-table.table td {
            white-space: nowrap !important;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .product-item .thumb {
            width: 48px;
            flex-shrink: 0;
            border-radius: 5px;
            overflow: hidden;
        }

        .product-item .content {
            flex: 1;
            text-align: left;
        }

        .product-item .name {
            color: #3d3d3d;
            margin-bottom: 8px;
            display: block;
            line-height: 1;
        }

        .product-item .variants {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .product-item .variants li {
            font-size: 12px;
            background-color: #fafafa;
            border: 1px solid rgb(0 0 0 / 10%);
            border-radius: 16px;
            line-height: 1;
            padding: 3px 8px;
        }

        .order-table.table td:has(.seller-info) {
            position: relative;
        }

        .seller-info .badge {
            position: absolute;
            top: 12px;
            right: 12px;
            border-radius: 3px;
        }

        .seller-info .name {
            color: #3d3d3d;
        }

        .seller-info .link,
        .refund_link {
            font-size: 0.75rem;
            color: #324663;
            text-decoration: underline;
        }

        .order_info p {
            font-size: 12px !important;
        }
    </style>
@endpush
