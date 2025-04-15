@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Discount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Expire Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>{{ __($coupon->coupon_name) }}</td>
                                        <td>{{ $coupon->coupon_code }}</td>
                                        <td>{{ showAmount($coupon->coupon_amount, currencyFormat:false) }} {{ $coupon->discount_type == 1 ? __(gs('cur_text')) : '%' }}</td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="changeStatus" name="top" {{ $coupon->status ? 'checked' : '' }} data-id={{ $coupon->id }}>
                                                <span class="slider round"></span>
                                            </label>

                                        </td>
                                        <td class="{{ $coupon->end_date < now() ? 'text--danger' : '' }}">
                                            {{ showDateTime($coupon->end_date, 'd M, Y') }}
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.coupon.delete', $coupon->id) }}" data-question="@lang('Are you sure to delete this coupon?')">
                                                    <i class="las la-trash-alt"></i>@lang('Delete')
                                                </button>
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
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.coupon.create') }}" class="btn btn-sm btn-outline--primary"> <i class="las la-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.changeStatus').on('change', function() {
                var id = $(this).data('id');
                var mode = $(this).prop('checked');

                var data = {
                    'id': id
                };
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    url: "{{ route('admin.coupon.status') }}",
                    method: 'POST',
                    data: data,
                    success: function(result) {
                        notify('success', result.success);
                    }
                });
            });
        })(jQuery)
    </script>
@endpush
