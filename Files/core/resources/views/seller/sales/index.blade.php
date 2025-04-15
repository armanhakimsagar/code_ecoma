@extends('seller.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Product Name')</th>
                                    <th>@lang('Order ID')</th>
                                    <th>@lang('Order Quantity')</th>
                                    <th>@lang('Order Price')</th>
                                    <th>@lang('After Commission')</th>
                                    <th>@lang('Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ Str::limit($log->product->name, 30) }}</td>
                                        <td>
                                            <a href="{{ route('seller.order.details', $log->order_id) }}">{{ @$log->order->order_number }}</a>
                                        </td>
                                        <td>{{ $log->qty }}</td>
                                        <td>{{ showAmount($log->product_price) }}</td>
                                        <td>{{ showAmount($log->after_commission) }}</td>
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
    <x-search-form />
@endpush
