<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->sitename(__($pageTitle)) }}</title>
    @include('partials.seo')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/owl.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php?color=' . gs('base_color')) }}">
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
    @stack('style-lib')

    @stack('style')
</head>
@php echo loadExtension('google-analytics') @endphp

<body>
    @stack('fbComment')
    @include('Template::partials.preloader')

    @yield('panel')

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/owl.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/nice-select.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/zoomsl.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @include('Template::script.main')

    @stack('script')

    <script>
        'use strict';

        $('.policy').on('click', function() {
            $.get('{{ route('cookie.accept') }}', function(response) {
                $('.cookie__wrapper').removeClass('show');
            });
        });

        setTimeout(() => {
            $('.cookie__wrapper').addClass('show');
        }, 2000);

        let disableSubmission = false;
        $('.disableSubmission').on('submit', function(e) {
            if (disableSubmission) {
                e.preventDefault()
            } else {
                disableSubmission = true;
            }
        });
    </script>
</body>

</html>
