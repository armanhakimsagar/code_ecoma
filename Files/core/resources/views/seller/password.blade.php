@extends('seller.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-3 col-md-3 mb-30">

            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary align-items-center">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage(getFilePath('sellerProfile') . '/' . $seller->image, getFileSize('sellerProfile')) }}" alt="Image">
                        </div>
                        <div class="ps-3">
                            <h4 class="text--white">{{ __($seller->fullname) }}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Name')
                            <span class="fw-bold">{{ __($seller->fullname) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="fw-bold">{{ $seller->username }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span class="fw-bold">{{ $seller->email }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4 border-bottom pb-2">@lang('Change Password')</h5>

                    <form action="{{ route('seller.password.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>@lang('Password')</label>
                            <input class="form-control" type="password" name="old_password" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('New Password')</label>
                            <input class="form-control @if (gs('secure_password')) secure-password @endif" type="password" name="password" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Confirm Password')</label>
                            <input class="form-control" type="password" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 btn-lg h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('seller.profile') }}" class="btn btn-sm btn-outline--primary"><i class="las la-user"></i>@lang('Profile Setting')</a>
@endpush

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('style')
    <style>
        .list-group-item:first-child {
            border-top-left-radius: unset;
            border-top-right-radius: unset;
        }

        .hover-input-popup {
            position: relative;
        }

        .input-popup {
            display: none;
        }

        .hover-input-popup .input-popup {
            display: block;
            position: absolute;
            bottom: 100%;
            left: 50%;
            width: 280px;
            background-color: #1a1a1a;
            color: #fff;
            padding: 20px;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            -webkit-transition: all 0.3s;
            -o-transition: all 0.3s;
            transition: all 0.3s;
        }

        .input-popup::after {
            position: absolute;
            content: '';
            bottom: -19px;
            left: 50%;
            margin-left: -5px;
            border-width: 10px 10px 10px 10px;
            border-style: solid;
            border-color: transparent transparent #1a1a1a transparent;
            -webkit-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            transform: rotate(180deg);
        }

        .input-popup p {
            padding-left: 20px;
            position: relative;
        }

        .input-popup p::before {
            position: absolute;
            content: '';
            font-family: 'Line Awesome Free';
            font-weight: 900;
            left: 0;
            top: 4px;
            line-height: 1;
            font-size: 18px;
        }

        .input-popup p {
            color: #ffffff;
        }

        .input-popup p.error {
            text-decoration: line-through;
        }

        .input-popup p.error::before {
            content: "\f057";
            color: #ea5455;
        }

        .input-popup p.success::before {
            content: "\f058";
            color: #28c76f;
        }
    </style>
@endpush
