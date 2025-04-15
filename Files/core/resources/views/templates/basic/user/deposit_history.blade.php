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
                    <table class="payment-table section-bg">
                        <thead class="bg--base">
                            <tr>
                                <th class="text-white">@lang('Transaction ID')</th>
                                <th class="text-white">@lang('Gateway')</th>
                                <th class="text-white">@lang('Amount')</th>
                                <th class="text-white">@lang('Status')</th>
                                <th class="text-white">@lang('Time')</th>
                                <th class="text-white"> @lang('MORE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($deposits as $k => $deposit)
                                <tr>
                                    <td data-label="#@lang('Trx')">{{ $deposit->trx }}</td>
                                    <td data-label="@lang('Gateway')">{{ __(@$deposit->gateway->name ?? 'Cash On Delivery') }}</td>
                                    <td data-label="@lang('Amount')">
                                        <div>
                                            {{ showAmount(1) }} = {{ showAmount($deposit->rate, currencyFormat: false) }} {{ __($deposit->method_currency) }}
                                            <br>
                                            <strong>{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @php echo $deposit->statusBadge @endphp
                                    </td>
                                    <td data-label="@lang('Time')">
                                        {{ showDateTime($deposit->created_at, 'd M, Y h:i A') }}
                                    </td>

                                    @php
                                        $details = [];
                                        if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000) {
                                            foreach (@$deposit->detail ?? [] as $key => $info) {
                                                $details[] = $info;
                                                if ($info->type == 'file') {
                                                    $details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                                }
                                            }
                                        }
                                    @endphp

                                    <td data-label="@lang('Details')">
                                        @if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000)
                                            <button type="button" class="qv-btn detailBtn btn-sm" data-info="{{ json_encode($details) }}" @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                                <i class="las la-desktop"></i>
                                            </button>
                                        @else
                                            <button type="button" class="qv-btn btn-sm" data-bs-toggle="tooltip" title="@lang('Automatically processed')" disabled>
                                                <i class="las la-check"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty

                                <tr>
                                    <td colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ paginateLinks($deposits) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Detail MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-2">
                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');

                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        } else {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span"><a href="${element.value}"><i class="fa-regular fa-file"></i> @lang('Attachment')</a></span>
                            </li>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);


                modal.modal('show');
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

        })(jQuery);
    </script>
@endpush
