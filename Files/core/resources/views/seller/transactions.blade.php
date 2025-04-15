@extends('seller.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Trx')</th>
                                    <th>@lang('Transacted')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Post Balance')</th>
                                    <th>@lang('Detail')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>

                                        <td>{{ $transaction->trx }}</td>

                                        <td>
                                            {{ showDateTime($transaction->created_at) }}<br>{{ diffForHumans($transaction->created_at) }}
                                        </td>

                                        <td>
                                            <span class="@if ($transaction->trx_type == '+') text--success @else text--danger @endif">
                                                {{ $transaction->trx_type }} {{ showAmount($transaction->amount) }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ showAmount($transaction->post_balance) }}
                                        </td>

                                        <td>{{ __($transaction->details) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($transactions->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($transactions) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Transaction No." />
@endpush
