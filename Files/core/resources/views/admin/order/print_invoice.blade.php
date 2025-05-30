<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $order->order_number }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
    <link rel="shortcut icon" href="{{siteFavicon() }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    <style>
        address ul li{
            line-height: 1.3;
        }
    </style>
</head>

<body onload="window.print()">
    <!-- Container -->
    <div class="container-fluid invoice-container">
        <!-- Header -->

        <div class="container-fluid p-0">
            <div class="card border-0">
                <div class="card-body">
                    <!-- Main content -->
                    <div class="invoice">
                        <!-- title row -->
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="logo">
                                    <img src="{{ siteLogo('dark') }}" alt="@lang('logo')">
                                </div>
                            </div>
                            <div class="col-6">
                                <b>@lang('Order ID'):</b> {{ $order->order_number }}<br>
                                <b>@lang('Order Date'):</b> {{ showDateTime($order->created_at, 'd/m/Y') }} <br>
                                <b>@lang('Total Amount'):</b> {{ gs('cur_sym') . $order->total_amount }}
                            </div>
                        </div>
                        <hr>
                        <div class="row invoice-info">
                            <div class="col-6">
                                <h5 class="mb-2">@lang('User Details')</h5>
                                <address>
                                    <ul>
                                        <li>@lang('Name'): <strong>{{ @$order->user->fullname }}</strong></li>
                                        <li>@lang('Address'): {{ @$order->user->address }}</li>
                                        <li>@lang('State'): {{ @$order->user->state }}</li>
                                        <li>@lang('City'): {{ @$order->user->city }}</li>
                                        <li>@lang('Zip'): {{ @$order->user->zip }}</li>
                                        <li>@lang('Country'): {{ @$order->user->country }}</li>
                                    </ul>

                                </address>
                            </div><!-- /.col -->
                            <div class="col-6">
                                <h5 class="mb-2">@lang('Shipping Address')</h5>
                                @php
                                    $shippingAddress = json_decode($order->shipping_address);
                                @endphp

                                <address>
                                    <ul>
                                        <li>@lang('Name'): <strong>{{ $order->user->firstname }} {{ $order->user->lastname }}</strong></li>
                                        <li>@lang('Address'): {{ @$shippingAddress->address }}</li>
                                        <li>@lang('State'): {{ @$shippingAddress->state }}</li>
                                        <li>@lang('City'): {{ @$shippingAddress->city }}</li>
                                        <li>@lang('Zip'): {{ @$shippingAddress->zip }}</li>
                                        <li>@lang('Country'): {{ @$shippingAddress->country }}</li>
                                    </ul>
                                </address>
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
                                                            <span data-toggle="tooltip" title="@lang('Cash On Delivery')">@lang('COD')</span>
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
                                                <td width="50%">{{ @gs('cur_sym') . getAmount($subtotal, 2) }}</td>
                                            </tr>
                                            @if ($order->appliedCoupon)
                                                <tr>
                                                    <th>(-) @lang('Coupon') ({{ $order->appliedCoupon->coupon->coupon_code }})</th>
                                                    <td> {{ gs('cur_sym') . getAmount($order->appliedCoupon->amount, 2) }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th>(-) @lang('Shipping')</th>
                                                <td>{{ @gs('cur_sym') . getAmount($order->shipping_charge, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Total')</th>
                                                <td>{{ @gs('cur_sym') . $order->total_amount }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                        <!-- this row will not appear when printing -->
                    </div><!-- /.content -->
                </div>
            </div>
        </div>

    </div>
</body>

</html>
