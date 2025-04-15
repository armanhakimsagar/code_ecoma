@extends('seller.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Ticket ID')</th>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Priority')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Reply')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td>#{{ $ticket->ticket }}</td>

                                        <td>
                                            {{ __($ticket->subject) }}
                                        </td>

                                        <td>
                                            @if ($ticket->priority == Status::PRIORITY_LOW)
                                                <span class="badge badge--dark">@lang('Low')</span>
                                            @elseif($ticket->priority == Status::PRIORITY_MEDIUM)
                                                <span class="badge  badge--warning">@lang('Medium')</span>
                                            @elseif($ticket->priority == Status::PRIORITY_HIGH)
                                                <span class="badge badge--danger">@lang('High')</span>
                                            @endif
                                        </td>

                                        <td>
                                            @php echo $ticket->statusBadge @endphp
                                        </td>

                                        <td>
                                            {{ diffForHumans($ticket->last_reply) }}
                                        </td>

                                        <td>
                                            <a href="{{ route('seller.ticket.view', $ticket->ticket) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="la la-desktop"></i>@lang('View')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($tickets->hasPages())
                    <div class="card-footer">
                        {{ paginateLinks($tickets) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('seller.ticket.open') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Open New Ticket')</a>
@endpush
