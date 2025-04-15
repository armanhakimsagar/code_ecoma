@if (@gs('socialite_credentials')->linkedin->status || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->google->status == Status::ENABLE)
    @php
        $text = isset($register) ? 'Register' : 'Login';
    @endphp
    <div class="contact-group social-login-group">
        <div class="multi-group">
            <div class="d-flex justify-content-between flex-wrap gap-4 w-100">
                @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
                    <div class="continue-google flex-grow-1">
                        <a href="{{ route('user.social.login', 'google') }}" class="btn w-100 social-login-btn">
                            <span class="google-icon">
                                <img src="{{ asset($activeTemplateTrue . 'images/google.svg') }}" alt="Google">
                            </span> @lang("$text with Google")
                        </a>
                    </div>
                @endif
                @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
                    <div class="continue-facebook flex-grow-1">
                        <a href="{{ route('user.social.login', 'facebook') }}" class="btn w-100 social-login-btn">
                            <span class="facebook-icon">
                                <img src="{{ asset($activeTemplateTrue . 'images/facebook.svg') }}" alt="Facebook">
                            </span> @lang("$text with Facebook")
                        </a>
                    </div>
                @endif
                @if (@gs('socialite_credentials')->linkedin->status == Status::ENABLE)
                    <div class="continue-facebook flex-grow-1">
                        <a href="{{ route('user.social.login', 'linkedin') }}" class="btn w-100 social-login-btn">
                            <span class="facebook-icon">
                                <img src="{{ asset($activeTemplateTrue . 'images/linkdin.svg') }}" alt="Linkedin">
                            </span> @lang("$text with Linkedin")
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="contact-group">
        <div class="multi-group justify-content-center">
            <div class="text-center">
                <span>@lang('OR')</span>
            </div>
        </div>
    </div>

    @push('style')
        <style>
            .social-login-btn {
                border: 1px solid #cbc4c459 !important;
                background: #fff;
                color: #000 !important;
                font-size: 0.875rem;
                padding: 10px 20px;
            }

            .social-login-btn:hover,
            .social-login-btn:focus,
            .social-login-btn:active {
                border: 1px solid #346dff;
                background-color: #fff !important;
            }

            .social-login-group {
                margin-bottom: 10px;
            }
        </style>
    @endpush
@endif
