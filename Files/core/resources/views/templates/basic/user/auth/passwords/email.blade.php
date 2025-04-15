@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $content = getContent('forgot_password_page.content', true);
    @endphp

    <div class="account-section padding-bottom padding-top">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="section-header left-style">
                        <h3 class="title">{{ __($content->data_values->title) }}</h3>
                        <p>{{ __($content->data_values->description) }}</p>
                    </div>

                    <form method="POST" action="{{ route('user.password.email') }}" class="contact-form mb-30-none verify-gcaptcha">
                        @csrf
                        <div class="contact-group">
                            <label>@lang('Email Or Username')</label>
                            <input type="text" class="" name="value" value="{{ old('value') }}" required autofocus="off">
                        </div>

                        <x-captcha path="Template::partials" />

                        <div class="contact-group">
                            <button type="submit" class="cmn--btn m-0 ms-auto text-white">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
