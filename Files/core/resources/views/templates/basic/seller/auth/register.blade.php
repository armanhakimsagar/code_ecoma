@php
    $layout = gs('registration') ? 'layouts.frontend' : 'layouts.app';
@endphp
@extends($activeTemplate . $layout)
@if (gs('registration'))
    @section('content')
        @php
            $register_content = getContent('seller_register_page.content', true);
            $pages = getContent('policy_pages.element', false, '', 1);
        @endphp

        <section class="account-section padding-bottom padding-top">
            <div class="contact-thumb rev-side d-none d-lg-block">
                <img src="{{ frontendImage('seller_register_page', @$register_content->data_values->image, '650x980') }}" alt="register-bg">
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-7 offset-lg-5">

                        <div class="section-header left-style">
                            <h3 class="title">{{ __(@$register_content->data_values->title) }}</h3>
                            <p>{{ __(@$register_content->data_values->description) }}</p>
                        </div>

                        <form action="{{ route('seller.register') }}" method="POST" onsubmit="return submitUserForm();">
                            @csrf

                            <div class="contact-group">
                                <label for="firstname">@lang('First Name')</label>
                                <input id="firstname" class="form-control" type="text" name="firstname" value="{{ old('firstname') }}" required>
                            </div>

                            <div class="contact-group">
                                <label for="lastname">@lang('Last Name')</label>
                                <input id="lastname" class="form-control" type="text" name="lastname" value="{{ old('lastname') }}" required>
                            </div>

                            <div class="contact-group">
                                <label for="email">@lang('Email')</label>
                                <input id="email" class="form-control checkUser" type="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="contact-group hover-input-popup">
                                <label for="password">@lang('Password')</label>
                                <div class="multi-group">
                                    <input id="password" class="form-control w-100" type="password" name="password" required class="w-100 @if (gs('secure_password')) secure-password @endif">
                                </div>
                            </div>

                            <div class="contact-group">
                                <label for="password-confirm">@lang('Confirm Password')</label>
                                <div class="multi-group">
                                    <input id="password-confirm" class="form-control w-100" type="password" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <x-captcha path="Template::partials" />

                            @if (gs('agree'))
                                <div class="contact-group">
                                    <div class="multi-group agree-group">
                                        <div class="form-check form--check w-100">
                                            <input type="checkbox" class="form-check-input" name="agree" id="agree" value="checkedValue">
                                            <label for="agree" class="form-check-label">
                                                @lang('I agree with')
                                                @foreach ($pages as $item)
                                                    <span>
                                                        <a href="{{ route('policy.pages', $item->slug) }}" class="text--base">{{ __($item->data_values->title) }}</a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="contact-group">
                                <div class="m--10 d-flex flex-wrap align-items-center w-100 justify-content-between">
                                    <span class="account-alt">@lang('Already have an account?') <a href="{{ route('seller.login') }}">@lang('Login')</a></span>
                                    <button type="submit" id="recaptcha" class="cmn--btn text-white">@lang('Sign Up')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-center">@lang('You already have an account please Sign in ')</h6>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn--dark h-auto text-white" data-bs-dismiss="modal">@lang('Close')</button>
                            <a href="{{ route('seller.login') }}" class="btn btn--base h-auto">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@else
    @section('panel')
        @include($activeTemplate . 'partials.registration_disabled')
    @endsection
@endif

@if (gs('registration'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush

    @push('script')
        <script>
            "use strict";
            (function($) {

                $('.checkUser').on('focusout', function(e) {
                    var url = '{{ route('seller.check.seller') }}';
                    var value = $(this).val();
                    var token = '{{ csrf_token() }}';

                    var data = {
                        email: value,
                        _token: token
                    }

                    $.post(url, data, function(response) {
                        if (response.data != false) {
                            $('#existModalCenter').modal('show');
                        }
                    });
                });
            })(jQuery);
        </script>
    @endpush
@endif
