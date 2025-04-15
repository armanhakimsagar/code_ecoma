@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.offer.store', $offer->id ?? 0) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('General Information')</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="offer_name">@lang('Offer Name') </label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <input type="text" class="form-control" id="offer_name" name="offer_name"" value="{{ isset($offer) ? $offer->name : old('offer_name') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="discount_type">@lang('Discount Type')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <select class="form-control" id="discount_type" name="discount_type">
                                            <option selected value="">@lang('Select One')</option>
                                            <option value="1">@lang('Fixed')</option>
                                            <option value="2">@lang('Percentage')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="amount">@lang('Amount')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="input-group">
                                            <input type="number" class="form-control numeric-validation" id="amount" name="amount" value="{{ isset($offer) ? getAmount($offer->amount) : old('amount') }}">
                                            <span class="input-group-text" id="basic-addon2">
                                                @if (isset($offer))
                                                    {{ $offer->discount_type == 1 ? gs('cur_sym') : '%' }}
                                                @else
                                                    {{ gs('cur_text') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="start_date">@lang('Start Date')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <input type="text" name="start_date" id="start_date" class="form-control" value="{{ isset($offer) ? $offer->start_date : old('start_date') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="end_date">@lang('End Date')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <input type="text" name="end_date" id="end_date" class="form-control" value="{{ isset($offer) ? $offer->end_date : old('end_date') }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card my-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Products')</h5>
                    </div>
                    <div class="card-body has-select2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="products">@lang('Select Product')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9 position-relative">
                                        <select class="form-control" id="products" name="products[]" multiple required>
                                            @if (request()->routeIs('admin.offer.edit'))
                                                @foreach ($offer->products as $product)
                                                    <option value="{{ $product->id }}">{{ __($product->name) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn h-45 w-100 btn--primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.offer.index') }}" />
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            function dateRangePicker(element, minDate = moment().format('YYYY-MM-DD')) {
                $(element).daterangepicker({
                    autoUpdateInput: false,
                    singleDatePicker: true,
                    showDropdowns: true,
                    minDate: minDate,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                });
            }

            function applyDatePicker(element) {
                $(element).on('apply.daterangepicker', (event, picker) => {
                    $(event.target).val(picker.startDate.format('YYYY-MM-DD'));
                });
            }

            // initialize date picker
            dateRangePicker('#start_date');
            dateRangePicker('#end_date', moment().add(1, 'days').format('YYYY-MM-DD'));

            // apply date picker
            applyDatePicker('#start_date');
            applyDatePicker('#end_date');

            $('#discount_type').on('change', function() {
                var val = this.value;
                if (val == 1) {
                    $('#basic-addon2').text(`{{ __(gs('cur_sym')) }}`);
                } else {
                    $('#basic-addon2').text(`%`);
                }
            });

            @if (request()->routeIs('admin.offer.edit'))
                var products = @json($offer->products->pluck('id'));
                $('#products').val(products).trigger('change');
                $('#discount_type').val({{ $offer->discount_type }});
            @endif

            let productDropdownParent = $('#products').parent();
            $('#products').select2({
                ajax: {
                    url: '{{ route('admin.offer.products') }}',
                    type: "get",
                    dataType: 'json',
                    delay: 1000,
                    data: function(params) {

                        return {
                            search: params.term,
                            page: params.page, // Page number, page breaks
                            rows: 5 // How many rows are displayed per page
                        };
                    },
                    processResults: function(response, params) {
                        params.page = params.page || 1;
                        return {
                            results: response,
                            pagination: {
                                more: params.page < response.length
                            }
                        };
                    },
                    cache: false
                },
                dropdownParent: productDropdownParent

            });


        })(jQuery)
    </script>
@endpush
