@extends('Template::layouts.frontend')
@section('content')
    <div class="padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card shadow-md border-0">
                        <div class="card-header bg-transparent pt-3">
                            <h5 class="card-title">@lang('Paystack')</h5>
                        </div>
                        <div class="card-body p-5">
                            <form action="{{ route('ipn.' . $deposit->gateway->alias) }}" method="POST" class="text-center">
                                @csrf
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
                                <button type="button" class="btn btn--base w-100 mt-3" id="btn-confirm">@lang('Pay Now')</button>
                                <script src="//js.paystack.co/v1/inline.js" data-key="{{ $data->key }}" data-email="{{ $data->email }}" data-amount="{{ round($data->amount) }}" data-currency="{{ $data->currency }}" data-ref="{{ $data->ref }}" data-custom-button="btn-confirm"></script>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
