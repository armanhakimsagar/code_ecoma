@extends('seller.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Withdraw Via') {{ $withdraw->method->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary">
                        <p class="p-2"><i class="las la-info-circle"></i> @lang('You are requesting') <b>{{ showAmount($withdraw->amount) }}</b> @lang('for withdraw.') @lang('The admin will send you')
                            <b class="text--success">{{ showAmount($withdraw->final_amount, currencyFormat: false) . ' ' . $withdraw->currency }} </b> @lang('to your account.')
                        </p>
                    </div>
                    <form action="{{ route('seller.withdraw.submit') }}" class="disableSubmission" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            @php
                                echo $withdraw->method->description;
                            @endphp
                        </div>
                        <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />
                        @if (seller()->ts)
                            <div class="form-group">
                                <label>@lang('Google Authenticator Code')</label>
                                <input type="text" name="authenticator_code" class="form-control form--control" required>
                            </div>
                        @endif
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
