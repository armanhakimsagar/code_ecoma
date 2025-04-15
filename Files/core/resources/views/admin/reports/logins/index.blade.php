@extends('admin.layouts.app')

@section('panel')
    @push('topBar')
        @include('admin.reports.logins.top_bar')
    @endpush
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Login at')</th>
                                    <th>@lang('IP')</th>
                                    <th>@lang('Location')</th>
                                    <th>@lang('Browser | OS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginLogs as $log)
                                    <tr>
                                        <td>
                                            @if ($log->user_id)
                                                <span class="fw-bold">{{ @$log->user->fullname }}</span>
                                                <br>
                                                <span class="small"> <a href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ @$log->user->username }}</a> </span>
                                            @else
                                                <span class="fw-bold">{{ @$log->seller->fullname }}</span>
                                                <br>
                                                <span class="small"> <a href="{{ route('admin.sellers.detail', $log->seller_id) }}"><span>@</span>{{ @$log->seller->username }}</a> </span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ showDateTime($log->created_at) }} <br> {{ diffForHumans($log->created_at) }}
                                        </td>

                                        <td>
                                            @if ($log->user_id)
                                                <span class="fw-bold">
                                                    <a href="{{ route('admin.report.user.login.ipHistory', [$log->user_ip]) }}">{{ $log->user_ip }}</a>
                                                </span>
                                            @else
                                                <span class="fw-bold">
                                                    <a href="{{ route('admin.report.seller.login.ipHistory', [$log->user_ip]) }}">{{ $log->user_ip }}</a>
                                                </span>
                                            @endif
                                        </td>

                                        <td>{{ __($log->city) }} <br> {{ __($log->country) }}</td>
                                        <td>
                                            {{ __($log->browser) }} <br> {{ __($log->os) }}
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
                @if ($loginLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($loginLogs) }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('breadcrumb-plugins')
    @if (request()->routeIs('admin.report.user.login.history') || request()->routeIs('admin.report.seller.login.history'))
        <x-search-form placeholder="Search Username" dateSearch='yes' />
    @endif
@endpush
@if (request()->routeIs('admin.report.user.login.ipHistory') || request()->routeIs('admin.report.seller.login.ipHistory'))
    @push('breadcrumb-plugins')
        <a href="https://www.ip2location.com/{{ $ip }}" target="_blank" class="btn btn-outline--primary">@lang('Lookup IP') {{ $ip }}</a>
    @endpush
@endif
