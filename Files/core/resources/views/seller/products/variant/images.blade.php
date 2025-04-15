@extends('seller.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card-body bg--10 p-3 border-radius-5">
                <h4 class="text-white">@lang('Product Name') : {{ __($product_name) }}</h4>
                <h5 class="text-white">@lang('Attribute Name') : {{ __($variant->name) }}</h5>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-field">
                            @if (count($images))
                                <h5 class="mb-2">@lang('Click inside the box below to add more images')</h5>
                            @endif
                            <div class="input-images"></div>
                            <small class="form-text text-muted">
                                <i class="las la-info-circle"></i> @lang('You can only upload a maximum of 6 images')</label>
                            </small>
                        </div>

                        <button type="submit" class="btn w-100 h-45 btn--primary mt-3">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('seller.products.variant.store', $variant->product->id) }}" />
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/image-uploader.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {

            var dropdownParent = $('.has-select2');

            @if (isset($images))
                let preloaded = @json($images);
            @else
                let preloaded = [];
            @endif

            $('.input-images').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'photos',
                preloadedInputName: 'old',
                maxFiles: 6
            });
        })(jQuery)
    </script>
@endpush
