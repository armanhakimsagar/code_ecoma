@extends('seller.layouts.app')

@section('panel')
    <div class="card">
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Image')</label>
                    </div>
                    <div class="col-md-9">
                        <x-image-uploader name="image" :image="$seller->image" type="sellerProfile" class="w-50" :required="false" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('First Name')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="firstname" value="{{ old('firstname', $seller->firstname) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Last Name')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="lastname" value="{{ old('lastname', $seller->lastname) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Email')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control bg--white" type="email" value="{{ $seller->email }}" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Mobile')</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text">+{{ $seller->dial_code }}</span>
                            <input class="form-control bg--white" type="number" value="{{ old('mobile', $seller->mobile) }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Country')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control bg--white" type="text" value="{{ @$seller->country_name }}" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('State')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="state" value="{{ old('state', $seller->state) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('City')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="city" value="{{ old('city', $seller->city) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Zip Code')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="number" name="zip" value="{{ old('zip', $seller->zip) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Address')</label>
                    </div>

                    <div class="col-md-9">
                        <textarea class="form-control" name="address">{{ old('address', $seller->address) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn h-45 w-100 btn--primary">@lang('Submit')</button>
            </form>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('seller.password') }}" class="btn btn-sm btn-outline--primary"><i class="las la-lock"></i>@lang('Password Setting')</a>
@endpush
