@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.sellers.shop.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="seller_id" value="{{ $seller->id }}">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Images')</h5>
            </div>
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-xl-3 col-sm-5">
                        <div class="payment-method-item">
                            <label>@lang('Logo')</label>
                            <x-image-uploader type="sellerShopLogo" id="shopLogo" :image="@$shop->logo" class="w-100" :hasSize="false" :required="@$shop->logo ? false : true" :showMessage="false" />
                            <div class="mt-2">
                                <small class="mt-3 text-muted text--small"> @lang('Supported Files:')
                                    <b>@lang('.png'), @lang('.jpeg'), @lang('.jpg').</b> @lang('Maxium upload size 2MB'). @lang('Recommended aspect ratio is') <b>1:1.</b>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 col-xl-9 col-sm-7">
                        <div class="payment-method-item">
                            <label>@lang('Cover Photo')</label>
                            <x-image-uploader type="sellerShopCover" id="shopCoverPhoto" name="cover_image" :image="@$shop->cover" class="w-100" :required="@$shop->cover ? false : true" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Basic Information')</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Shop Name')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="name" value="{{ old('name', @$shop->name) }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Phone')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="number" name="phone" value="{{ old('phone', @$shop->phone) }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Opens at')</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input type="time" class="form-control" placeholder="--:--" name="opening_time" value="{{ old('opening_time') ?? showDateTime(@$shop->opens_at, 'H:i') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Closed at')</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input type="time" class="form-control" value="{{ old('opening_time') ?? showDateTime(@$shop->closed_at, 'H:i') }}" placeholder="--:--" name="closing_time">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Address')</label>
                    </div>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="address" value="{{ old('address', @$shop->address)  }}" required>
                    </div>
                </div>

            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('SEO Contents')</h5>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Meta Title')</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', @$shop->meta_title) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Meta Description')</label>
                    </div>

                    <div class="col-md-9">
                        <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description', @$shop->meta_description) }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>@lang('Meta Keywords')</label>
                    </div>
                    @php
                        if (old('meta_keywords')) {
                            $metaKeywords = old('meta_keywords');
                        } elseif ($shop && $shop->meta_keywords) {
                            $metaKeywords = $shop->meta_keywords;
                        } else {
                            $metaKeywords = null;
                        }
                    @endphp

                    <div class="col-md-9">
                        <select name="meta_keywords[]" class="form-control select2-auto-tokenize" multiple="multiple">
                            @if ($metaKeywords)
                                @foreach ($metaKeywords as $option)
                                    <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                @endforeach
                            @endif
                        </select>

                        <small class="form-text text-muted">
                            <i class="las la-info-circle"></i> @lang('Type , as separator or hit enter among keywords')
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Social Links')</h5>
            </div>
            <div class="card-body">
                @php
                    if (old('social_links')) {
                        $socialLinks = old('social_links');
                    } elseif ($shop && $shop->social_links) {
                        $socialLinks = $shop->social_links;
                    } else {
                        $socialLinks = null;
                    }
                @endphp

                <div class="socials-wrapper">
                    @if ($socialLinks)
                        @foreach ($socialLinks as $key => $item)
                            <div class="socials">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="social_links[{{ $key }}][name]" value="{{ $item['name'] }}" placeholder="@lang('Type Name Here...')" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control iconPicker icon" autocomplete="off" name="social_links[{{ $key }}][icon]" value="{{ $item['icon'] }}" required>
                                                <span class="input-group-text  input-group-addon" data-icon="las la-home" role="iconpicker">@php echo $item['icon'] @endphp</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group d-flex justify-content-between">
                                            <input type="text" class="form-control" name="social_links[{{ $key }}][link]" value="{{ $item['link'] }}" placeholder="@lang('Type Link Here...')" required>
                                            <button type="button" class="btn btn-outline--danger remove-social ms-2"><i class="la la-minus me-0"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <p class="p-2 social-info">@lang('Add social links as you want by clicking the (+) button on the right side.')</p>
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-outline--success add-social "><i class="la la-plus me-0"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
            </div>
        </div>
    </form>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.sellers.detail', $seller->id) }}" />
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/jsTree/style.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {

            let iconPicker = $('.iconPicker');

            if (iconPicker.length > 0) {
                iconPicker.iconpicker({
                    placement: 'top'
                }).on('iconpickerSelected', function(e) {
                    $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
                });
            }

            $('.add-social').on('click', function() {
                var socials = $(document).find('.socials');
                var length = socials.length;

                var content = `<div class="socials">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="social_links[${length}][name]" placeholder="@lang('Type Name Here...')" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control iconPicker-${length} icon" autocomplete="off" name="social_links[${length}][icon]" required>
                                                <span class="input-group-text  input-group-addon" data-icon="las la-home" role="iconpicker"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group d-flex justify-content-between">
                                            <input type="text" class="form-control" name="social_links[${length}][link]" placeholder="@lang('Type Link Here...')" required>
                                            <button type="button" class="btn btn-outline--danger remove-social ms-2"><i class="la la-minus me-0"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                $(content).appendTo('.socials-wrapper').hide().slideDown('slow');

                let iconPicker = $(document).find(`.iconPicker-${length}`);
                iconPicker.iconpicker({
                    placement: 'top'
                }).on('iconpickerSelected', function(e) {
                    $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
                });

                socials = $(document).find('.socials');
                length = socials.length;

                socials = $(document).find('.socials');
                length = socials.length;

                if (length > 0) {
                    $('.remove-social').removeClass('d-none');
                } else {
                    $('.remove-social').addClass('d-none');
                }
            });

            $(document).on('change', '.iconPicker', function(e) {
                $(this).parent().siblings('.icon-name').val(`<i class="${e.icon}"></i>`);
            });

            $(document).on('click', '.remove-social', function() {
                var parent = $(this).parents('.socials');
                parent.slideUp('slow', function(e) {
                    this.remove();
                });
            });


        })(jQuery)
    </script>
@endpush
