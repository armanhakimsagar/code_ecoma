@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Product') | @lang('Seller')</th>
                                    <th>@lang('Order ID')</th>
                                    <th>@lang('Quantity')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Commission Percent')</th>
                                    <th>@lang('Commission Amount')</th>
                                    <th>@lang('Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <div>
                                                {{ strLimit(@$log->product->name, 30) }}
                                                <br>
                                                <a href="{{ route('admin.sellers.detail', $log->seller_id) }}">{{ $log->seller->username }}</a>
                                            </div>
                                        </td>
                                        <td>{{ $log->order_id }}</td>
                                        <td>{{ $log->qty }}</td>
                                        <td>{{ showAmount($log->product_price) }}</td>
                                        <td>{{ getAmount($log->product_commission) }}%</td>
                                        <td>{{ gs('cur_sym') }}{{ getAmount(($log->product_price * $log->product_commission) / 100) }}</td>
                                        <td>{{ showDateTime($log->created_at, 'd M, Y') }}</td>
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

                @if ($logs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($logs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Order ID" />
@endpush
