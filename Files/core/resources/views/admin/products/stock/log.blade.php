@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('SN.')</th>
                                <th class="text-center">@lang('Quantity')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Created At')</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse($stockLogs as $log)
                                <tr>
                                    <td data-label="@lang('SN.')">{{ $loop->iteration }}</td>
                                    <td data-label="@lang('Quantity')" class="text-center">
                                        <span class="badge badge--{{ $log->quantity > 0 ? 'success' : 'dark' }} ">
                                            {{ sprintf('%02d', $log->quantity) }}
                                        </span>
                                    </td>
                                    <td data-label="@lang('Description')">
                                        {{ __($log->remark()) }}
                                    </td>
                                    <td data-label="@lang('Created At')">{{ showDateTime($log->created_at) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($stockLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($stockLogs) }}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.products.stock.create', $productStock->product->id) }}" />
@endpush
