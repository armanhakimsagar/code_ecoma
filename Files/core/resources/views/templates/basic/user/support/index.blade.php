@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="payment-history-section padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-xl-3">
                    <div class="dashboard-menu">
                        @include($activeTemplate . 'user.partials.dp')
                        <ul>
                            @include($activeTemplate . 'user.partials.sidebar')
                        </ul>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="text-end mb-3">
                        <a href="{{ route('ticket.open') }}" class="btn btn--base"> <i class="las la-box-open"></i> @lang('Open New Ticket') </a>
                    </div>
                    <table class="payment-table section-bg">
                        <thead class="bg--base">
                            <tr>
                                <th class="text-white">@lang('Subject')</th>
                                <th class="text-white">@lang('Status')</th>
                                <th class="text-white">@lang('Priority')</th>
                                <th class="text-white">@lang('Last Reply')</th>
                                <th class="text-white">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supports as $support)
                                <tr>
                                    <td data-label="@lang('Subject')"> <a href="{{ route('ticket.view', $support->ticket) }}"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>

                                    <td data-label="@lang('Status')">
                                        @php echo $support->statusBadge @endphp
                                    </td>
                                    <td data-label="@lang('Priority')">
                                        @if ($support->priority == Status::PRIORITY_LOW)
                                            <span class="badge badge--dark">@lang('Low')</span>
                                        @elseif($support->priority == Status::PRIORITY_MEDIUM)
                                            <span class="badge  badge--warning">@lang('Medium')</span>
                                        @elseif($support->priority == Status::PRIORITY_HIGH)
                                            <span class="badge badge--danger">@lang('High')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Last Reply')">{{ diffForHumans($support->last_reply) }} </td>

                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('ticket.view', $support->ticket) }}" class="btn-normal-2 btn-sm">
                                            <i class="fa fa-desktop"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="12">@lang('No data found')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ paginateLinks($supports) }}
                </div>
            </div>
        </div>
    </div>
@endsection
