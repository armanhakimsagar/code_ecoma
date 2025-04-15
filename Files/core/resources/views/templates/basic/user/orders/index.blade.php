@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="payment-history-section padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-xl-3">
                    <div class="dashboard-menu">
                        @include($activeTemplate . 'user.partials.dp')
                        <ul>
                            @include($activeTemplate . 'user.partials.sidebar')
                        </ul>
                    </div>
                </div>
                <div class="col-xl-9">
                    <table class="payment-table section-bg">
                        <thead class="bg--base">
                            <tr>
                                <th class="text-white">@lang('Order ID')</th>
                                <th class="text-white text-center">@lang('Products')</th>
                                <th class="text-white text-center">@lang('Payment')</th>
                                <th class="text-white text-center">@lang('Order')</th>
                                <th class="text-white text-center">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td data-label="@lang('Order ID')">
                                        <a href="{{ route('user.order', $order->order_number) }}" class="fw-bold text--dark">#{{ $order->order_number }}</a>
                                    </td>

                                    <td data-label="@lang('Total Products')" class="text-center">{{ $order->orderDetail->sum('quantity') }}</td>

                                    <td data-label="@lang('Payment')">
                                        @php echo $order->paymentBadge('badge-capsule') @endphp
                                    </td>

                                    <td data-label="@lang('Order Status')">
                                        @php echo $order->statusBadge('badge-capsule') @endphp
                                    </td>
                                    <td data-label="@lang('Action')" class="text-center">
                                        <a href="{{ route('user.order', $order->order_number) }}" class="qv-btn btn-sm"> <i class="las la-desktop"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ paginateLinks($orders) }}
                </div>
            </div>
        </div>
    </div>
@endsection
