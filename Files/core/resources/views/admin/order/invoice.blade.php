@extends('admin.layouts.app')

@section('panel')
    <div class="content-wrapper">
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-body">
                    <!-- Main content -->
                    <div class="invoice" id="invoice">
                        <!-- title row -->
                        <div class="row mt-3">
                            <div class="col-lg-6">
                                <h4><i class="fa fa-globe"></i> {{ __(gs('sitename')) }} </h4>
                            </div>
                            <div class="col-lg-6">
                                <h5 class="text-end">{{ showDateTime($order->created_at, 'd/M/Y') }}</h5>
                            </div>
                        </div>
                        <hr>
                        <div class="row invoice-info">
                            <div class="col-md-4">
                                <h5 class="mb-2">@lang('User Details')</h5>
                                <address>
                                    <ul>
                                        <li>@lang('Name'): <strong>{{ @$order->user->fullname }}</strong></li>
                                        <li>@lang('Address'): {{ @$order->user->address->address }}</li>
                                        <li>@lang('State'): {{ @$order->user->address->state }}</li>
                                        <li>@lang('City'): {{ @$order->user->address->city }}</li>
                                        <li>@lang('Zip'): {{ @$order->user->address->zip }}</li>
                                        <li>@lang('Country'): {{ @$order->user->address->country }}</li>
                                    </ul>

                                </address>
                            </div><!-- /.col -->
                            <div class="col-md-4 text-center">
                                <h5 class="mb-2">@lang('Shipping Address')</h5>
                                @php
                                    $shipping_address = json_decode($order->shipping_address);
                                @endphp

                                <address>
                                    <ul>
                                        <li>@lang('Name'): <strong>{{ $order->user->firstname }} {{ $order->user->lastname }}</strong></li>
                                        <li>@lang('Address'): {{ $shipping_address->address }}</li>
                                        <li>@lang('State'): {{ $shipping_address->state }}</li>
                                        <li>@lang('City'): {{ $shipping_address->city }}</li>
                                        <li>@lang('Zip'): {{ $shipping_address->zip }}</li>
                                        <li>@lang('Country'): {{ $shipping_address->country }}</li>
                                    </ul>
                                </address>
                            </div><!-- /.col -->

                            <div class="col-md-4 text-end">
                                <b>@lang('Order ID'):</b> {{ $order->order_number }}<br>
                                <b>@lang('Order Date'):</b> {{ showDateTime($order->created_at, 'd/m/Y') }} <br>
                                <b>@lang('Total Amount'):</b> {{ showAmount($order->total_amount) }}
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                        <!-- Table row -->

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>@lang('SN.')</th>
                                            <th>@lang('Product')</th>
                                            <th>@lang('Variants')</th>
                                            <th>@lang('Discount')</th>
                                            <th>@lang('Quantity')</th>
                                            <th>@lang('Price')</th>
                                            <th>@lang('Total Price')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subtotal = 0;
                                        @endphp
                                        @foreach ($order->orderDetail as $data)
                                            @php
                                                $details = json_decode($data->details);
                                                $offer_price = $details->offer_amount;
                                                $extra_price = 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->product->name }}</td>
                                                <td>
                                                    @if ($details->variants)
                                                        @foreach ($details->variants as $item)
                                                            <span class="d-block">{{ __($item->name) }} : <b>{{ __($item->value) }}</b></span>
                                                            @php $extra_price += $item->price;  @endphp
                                                        @endforeach
                                                    @else
                                                        @lang('N/A')
                                                    @endif
                                                </td>
                                                @php $base_price = $data->base_price + $extra_price @endphp
                                                <td class="text-end">{{ gs('cur_sym') . getAmount($offer_price) }}/ @lang('Item')</td>
                                                <td class="text-center">{{ $data->quantity }}</td>
                                                <td class="text-end">{{ gs('cur_sym') . ($data->base_price - getAmount($offer_price)) }}</td>

                                                <td class="text-end">{{ gs('cur_sym') . getAmount(($base_price - $offer_price) * $data->quantity) }}</td>
                                                @php $subtotal += ($base_price - $offer_price) * $data->quantity @endphp
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <div class="row mt-4">
                            <!-- accepted payments column -->
                            <div class="col-lg-6">
                                @if (isset($order->deposit) && $order->deposit->status != 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th width="50%">@lang('Payment Method')</td>
                                                    <td width="50%">
                                                        @if ($order->deposit->method_code == 0)
                                                            <span data-s-toggle="tooltip" title="@lang('Cash On Delivery')">@lang('COD')</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>@lang('Payment Charge')</td>
                                                    <td>{{ gs('cur_sym') . ($charge = getAmount(@$order->deposit->charge)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('Total Payment Amount') </td>
                                                    <td>{{ gs('cur_sym') . getAmount($order->deposit->amount + $charge) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                            </div><!-- /.col -->
                            <div class="col-lg-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="50%">@lang('Subtotal')</th>
                                                <td width="50%">{{ gs('cur_sym') . getAmount($subtotal, 2) }}</td>
                                            </tr>
                                            @if ($order->appliedCoupon)
                                                <tr>
                                                    <th>(<i class="la la-minus"></i>) @lang('Coupon') ({{ $order->appliedCoupon->coupon->coupon_code }})</th>
                                                    <td> {{ gs('cur_sym') . getAmount($order->appliedCoupon->amount, 2) }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th>(<i class="la la-plus"></i>) @lang('Shipping')</th>
                                                <td>{{ gs('cur_sym') . getAmount($order->shipping_charge, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Total')</th>
                                                <td>{{ gs('cur_sym') . getAmount($order->total_amount) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                        <!-- this row will not appear when printing -->
                    </div><!-- /.content -->
                    <div class="no-print mt-3 text-end">
                        <a href="{{ route('admin.order.invoice.print', $order->id) }}" target=blank class="btn btn--dark"><i class="las la-print"></i>@lang('Print')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.order.details', $order->id) }}" />
@endpush
