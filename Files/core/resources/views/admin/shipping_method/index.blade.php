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
                                    <th>@lang('Name')</th>
                                    <th>@lang('Deliver In')</th>
                                    <th>@lang('Charge')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shippingMethods as $shippingMethod)
                                    <tr>
                                        <td>{{ __($shippingMethod->name) }}</td>
                                        <td>{{ $shippingMethod->shipping_time }} @lang('Days')</td>
                                        <td>{{ showAmount($shippingMethod->charge) }}</td>
                                        <td>
                                            @php echo $shippingMethod->statusBadge @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.shipping.methods.edit', $shippingMethod->id) }}" class="btn btn-sm btn-outline--primary cuModalBtn">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>

                                                @if ($shippingMethod->status == Status::ENABLE)
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this method?')" data-action="{{ route('admin.shipping.methods.status', $shippingMethod->id) }}"><i class="las la-eye-slash"></i>@lang('Disable')</button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this method?')" data-action="{{ route('admin.shipping.methods.status', $shippingMethod->id) }}"><i class="las la-eye"></i>@lang('Enable')</button>
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

                    @if ($shippingMethods->hasPages())
                        <div class="card-footer py-4">
                            {{ paginateLinks($shippingMethods) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.shipping.methods.create') }}" class="btn btn-sm btn-outline--primary">
        <i class="la la-plus"></i> @lang('Add New')
    </a>
@endpush
