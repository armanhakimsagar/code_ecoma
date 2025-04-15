@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Order Number') | @lang('Date')</th>
                                    <th>@lang('Suborder Number')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Products')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subOrders as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.order.details', $item->order_id) }}">#{{ @$item->order->order_number }}</a> <br>
                                            <small>{{ showDateTime($item->created_at) }}</small>
                                        </td>

                                        <td>
                                            <span class="fw-bold">#{{ @$item->order_number }}</span>
                                        </td>

                                        <td>
                                            {{ showAmount($item->total_amount) }}
                                        </td>

                                        <td>
                                            {{ $item->orderDetail->sum('quantity') }}
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--dark confirmationBtn" data-question="@lang('Are you sure the order has been picked up?')" data-action="{{ route('admin.suborder.mark.as.picked.up', $item->id) }}"><i class="las la-luggage-cart"></i>@lang('Picked Up')</button>
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

                @if ($subOrders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($subOrders) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Order ID" />
@endpush
