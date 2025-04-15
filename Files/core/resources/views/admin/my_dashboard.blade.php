@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="row gy-4">
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.order.all') }}?self_order=1" icon="las la-cart-arrow-down" title="Total Orders" value="{{ $order['all'] }}" bg="primary" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.order.pending') }}?self_order=1" icon="las la-cart-arrow-down" title="Pending Orders" value="{{ $order['pending'] }}" bg="warning" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.order.processing') }}?self_order=1" icon="las la-cart-arrow-down" title="Processing Orders" value="{{ $order['processing'] }}" bg="teal" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.order.dispatched') }}?self_order=1" icon="las la-cart-arrow-down" title="Dispatched Orders" value="{{ $order['dispatched'] }}" bg="info" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.order.delivered') }}?self_order=1" icon="las la-cart-arrow-down" title="Delivered Orders" value="{{ $order['delivered'] }}" bg="success" />
                </div>

                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.order.canceled') }}?self_order=1" icon="las la-comment-slash" title="Cancelled Orders" value="{{ $order['canceled'] }}" bg="danger" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.order.cod') }}?self_order=1" icon="las la-shipping-fast" title="COD Orders" value="{{ $order['cod'] }}" bg="dark" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.products.all') }}?self_product=1" icon="la la-product-hunt" title="Total Products" value="{{ $product['total'] }}" bg="primary" />
                </div>
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.order.sells.log.admin') }}" icon="las la-file-invoice-dollar" title="Total Sold" value="{{ $product['total_sold'] }}" bg="success" />
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
                <div class="col-xl-6 col-lg-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between">
                                <h5 class="card-title">@lang('Sales Report')</h5>
                                <div id="salesDatePicker" class="border p-1 cursor-pointer rounded">
                                    <i class="la la-calendar"></i>&nbsp;
                                    <span></span> <i class="la la-caret-down"></i>
                                </div>
                            </div>
                            <div id="salesChartArea"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12">
                    <div class="card h-100 top-selling-card">
                        <div class="card-body">
                            <h5 class="mb-4">@lang('Top Selling Products')</h5>

                            @forelse($product['top_selling_products']->where('seller_id',0) as $item)
                                @php
                                    if ($item->offer && $item->offer->activeOffer) {
                                        $discount = calculateDiscount($item->offer->activeOffer->amount, $item->offer->activeOffer->discount_type, $item->base_price);
                                    } else {
                                        $discount = 0;
                                    }
                                @endphp

                                <div class="d-flex flex-wrap gap-4 row-gap-2 custom-mb-4">
                                    <div class="product-thumb">
                                        <div class="image-wrapper border">
                                            <a href="{{ route('product.detail', $item->slug) }}" title="@lang('View As Customer')" class="product-img">
                                                <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->main_image, getFileSize('product')) }}" alt="image">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="product-details">
                                        <div class="d-flex justify-content-between flex-wrap gap-4 row-gap-2">
                                            <a href="{{ route('admin.products.edit', $item->id) }}" class="text--primary product-title fw-bold d-inline-block mb-2"><span title="@lang('Edit')" class="text--primary">{{ __($item->name) }}</span></a>
                                            <p class="fw-bold">{{ $item->total }} {{ Str::plural('sale', $item->total) }}</p>

                                        </div>
                                        <p class="summary">{{ __(strLimit($item->summary, 100)) }}</p>
                                        <p class="fw-bold">
                                            @if ($discount > 0)
                                                <del>{{ gs('cur_sym') . getAmount($item->base_price, 2) }}</del>
                                                <span class="me-2">{{ gs('cur_sym') . getAmount($item->base_price - $discount, 2) }}</span>
                                            @else
                                                <span class="me-2">{{ gs('cur_sym') . getAmount($item->base_price, 2) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div><!-- media end-->
                            @empty
                                <h3 class="mt-5 text-center">@lang('No Sale Yet')</h3>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                document.querySelector("#salesChartArea"),
                @json(__(gs('cur_text'))),
                [{
                    name: 'Sales',
                    data: []
                }],
                [],
            );

            const salesChart = (startDate, endDate) => {

                const data = {
                    start_date: startDate.format('YYYY-MM-DD'),
                    end_date: endDate.format('YYYY-MM-DD')
                }

                const url = @json(route('admin.chart.my.sales'));

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

            $('#salesDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#salesDatePicker span', start, end));
            salesChart(start, end);
            $('#salesDatePicker').on('apply.daterangepicker', (event, picker) => salesChart(picker.startDate, picker.endDate));

        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        .image-wrapper {
            overflow: hidden;
            border-radius: 8px;
            object-fit: cover;
            align-items: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 150px;
        }

        .product-title {
            flex: 1 1 400px;
        }

        .product-details {
            flex: 1 1 400px;
        }

        .row-gap-2 {
            row-gap: 8px !important;
        }

        .image-wrapper img {
            object-fit: cover;
            width: 75px;
        }

        .top-selling-card .description-wrapper {
            flex: 1;
        }

        .custom-mb-4:not(:last-child) {
            margin-bottom: 16px !important;
        }
    </style>
@endpush
