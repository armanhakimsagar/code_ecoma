@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.coupon.store', $coupon->id ?? 0) }}" method="POST" enctype="multipart/form-data">
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
                                        <label for="coupon_name">@lang('Coupon Name')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <input type="text" class="form-control" id="coupon_name" name="coupon_name" value="{{ isset($coupon) ? $coupon->coupon_name : old('coupon_name') }}" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="coupon_code">@lang('Coupon Code')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="{{ isset($coupon) ? $coupon->coupon_code : old('coupon_code') }}" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="discount_type">@lang('Discount Type')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <select class="form-control" id="discount_type" name="discount_type" required>
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
                                            <input type="number" class="form-control numeric-validation" id="amount" name="amount" value="{{ isset($coupon) ? getAmount($coupon->coupon_amount) : old('amount') }}" required>
                                            <span class="input-group-text" id="basic-addon2">
                                                @if (isset($coupon))
                                                    {{ $coupon->discount_type == 1 ? gs('cur_text') : '%' }}
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
                                        <input type="text" name="start_date" id="start_date" class="form-control" value="{{ isset($coupon) ? $coupon->start_date : old('start_date') }}" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="end_date">@lang('End Date')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <input type="text" name="end_date" id="end_date" class="form-control" value="{{ isset($coupon) ? $coupon->end_date : old('end_date') }}" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="description">@lang('Description')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <textarea class="form-control" name="description" id="description" rows="3">{{ isset($coupon) ? $coupon->description : old('$coupon->description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Usage Restrictions')</h5>
                    </div>
                    <div class="card-body has-select2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row  ">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="minimum_spend">@lang('Minimum Spend')</label>
                                    </div>

                                    <div class="col-lg-10 col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-text"> {{ gs('cur_sym') }}</span>
                                            <input type="number" class="form-control numeric-validation" id="minimum_spend" name="minimum_spend" value="{{ @$coupon->minimum_spend ? getAmount($coupon->minimum_spend) : old('minimum_spend') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="maximum_spend">@lang('Maximum Spend')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-text"> {{ gs('cur_sym') }}</span>
                                            <input type="number" class="form-control numeric-validation" id="maximum_spend" name="maximum_spend" value="{{ @$coupon->maximum_spend ? getAmount($coupon->maximum_spend) : old('maximum_spend') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="categories">@lang('Categories')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <select class="select2 form-control" name="categories[]" id="categories" multiple>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">@lang($category->name)</option>
                                                @php
                                                    $prefix = '--';
                                                @endphp
                                                @foreach ($category->allSubcategories as $subcategory)
                                                    @include('admin.partials.subcategories', ['subcategory' => $subcategory, 'prefix' => $prefix])
                                                    <option value="{{ $subcategory->id }}">
                                                        {{ $prefix }}@lang($subcategory->name)
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="products">@lang('Select Product')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9 position-relative">
                                        <select class="form-control" id="products" name="products[]" multiple>
                                            @if (request()->routeIs('admin.coupon.edit'))
                                                @foreach ($coupon->products as $product)
                                                    <option value="{{ $product->id }}">{{ __($product->name) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="usage_limit_per_coupon">@lang('Usage Limit Per Coupon')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <input name="usage_limit_per_coupon" class="form-control integer-validation" id="usage_limit_per_coupon" value="{{ isset($coupon) ? $coupon->usage_limit_per_coupon : old('usage_limit_per_coupon') }}" type="number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2 col-md-3">
                                        <label for="usage_limit_per_customer">@lang('Usage Limit Per Customer')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <input name="usage_limit_per_customer" class="form-control numeric-validation" id="usage_limit_per_customer" value="{{ isset($coupon) ? $coupon->usage_limit_per_user : old('usage_limit_per_customer') }}" type="number">
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
    <x-back route="{{ route('admin.coupon.index') }}" />
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
                    $('#basic-addon2').text(`{{ __(gs('cur_text')) }}`);
                } else {
                    $('#basic-addon2').text(`%`);
                }
            });

            @if (request()->routeIs('admin.coupon.edit'))
                var categories = @json($coupon->categories->pluck('id'));
                $('#categories').val(categories).trigger('change');

                var products = @json($coupon->products->pluck('id'));
                $('#products').val(products).trigger('change');

                $('#discount_type').val({{ $coupon->discount_type }});
            @endif

            let productDropdownParent = $('#products').parent();
            $('#products').select2({
                ajax: {
                    url: '{{ route('admin.coupon.products') }}',
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
