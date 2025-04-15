@extends('seller.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-12">

            @php
                $kyc = getContent('kyc.content', true);
            @endphp
            @if (seller()->kv == Status::KYC_UNVERIFIED && seller()->kyc_rejection_reason)
                <div class="alert alert-danger" role="alert">
                    <div class="d-flex justify-content-between">
                        <h4 class="alert-heading">@lang('KYC Documents Rejected')</h4>
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#kycRejectionReason">@lang('Show Reason')</button>
                    </div>
                    <p class="mb-0">{{ __(@$kyc->data_values->reject) }} <a href="{{ route('seller.kyc.form') }}">@lang('Click Here to Re-submit Documents')</a>.</p>
                    <a href="{{ route('seller.kyc.data') }}">@lang('See KYC Data')</a>
                </div>
            @elseif(seller()->kv == Status::KYC_UNVERIFIED)
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">@lang('KYC Verification required')</h4>
                    <p class="mb-0">{{ __(@$kyc->data_values->required) }} <a href="{{ route('seller.kyc.form') }}">@lang('Click Here to Submit Documents')</a></p>
                </div>
            @elseif(seller()->kv == Status::KYC_PENDING)
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">@lang('KYC Verification pending')</h4>
                    <p class="mb-0">{{ __(@$kyc->data_values->pending) }} <a href="{{ route('seller.kyc.data') }}">@lang('See KYC Data.')</a></p>
                </div>
            @endif

            <div class="row gy-4">
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('seller.order.all') }}" icon="las la-cart-arrow-down" title="Total Orders" value="{{ $order['all'] }}" bg="primary" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('seller.order.delivered') }}" icon="las la-cart-arrow-down" title="Delivered Orders" value="{{ $order['delivered'] }}" bg="success" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('seller.order.pending') }}" icon="las la-spinner" title="Pending Orders" value="{{ $order['pending'] }}" bg="warning" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('seller.order.processing') }}" icon="las la-cog" title="Processing Orders" value="{{ $order['processing'] }}" bg="info" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('seller.order.ready.to.pickup') }}" icon="las la-truck" title="Ready to Pickup Orders" value="{{ $order['processing'] }}" bg="dark" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('seller.order.rejected') }}" icon="las la-times-circle" title="Rejected Orders" value="{{ $order['processing'] }}" bg="danger" />
                </div>
            </div>
        </div>

        <div class="col-12">
            <h4 class="mb-3">@lang('Sales Log')</h4>
            <div class="row gy-4">
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="2" icon="las la-cart-arrow-down" title="Sale Amount in Last 7 Days" value="{{ showAmount($sale['last_seven_days']) }}" color="purple" overlay_icon="0" />
                </div>

                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="2" icon="las la-cart-arrow-down" title="Sale Amount In Last 15 Days" value="{{ showAmount($sale['last_fifteen_days']) }}" color="dark" overlay_icon="0" />
                </div>

                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="2" icon="las la-cart-arrow-down" title="Sale Amount In Last 30 Days" value="{{ showAmount($sale['last_thirty_days']) }}" color="danger" overlay_icon="0" />
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row gy-4">
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between">
                                <h5 class="card-title">@lang('Withdrawal Report')</h5>
                                <div id="withdrawDatePicker" class="border p-1 cursor-pointer rounded">
                                    <i class="la la-calendar"></i>&nbsp;
                                    <span></span> <i class="la la-caret-down"></i>
                                </div>
                            </div>
                            <div id="withdrawChartArea"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="row gy-4">
                        <div class="col-lg-12 col-sm-6">
                            <div class="widget-three box--shadow2 b-radius--5 bg--white">
                                <div class="widget-three__icon b-radius--rounded bg--success  box--shadow2">
                                    <i class="las la-wallet"></i>
                                </div>
                                <div class="widget-three__content">
                                    <h2 class="numbers">{{ showAmount(seller()->balance) }}</h2>
                                    <p class="text--small">@lang('In Wallet')</p>
                                </div>
                            </div><!-- widget-two end -->
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="widget-three box--shadow2 b-radius--5 bg--white">
                                <div class="widget-three__icon b-radius--rounded bg--primary  box--shadow2">
                                    <i class="las la-clipboard-check"></i>
                                </div>
                                <div class="widget-three__content">
                                    <h2 class="numbers">{{ showAmount($withdraw['total']) }}</h2>
                                    <p class="text--small">@lang('Total Withdrawn')</p>
                                </div>
                            </div><!-- widget-two end -->
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="widget-three box--shadow2 b-radius--5 bg--white">
                                <div class="widget-three__icon b-radius--rounded bg--warning  box--shadow2">
                                    <i class="las la-hourglass-end"></i>
                                </div>
                                <div class="widget-three__content">
                                    <h2 class="numbers">{{ $withdraw['pending'] }}</h2>
                                    <p class="text--small">@lang('Pending Withdrawals')</p>
                                </div>
                            </div><!-- widget-two end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h4 class="mb-3">@lang('Latest Orders')</h4>
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Order Date')</th>
                                    <th>@lang('Customer')</th>
                                    <th>@lang('Order ID')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($latestOrders as $item)
                                    <tr>
                                        <td>
                                            {{ showDateTime($item->created_at, 'd M, Y') }}
                                        </td>

                                        <td>
                                            @if ($item->order->user)
                                                {{ $item->order->user->username }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ @$item->order_number }}
                                        </td>
                                        <td>
                                            <b>{{ showAmount($item->total_amount) }}</b>
                                        </td>

                                        <td>
                                            @php echo $item->statusBadge @endphp
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
            </div>
        </div>
    </div>

    @if (seller()->kv == Status::KYC_UNVERIFIED && seller()->kyc_rejection_reason)
    <div class="modal fade" id="kycRejectionReason">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                    <button type="button" class="close p-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ seller()->kyc_rejection_reason }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        'use strict';

        (function($) {

            const start = moment().subtract(14, 'days');
            const end = moment();

            const dateRangeOptions = {
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                },
                maxDate: moment()
            }

            const changeDatePickerText = (element, startDate, endDate) => {
                $(element).html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
            }

            let salesBarChart = barChart(
                document.querySelector("#withdrawChartArea"),
                @json(__(gs('cur_text'))),
                [{
                    name: 'Withdrawn',
                    data: []
                }],
                [],
            );

            const withdrawChart = (startDate, endDate) => {

                const data = {
                    start_date: startDate.format('YYYY-MM-DD'),
                    end_date: endDate.format('YYYY-MM-DD')
                }

                const url = @json(route('seller.withdraw.chart.data'));

                $.get(url, data,
                    function(data, status) {
                        if (status == 'success') {
                            salesBarChart.updateSeries(data.data);
                            salesBarChart.updateOptions({
                                xaxis: {
                                    categories: data.created_on,
                                }
                            });
                        }
                    }
                );
            }

            $('#withdrawDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#withdrawDatePicker span', start, end));
            withdrawChart(start, end);
            $('#withdrawDatePicker').on('apply.daterangepicker', (event, picker) => withdrawChart(picker.startDate, picker.endDate));
        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        .alert {
            display: block;
            padding: 20px;
            border-radius: 5px;
        }

        .alert-warning {
            border: 1px solid hsla(29, 100%, 53%, 0.50);
        }

        .alert-danger {
            border: 1px solid hsla(0, 83%, 53%, 0.50);
        }

        .alert-info {
            border: 1px solid hsla(203, 89%, 53%, 0.50);
        }
    </style>
@endpush
