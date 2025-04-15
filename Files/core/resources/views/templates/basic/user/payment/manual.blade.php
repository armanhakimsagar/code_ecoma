@extends('Template::layouts.frontend')

@section('content')
    <div class="padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card shadow-md border-0">
                        <div class="card-header bg-transparent pt-3">
                            <h5 class="card-title">{{ __($pageTitle) }}</h5>
                        </div>
                        <div class="card-body  ">
                            <form action="{{ route('user.deposit.manual.update') }}" method="POST" class="disableSubmission" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="alert alert-primary">
                                            <p class="mb-0"><i class="las la-info-circle"></i> @lang('You are requesting') <b>{{ showAmount($data['amount']) }}</b> @lang('to deposit.') @lang('Please pay')
                                                <b>{{ showAmount($data['final_amount'], currencyFormat: false) . ' ' . $data['method_currency'] }} </b> @lang('for successful payment.')
                                            </p>
                                        </div>

                                        <div class="mb-3">@php echo  $data->gateway->description @endphp</div>

                                    </div>

                                    <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />

                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn--base w-100">@lang('Pay Now')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .form-group{
            margin-bottom:10px;
        }
    </style>
@endpush
