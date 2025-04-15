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
                                    <th>@lang('Order ID')</th>
                                    <th>@lang('Customer')</th>
                                    <th>@lang('Total Products')</th>
                                    <th>@lang('Amount')</th>
                                    @if (request()->routeIs('seller.order.all'))
                                        <th>@lang('Status')</th>
                                    @endif
                                    <th>@lang('Ordered At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text--primary">{{ @$item->order_number }}</span>
                                        </td>

                                        <td>
                                            {{ @$item->order?->user?->fullname }}
                                        </td>

                                        <td>
                                            <a href="{{ route('seller.order.details', $item->id) }}">
                                                <span class="badge badge--primary">{{ $item->total_products }}</span>
                                            </a>
                                        </td>

                                        <td>
                                            <b>{{ showAmount($item->total_amount) }}</b>
                                        </td>

                                        @if (request()->routeIs('seller.order.all'))
                                            <td>
                                                @php echo $item->statusBadge @endphp
                                            </td>
                                        @endif

                                        <td>
                                            {{ showDateTime($item->created_at) }}
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end flex-wrap gap-2">

                                                <a href="{{ route('seller.order.details', $item->id) }}" class="btn btn-sm btn-outline--primary"><i class="las la-desktop"></i>@lang('Detail')</a>

                                                @if (request()->routeIs('seller.order.pending'))
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline--dark dropdown-toggle" type="button" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                            @lang('More')
                                                        </button>

                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                            <a href="javascript:void(0)" class="dropdown-item confirmationBtn" data-question="@lang('Are you sure to processing this order?')" data-action="{{ route('seller.order.mark.as.processing', $item->id) }}"><i class="la la-check-double"></i> @lang('Mark As Processing')</a>

                                                            <a href="javascript:void(0)" class="dropdown-item confirmationBtn" data-question="@lang('Are you sure to reject the order?')" data-action="{{ route('seller.order.reject', $item->id) }}"><i class="la la-times-circle"></i> @lang('Reject')</a>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if ($item->status == Status::SUBORDER_PROCESSING)
                                                        <button type="button" class="btn btn-sm btn-outline--dark confirmationBtn" data-question="@lang('Are you sure to mark the order as ready to pickup?')" data-action="{{ route('seller.order.mark.as.ready.to.pickup', $item->id) }}"><i class="las la-check-double"></i>@lang('Mark As Ready to Pickup')</button>
                                                    @endif
                                                @endif
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

                @if ($orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
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
