@extends('Template::layouts.frontend')
@section('content')
    <div class="padding-bottom padding-top">
        <div class="container">
            <form action="{{ route('user.deposit.insert') }}" method="post" class="deposit-form">
                @csrf
                <input type="hidden" name="currency">
                <div class="row gy-4">
                    <div class="col-lg-8">
                        <div class="payment-option">
                            <h6 class="payment-option__title mb-4">@lang('Select a Payment Option')</h6>
                            <div class="payment-option__wrapper">
                                <div class="payment-option-wrapper">
                                    @foreach ($gatewayCurrency as $item)
                                        <label for="data-{{ $loop->index }}" class="payment-option-item">
                                            <div class="form--radio d-flex">
                                                <input value="{{ $item->method_code }}" id="data-{{ $loop->index }}" data-gateway="{{ $item }}" class="online_payment form-check-input mt-0" type="radio" name="gateway"  required>
                                            </div>

                                            <span class="payment-option-item-content">
                                                <span class="thumb">
                                                    <img src="{{ getImage(getFilePath('gateway') . '/' . @$item->method->image, getFileSize('gateway')) }}" data-src="{{ getImage(getFilePath('gateway') . '/' . @$item->method->image, getFileSize('gateway')) }}" class="w-100 lazyload" alt="image">
                                                </span>
                                                <span class="payment-name">
                                                    {{ __($item->name) }}
                                                </span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="payment-details w-100">
                            <h6 class="title">@lang('Payment Details')</h6>
                            <ul class="gateway-info">
                                <li>
                                    <span class="subtitle">@lang('Total Amount')</span>
                                    <span>{{ showAmount($order->total_amount) }}</span>
                                </li>

                                <li class="deposit-info">
                                    <span>@lang('Processing Charge')
                                        <span data-bs-toggle="tooltip" title="@lang('Gateway Processing Charge')" class="processing-fee-info"><i class="las la-info-circle"></i> </span>
                                    </span>
                                    <span>
                                        <span class="processing-fee text--color">@lang('0.00')</span>
                                        {{ __(gs('cur_text')) }}
                                    </span>
                                </li>

                                <li class="deposit-info total-amount">
                                    <span>@lang('Total')</span>
                                    <span>
                                        <span class="final-amount text--color">
                                            <span class="cl-title" id="total">{{ showAmount($order->total_amount, currencyFormat: false) }}</span>
                                        </span>
                                        {{ __(gs('cur_text')) }}
                                    </span>
                                </li>
                            </ul>

                            <p class="gateway-conversion mb-0 d-none">
                                <span>@lang('Rate') </span>
                                <span class="exchange_rate fw-semibold"><span class="text"></span></span>
                            </p>

                            <p class="conversion-currency bg-light p-3 my-3 rounded-1 d-none">
                                <span>@lang('The final payable amount is')</span>
                                <span class="whitespace-nowrap">
                                    <strong class="in-currency fw-semibold"></strong> <strong class="gateway-currency fw-semibold"></strong>
                                </span>
                            </p>

                            <p class="crypto-message text-muted">
                                <i class="la la-info-circle"></i>
                                <span> @lang('Conversion with') <span class="gateway-currency text--color"></span> @lang('and final value will Show on next step')</span>
                            </p>

                            <button type="submit" class="btn btn--base w-100">@lang('Pay Now')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function() {
            "use strict";
            $('[name=gateway]').on('change', function() {
                let gateway = $(this).data('gateway');
                $('[name=currency]').val(gateway.currency);


                var processingFeeInfo = `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`;

                $(".processing-fee-info").attr("data-bs-original-title", processingFeeInfo);
                calculation(gateway);

            });

            function calculation(gateway) {

                let percentCharge = 0;
                let fixedCharge = 0;
                let totalPercentCharge = 0;
                let amount = @json($order->total_amount);

                if (amount) {
                    percentCharge = parseFloat(gateway.percent_charge);
                    fixedCharge = parseFloat(gateway.fixed_charge);
                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);
                }

                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
                let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(2));
                $(".processing-fee").text(totalCharge.toFixed(2));


                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
                    $(".button[type=submit]").attr('disabled', true);
                } else {
                    $(".button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {

                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                    $(".gateway-conversion").find('.exchange_rate .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                    $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ?
                        8 : 2))
                } else {
                    $(".gateway-conversion, .conversion-currency").addClass('d-none');
                    $('.deposit-form').removeClass('adjust-height')
                }

                if (gateway.method.crypto == 1) {
                    $('.crypto-message').removeClass('d-none');
                } else {
                    $('.crypto-message').addClass('d-none');
                }
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .payment-option-wrapper {
            max-height: 540px;
            overflow-y: auto;
            display: flex;
            justify-content: flex-start;
            gap: 16px;
            flex-wrap: wrap;
            align-content: start;
        }

        @media (max-width: 1399px) {
            .payment-option-wrapper {
                max-height: 350px;
            }
        }

        @media (max-width: 767px) {
            .payment-option-wrapper {
                max-height: 290px;
            }
        }

        .payment-option-wrapper::-webkit-scrollbar {
            width: 0;
        }

        .payment-option-item {
            --border: 0 0% 90%;
            position: relative;
            cursor: pointer;
            margin-bottom: 0px;
            display: flex;
            align-items: center;
            gap: 12px;
            width: calc(50% - 8px);
            padding: 16px 20px;
            border-radius: 6px;
            border: 1px solid hsl(var(--border));
        }

        @media (max-width: 575px) {
            .payment-option-item {
                width: 100%;
            }
        }

        .payment-name {
            --black-h: 0;
            --black-s: 0%;
            --black-l: 0%;
            --black: var(--black-h) var(--black-s) var(--black-l);
            color: hsl(var(--black) / .6);
            font-weight: 500;
            user-select: none;
        }

        .payment-option-item .payment-option-item-content {
            font-size: 0.75rem;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            flex-direction: row-reverse;
        }

        .payment-option {
            --border: 0 0% 90%;
            border: 1px solid hsl(var(--border));
            padding: 30px;
            border-radius: 5px;
            background: #fff;
        }

        @media (max-width: 767px) {
            .payment-option {
                padding: 20px;
            }
        }

        .payment-option-item-content .thumb {
            display: inline-block;
        }

        .payment-option-item-content .thumb img {
            max-width: 80px;
            border-radius: 5px;
            max-height: 36px;
        }

        .payment-details {
            border-radius: 5px;
        }

        .form--radio .form-check-input {
            box-shadow: none;
        }

    </style>
@endpush
