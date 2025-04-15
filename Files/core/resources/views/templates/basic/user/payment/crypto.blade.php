@extends('Template::layouts.frontend')

@section('content')
    <div class="padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card shadow-md border-0 card-deposit text-center">
                        <div class="card-header bg-transparent pt-3">
                            <h3>@lang('Payment Preview')</h3>
                        </div>
                        <div class="card-body card-body-deposit text-center">
                            <h4 class="my-2"> @lang('PLEASE SEND EXACTLY') <span class="text--success"> {{ $data->amount }}</span> {{ __($data->currency) }}</h4>
                            <h5 class="mb-2">@lang('TO') <span class="text--success"> {{ $data->sendto }}</span></h5>
                            <img src="{{ $data->img }}" alt="Image">
                            <h4 class="text-white bold my-4">@lang('SCAN TO SEND')</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
