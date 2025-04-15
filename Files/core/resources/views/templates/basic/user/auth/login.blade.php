@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $login_content = getContent('login_page.content', true);
    @endphp

    <section class="account-section padding-bottom padding-top">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="section-header left-style">
                        <h3 class="title">{{ __(@$login_content->data_values->title) }}</h3>
                        <p>{{ __(@$login_content->data_values->description) }}</p>
                    </div>

                    @include($activeTemplate . 'partials.social_login')

                    <form method="POST" action="{{ route('user.login') }}" class="contact-form mb-30-none verify-gcaptcha">
                        @csrf

                        <div class="contact-group">
                            <label for="username">@lang('Username')</label>
                            <div class="multi-group">
                                <input id="username" type="text" name="username" class="form-control w-100" placeholder="@lang('Enter Your Username')" value="{{ old('username') }}" required>
                            </div>
                        </div>

                        <div class="contact-group">
                            <label for="password">@lang('Password')</label>
                            <div class="multi-group">
                                <input id="password" type="password" name="password" class="form-control w-100" placeholder="@lang('Enter Your Password')" required autocomplete="current-password">
                            </div>
                        </div>

                        <x-captcha path="Template::partials" />

                        <div class="contact-group">
                            <div class="d-flex flex-wrap align-items-center w-100 justify-content-end ">
                                <button type="submit" class="cmn--btn m-0 text-white">@lang('Login')</button>
                            </div>
                        </div>
                        <div class="contact-group">
                            <div class="w-100">
                                <div class="d-flex flex-wrap align-items-center justify-content-between">
                                    @if (Route::has('user.register') && gs('registration'))
                                        <span class="acc">@lang('Don\'t have an account')? <a href="{{ route('user.register') }}">@lang('Create An Account')</a></span>
                                    @endif
                                    @if (Route::has('user.password.request'))
                                        <span class="account-alt">
                                            <a href="{{ route('user.password.request') }}">
                                                {{ __('Forgot Password') }}?
                                            </a>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <img src="{{ frontendImage('login_page', @$login_content->data_values->image, '600x840') }}" class="w-100" alt="@lang('login-bg')">
                </div>
            </div>
        </div>
    </section>
@endsection
