@extends('Template::layouts.frontend')
@section('content')
    <div class="padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-xl-3">
                    <div class="dashboard-menu">
                        @include('Template::user.partials.dp')
                        <ul>
                            @include('Template::user.partials.sidebar')
                        </ul>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" class="register row">
                                @csrf
                                <div class="col-lg-12 mb-20">
                                    <label class="billing-label" for="password">@lang('Current Password')</label>
                                    <input id="password" type="password" class="form-control custom--style" name="current_password" required autocomplete="current-password">
                                </div>

                                <div class="col-lg-12 mb-20 hover-input-popup">
                                    <label class="billing-label" for="password">@lang('Password')</label>
                                    <input id="password" type="password" class="form-control custom--style  @if (gs('secure_password')) secure-password @endif" name="password" required autocomplete="current-password">
                                </div>
                                <div class="col-lg-12 mb-20">
                                    <label class="billing-label" for="confirm_password">@lang('Confirm Password')</label>
                                    <input id="password_confirmation" type="password" class="form-control custom--style" name="password_confirmation" required autocomplete="current-password">
                                </div>

                                <div class="col-md-12 ml-auto text-end mb-20">
                                    <button type="submit" class="bill-button w-unset text-white">@lang('Change Password')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
