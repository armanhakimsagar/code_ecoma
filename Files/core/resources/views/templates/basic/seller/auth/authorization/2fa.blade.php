@extends('Template::layouts.frontend')
@section('content')
    <div class="account-section padding-bottom padding-top">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <h5 class="pb-1 mb-3 text-center border-bottom">@lang('2FA Verification')</h5>
                        <form action="{{ route('seller.2fa.verify') }}" method="POST" class="submit-form">
                            @csrf
                            @include('Template::partials.verification_code')
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
