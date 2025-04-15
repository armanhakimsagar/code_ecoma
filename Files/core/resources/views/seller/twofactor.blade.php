@extends('seller.layouts.app')
@section('panel')
    <div class="row justify-content-center gy-4">
        @if (!seller()->ts)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Add Your Account')</h5>
                    </div>

                    <div class="card-body">
                        <h6 class="mb-3">
                            @lang('Use the QR code or setup key on your Google Authenticator app to add your account.')
                        </h6>

                        <div class="form-group mx-auto text-center">
                            <img class="mx-auto" src="{{ $qrCodeUrl }}" alt="QR">
                        </div>

                        <div class="form-group">
                            <label class="form-label">@lang('Setup Key')</label>
                            <div class="input-group">
                                <input type="text" name="key" value="{{ $secret }}" class="form-control form--control referralURL" readonly>
                                <button type="button" class="input-group-text copytext" id="copyBoard"> <i class="fas fa-copy"></i> </button>
                            </div>
                        </div>

                        <label><i class="fas fa-info-circle"></i> @lang('Help')</label>
                        <p>@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.') <a class="text--base" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">@lang('Download')</a></p>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-6">

            @if (seller()->ts)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Disable 2FA Security')</h5>
                    </div>
                    <form action="{{ route('seller.twofactor.disable') }}" method="POST">
                        <div class="card-body">
                            @csrf
                            <input type="hidden" name="key" value="{{ $secret }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Google Authenticator OTP')</label>
                                <input type="text" class="form-control form--control" name="code" required>
                            </div>
                            <button type="submit" class="btn h-45 btn--primary w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Enable 2FA Security')</h5>
                    </div>
                    <form action="{{ route('seller.twofactor.enable') }}" method="POST">
                        <div class="card-body">
                            @csrf
                            <input type="hidden" name="key" value="{{ $secret }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Google Authenticator OTP')</label>
                                <input type="text" class="form-control form--control" name="code" required>
                            </div>
                            <button type="submit" class="btn h-45 btn--primary w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#copyBoard').on('click', function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .copyInput {
            display: inline-block;
            line-height: 50px;
            position: absolute;
            top: 0;
            right: 0;
            width: 40px;
            text-align: center;
            font-size: 14px;
            cursor: pointer;
            -webkit-transition: all .3s;
            -o-transition: all .3s;
            transition: all .3s;
        }

        .copied::after {
            position: absolute;
            top: 6px;
            right: 12%;
            width: 100px;
            display: block;
            content: "COPIED";
            font-size: 1em;
            padding: 5px 5px;
            color: #fff;
            background-color: #4634ff;
            border-radius: 3px;
            opacity: 0;
            will-change: opacity, transform;
            animation: showcopied 1.5s ease;
        }

        @keyframes showcopied {
            0% {
                opacity: 0;
                transform: translateX(100%);
            }

            50% {
                opacity: 0.7;
                transform: translateX(40%);
            }

            70% {
                opacity: 1;
                transform: translateX(0);
            }

            100% {
                opacity: 0;
            }
        }
    </style>
@endpush
