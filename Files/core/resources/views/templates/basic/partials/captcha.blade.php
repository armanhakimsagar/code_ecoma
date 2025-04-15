@php
    $customCaptcha = loadCustomCaptcha();
    $googleCaptcha = loadReCaptcha();
@endphp

@if ($googleCaptcha)
    <div class="contact-group">
        <div class="multi-group">
            @php echo $googleCaptcha @endphp
        </div>
    </div>
@endif

@if ($customCaptcha)
    <div class="contact-group">
        <div class="multi-group">
            @php echo $customCaptcha @endphp
        </div>
    </div>
    <div class="contact-group">
        <label for="captcha-code">@lang('Captcha')</label>
        <div class="multi-group">
            <input type="text" name="captcha" id="captcha-code" placeholder="@lang('Enter Code')" class="form-control w-100">
        </div>
    </div>
@endif

@if ($googleCaptcha)
    @push('script')
        <script>
            (function($) {
                "use strict"
                $('.verify-gcaptcha').on('submit', function() {
                    var response = grecaptcha.getResponse();

                    if (response.length == 0) {
                        document.getElementById('g-recaptcha-error').innerHTML = '<span class="text--danger">@lang('Captcha field is required.')</span>';
                        return false;
                    }
                    return true;
                });

                window.verifyCaptcha = () => {
                    document.getElementById('g-recaptcha-error').innerHTML = '';
                }
            })(jQuery);
        </script>
    @endpush
@endif
