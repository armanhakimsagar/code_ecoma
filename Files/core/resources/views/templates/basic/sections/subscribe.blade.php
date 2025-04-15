@php
    $subscribe = getContent('subscribe.content', true);
@endphp

<section class="newsletter-section bg--base padding-top padding-bottom">
    <div class="container">
        <div class="section-header mb-4">
            <h3 class="title mb-0">@lang(@$subscribe->data_values->text)</h3>
        </div>
        <div class="subscribe-form ml-auto mx-auto">
            <input type="text" placeholder="Enter Your Email Address" class="form-control" name="email">
            <button type="button" class="subscribe-btn">@lang('Subscribe')</button>
        </div>
    </div>
</section>

@push('script')
    <script>
        'use strict';
        (function($) {
            $(document).on('click', '.subscribe-btn', function() {
                subscribe();
            });

            $('input[name="email"]').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    subscribe();
                }
            });

            function subscribe() {
                var email = $('input[name="email"]').val();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ route('subscribe') }}",
                    method: "POST",
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.success) {
                            notify('success', response.success);
                            $('input[name="email"]').val('');
                        } else {
                            notify('error', response);
                        }
                    }
                });
            }
        })(jQuery)
    </script>
@endpush
