@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4 justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="mb-0">@lang('Order Items')</h6>
                    <div class="details">
                        <h6 class="text-muted">@lang('Order No') #{{ $suborder->order_number }}</h6>
                        <small class="text-muted">@lang('Total Products:') {{ $suborder->orderDetail->sum('quantity') }}</small>
                        @php echo $suborder->statusBadge @endphp
                    </div>
                </div>

                <div class="card-body">
                    <div class="order-details-products mb-3">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                    <tr>
                                        <th>@lang('Product')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Quantity')</th>
                                        <th>@lang('Total Price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suborder->orderDetail as $item)
                                        <tr>
                                            <td>
                                                <div class="single-product-item">
                                                    <div class="thumb">
                                                        <img src="{{ getImage(getFilePath('product') . '/thumb_' . $item->product->main_image) }}" alt="product-image">
                                                    </div>

                                                    <div class="content">
                                                        <div class="content-top">
                                                            <div class="content-top-left">
                                                                <span class="title d-block fw-normal">
                                                                    {{ strLimit(__($item->product->name), 60) }}

                                                                    @if ($item->details)
                                                                        @php
                                                                            $details = json_decode($item->details);
                                                                        @endphp
                                                                        @if ($details->variants)
                                                                            @foreach ($details->variants as $variant)
                                                                                <span class="d-block">{{ __($variant->name) }} : <b>{{ __($variant->value) }}</b></span>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ showAmount($item->base_price) }}
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ showAmount($item->total_price) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="text-end">@lang('Total')</td>
                                        <td>{{ showAmount($suborder->total_amount) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($suborder->order->status != Status::ORDER_CANCELED && !in_array($suborder->status, [Status::SUBORDER_DELIVERED, Status::SUBORDER_REJECTED]))
                        <div class="text-end">
                            @if ($suborder->status == Status::SUBORDER_PENDING)
                                <button type="button" class="btn btn-outline--success confirmationBtn" data-question="@lang('Are you sure to mark the order as processing?')" data-action="{{ route('admin.suborder.mark.as.processing', $suborder->id) }}"><i class="las la-check-double"></i>@lang('Mark As Processing')</button>
                            @elseif($suborder->status == Status::SUBORDER_PROCESSING)
                                <button type="button" class="btn btn-outline--success confirmationBtn" data-question="@lang('Are you sure to mark the order as picked up?')" data-action="{{ route('admin.suborder.mark.as.picked.up', $suborder->id) }}"><i class="las la-check-double"></i>@lang('Mark As Picked Up')</button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($suborder->status == Status::SUBORDER_PENDING || $suborder->status == Status::SUBORDER_PROCESSING)
        <x-confirmation-modal />
    @endif
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('seller.order.all') }}" />
@endpush

@push('style')
    <style>
        table.table--light thead th {
            color: #7c7c7c;
            background-color: #fff;
        }

        .single-product-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .single-product-item .thumb {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
            width: 60px;
            height: 60px;
            flex-shrink: 0;
        }

        .order-details-product {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        @media (max-width: 767px) {
            .order-details-products .single-product-item {
                flex-direction: column;
                align-items: flex-end;
            }

            .order-details-products .content-top {
                margin-bottom: 0;
            }
        }
    </style>
@endpush
