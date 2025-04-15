@extends('seller.layouts.master')

@section('content')
    <!-- page-wrapper start -->
    @php
        $sidenav = file_get_contents(resource_path('views/seller/partials/sidenav.json'));
    @endphp
    <div class="page-wrapper default-version">
        @include('seller.partials.sidenav')
        @include('seller.partials.topnav')
        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                @include('seller.partials.breadcrumb')
                @yield('panel')
            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>
@endsection
