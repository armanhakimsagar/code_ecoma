@extends('seller.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h5 class="mb-3">@lang('Validate Yourself')</h5>
            <div class="card">
                <div class="card-body">


                    <form action="{{route('seller.kyc.submit')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <x-viser-form identifier="act" identifierValue="kyc" />
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
