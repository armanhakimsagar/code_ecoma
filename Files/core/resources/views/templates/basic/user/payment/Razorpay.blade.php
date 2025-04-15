@extends('Template::layouts.frontend')

@section('content')
    <div class="padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card shadow-md border-0">
                        <div class="card-header bg-transparent pt-3">
                            <h5 class="card-title">@lang('Razorpay')</h5>
                        </div>
                        <div class="card-body p-5">
                            <ul class="list-group text-center">
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('You have to pay '):
                                    <strong>{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('You will get '):
                                    <strong>{{ showAmount($deposit->amount) }}</strong>
                                </li>
                            </ul>
                            <form action="{{ $data->url }}" method="{{ $data->method }}">
                                <input type="hidden" custom="{{ $data->custom }}" name="hidden">
                                <script src="{{ $data->checkout_js }}" @foreach ($data->val as $key => $value)
                                data-{{ $key }}="{{ $value }}" @endforeach></script>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('input[type="submit"]').addClass("mt-4 btn btn--base w-100");
        })(jQuery);
    </script>
@endpush
