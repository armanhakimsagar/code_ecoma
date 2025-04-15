@extends('admin.layouts.app')

@section('panel')

    <div class="row justify-content-center">

        <div class="loader-container text-center d-none">
            <span class="loader">
                <i class="fa fa-circle-notch fa-spin" aria-hidden="true"></i>
            </span>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('admin.products.store', $product->id ?? 0) }}" id="addForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card p-2 has-select2">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Product Information')</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Name')</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" value="{{ $product->name ?? old('name') }}" name="name" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Slug')</label>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $product->slug ?? old('slug') }}" name="slug" required />
                                    <span class="input-group-text btn btn--primary generate-slug d-flex align-items-center">
                                        @lang('Generate')
                                    </span>
                                </div>
                                <small class="slugMessage d-none"></small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Model')</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" value="{{ $product->model ?? old('model') }}" name="model" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Brand')</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2" name="brand_id">
                                    <option selected value="">@lang('Select One')</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ @$brand->id }}" @selected(old('brand_id', @$product->brand_id) == @$brand->id)>
                                            {{ __($brand->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-2 col-md-3">
                                <label for="categories">@lang('Categories')</label>
                            </div>
                            <div class="col-lg-10 col-md-9 select2-parent">
                                <select class="category-select2 form-control" name="categories[]" id="categories" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" data-title="{{ __($category->name) }}">@lang($category->name)</option>
                                        @php
                                            $prefix = '--';
                                        @endphp
                                        @foreach ($category->allSubcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" data-title="{{ __($subcategory->name) }}">
                                                {{ $prefix }}@lang($subcategory->name)
                                            </option>
                                            @include('admin.partials.subcategories', ['subcategory' => $subcategory, 'prefix' => $prefix])
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Base Price')</label>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" class="form-control numeric-validation" name="base_price" value="{{ @$product->base_price ? getAmount($product->base_price) : '' }}" required />
                                    <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 my-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Product Description')</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Description')</label>
                            </div>
                            <div class="col-md-10">
                                <textarea rows="5" class="form-control nicEdit" name="description">@php echo ($product->description)?? '' @endphp</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Summary')</label>
                            </div>
                            <div class="col-md-10">
                                <textarea rows="5" class="form-control" name="summary">{{ $product->summary ?? '' }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card p-2 my-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Extra Descriptions')</h5>
                    </div>
                    <div class="card-body">
                        <div class="extras">
                            @if (isset($product) && $product->extra_descriptions != null)
                                @foreach ($product->extra_descriptions as $item)
                                    <div class="extra">
                                        <div class="d-flex justify-content-end mb-3">
                                            <button type="button" class="btn btn-outline--danger float-right  remove-extra"><i class="la la-minus"></i></button>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <label>@lang('Name')</label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" name="extra[{{ $loop->iteration }}][key]" value="{{ $item['key'] }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <label>@lang('Value')</label>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea class="form-control nicEdit" name="extra[{{ $loop->iteration }}][value]" rows="3"> @php echo $item['value'] @endphp</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <p class="p-2 extra-info">@lang('Add more descriptions as you want by clicking the (+) button on the right side.')</p>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-end">

                                    <button type="button" class="btn btn-outline--success add-extra"><i class="la la-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 my-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Inventory')</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>
                                    @lang('Track Inventory')
                                </label>
                            </div>
                            <div class="col-md-10">
                                <label class="switch">
                                    <input type="checkbox" name="track_inventory" value="1" @checked(old('track_inventory', @$product->track_inventory) == 1)>
                                    <span class="slider round"></span>
                                </label>

                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>
                                    @lang('Show in Frontend')
                                </label>
                            </div>
                            <div class="col-md-10">
                                <label class="switch">
                                    <input type="checkbox" name="show_in_frontend" value="1" @checked(old('show_in_frontend', @$product->show_in_frontend) == 1)>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>
                                    @lang('Has Variants')
                                </label>
                            </div>
                            <div class="col-md-10">
                                <label class="switch">
                                    <input type="checkbox" name="has_variants" value="1" @checked(@$product->has_variants == 1 || old('has_variants') == 1)>
                                    <span class="slider round"></span>
                                </label>

                            </div>
                        </div>

                        <div class="form-group row sku-wrapper">
                            <div class="col-md-2">
                                <label>@lang('Product SKU')</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" value="{{ $product->sku ?? old('sku') }}" name="sku" />
                            </div>
                        </div>
                    </div>
                    @if (request()->routeIs('admin.products.edit'))
                        <div class="card-footer">
                            <h5 class="ml-3 text-danger">@lang('If you change the value of Track Inventory or Has Variants, your previous stock records for this product will be removed.')</h5>
                        </div>
                    @endif
                </div>

                <div class="card p-2 my-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Product Specifications')</h5>
                    </div>
                    <div class="card-body">
                        <div class="specifications-wrapper">
                            @if (isset($product) && $product->specification != null)
                                @foreach ($product->specification as $item)
                                    <div class="specifications">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>{{ $loop->iteration }}</label>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="specification[{{ $loop->iteration }}][name]" placeholder="@lang('Type Name Here...')" value="{{ @$item['name'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group abs-form-group d-flex justify-content-between">

                                                            <input type="text" class="form-control" name="specification[{{ $loop->iteration }}][value]" placeholder="@lang('Type Value Here...')" value="{{ @$item['value'] }}"">
                                                            <button type="button" class="btn btn-outline--danger remove-specification abs-button ms-2"><i class="la la-minus me-0"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <p class="p-2 specification-info">@lang('Add specifications as you want by clicking the (+) button on the right side.')</p>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-outline--success add-specification "><i class="la la-plus me-0"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 my-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('SEO Contents')</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Meta Title')</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', @$product->meta_title) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Meta Description')</label>
                            </div>
                            <div class="col-md-10">
                                <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description', @$product->meta_description) }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Meta Keywords')</label>
                            </div>
                            <div class="col-md-10">
                                <select name="meta_keywords[]" class="form-control select2-auto-tokenize" multiple="multiple">
                                    @if (@$product->meta_keywords)
                                        @foreach ($product->meta_keywords as $option)
                                            <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <small class="form-text text-muted">
                                    <i class="las la-info-circle"></i>
                                    @lang('Type , as separator or hit enter among keywords')
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 my-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Media Contents')</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Main Image')</label>
                            </div>
                            <div class="col-md-10">
                                <x-image-uploader class="w-50" type="product" :image="@$product->main_image" :name="'main_image'" :required="request()->routeIs('admin.products.create')" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Additional Images')</label>
                            </div>
                            <div class="col-md-10">
                                <div class="input-field">
                                    <div class="input-images"></div>
                                    <small class="form-text text-muted">
                                        <i class="las la-info-circle"></i> @lang('You can only upload a maximum of 6 images')</label>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Video')</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="video_link" placeholder="@lang('Type Here')..." value="{{ $product->video_link ?? '' }}" />
                                <small class="form-text text-muted">
                                    <i class="las la-info-circle"></i>
                                    @lang('Only youtube embed link is allowed')
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn w-100 h-45 btn--primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="close ml-auto m-3" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body text-center">
                    <i class="las la-times-circle f-size--100 text--danger mb-15"></i>
                    <h3 class="text--danger mb-15">@lang('Error: Cannot process your entry!')</h3>
                    <p class="mb-15">@lang('You can\'t add more than 6 image')</p>
                    <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('Continue')</button>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    @if (@$product->seller_id)
        @if ($product->status == Status::ENABLE)
            <button type="button" class="confirmationBtn btn btn-sm btn-outline--danger" data-action="{{ route('admin.products.action', $product->id) }}" data-question="@lang('Are you sure to mark as pending?')"><i class="la la-ban"></i> @lang('Mark as Pending')</button>
        @else
            <button type="button" class="confirmationBtn btn btn-sm btn-outline--success" data-action="{{ route('admin.products.action', $product->id) }}" data-question="@lang('Are you sure to mark as pending?')"><i class="las la-check-double"></i> @lang('Approve')</button>
        @endif
    @endif

    <x-back route="{{ route('admin.products.all') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/image-uploader.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.min.css') }}">
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

            $(document).on('input', 'input[name="images[]"]', function() {
                var fileUpload = $("input[type='file']");
                if (parseInt(fileUpload.get(0).files.length) > 6) {
                    $('#errorModal').modal('show');
                }
            });

            var categories = "";
            @if (request()->routeIs('admin.products.edit'))
                @if ($product->categories)
                    categories = @json($product->categories->pluck('id'));
                    categories = categories.length > 0 ? categories : "";
                @endif
            @endif

            let categoriesSelect = $('.category-select2');
            categoriesSelect.val(categories).select2({
                dropdownParent: categoriesSelect.parent('.select2-parent'),
                closeOnSelect: false,
                templateSelection: function(data, container) {
                    return $(data.element).data('title');
                }
            });

            $('.add-specification').on('click', function() {
                var specifications = $(document).find('.specifications');
                var length = specifications.length;
                $('.specification-info').addClass('d-none');
                var content = `<div class="specifications">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>${length+1}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="specification[${length}][name]" placeholder="@lang('Product Information')">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group abs-form-group d-flex justify-content-between">
                                                    <input type="text" class="form-control" name="specification[${length}][value]" placeholder="@lang('Name')">
                                                    <button type="button" class="btn btn-outline--danger remove-specification abs-button ms-2"><i class="la la-minus me-0"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>`;

                $(content).appendTo('.specifications-wrapper').hide().slideDown('slow');

                specifications = $(document).find('.specifications');
                length = specifications.length;

                if (length > 0) {
                    $('.remove-specification').removeClass('d-none');
                } else {
                    $('.remove-specification').addClass('d-none');
                }
            });

            $(document).on('click', '.remove-specification', function() {

                var parent = $(this).parents('.specifications');

                parent.slideUp('slow', function(e) {
                    this.remove();
                });

            });

            $('.add-extra').on('click', function() {
                var extras = $(document).find('.extra');
                var length = extras.length;

                $('.extra-info').addClass('d-none');

                var content = `<div class="extra">
                                    <div class="d-flex justify-content-end mb-3">
                                        <button type="button" class="btn btn-outline--danger float-right  remove-extra"><i class="la la-minus"></i></button>
                                    </div>
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label>@lang('Slug')</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="extra[${length + 1}][key]" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label>@lang('Generate')</label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="extra[${length + 1}][value]" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>`;


                var elm = $(content).appendTo('.extras').hide().slideDown('slow').find(`textarea[name="extra[${length +1}][value]"]`);
                var curSize = elm.length;
                new nicEditor().panelInstance(elm[0]);
                extras = $(document).find('.extra');
                length = extras.length;

                if (length != 0) {
                    $('.remove-extra').removeClass('d-none');
                } else {
                    $('.remove-extra').addClass('d-none');
                }
            });

            $(document).on('click', '.remove-extra', function() {
                var parent = $(this).parents('.extra');
                parent.slideUp('slow', function() {
                    this.remove();
                });
            });

            $("input[name='base_price']").on('click', function() {
                if ($(this).val() == 0) {
                    $(this).val('');
                }
            });

            if ($(document).find('input[name="has_variants"]').prop("checked") == true) {
                $(document).find('.sku-wrapper').hide();
            }

            $('input[name="has_variants"]').on('click', function() {
                if ($(this).prop("checked") == true) {
                    $('.sku-wrapper').hide('slow');
                    $(document).find('input[name="sku"]').val('');

                } else if ($(this).prop("checked") == false) {
                    $('.sku-wrapper').show('slow');
                    $(document).find('input[name="sku"]').val('');
                }
            });

            $('.generate-slug').on('click', function() {
                var name = $('input[name="name"]').val();
                var slug = createSlug(name);
                let oldSlug = $('input[name="slug"]').val();
                if (oldSlug != slug) {
                    $('input[name="slug"]').val(slug);
                    checkSlug(slug);
                }
            });

            $('input[name="slug"]').on('focusout', function() {
                checkSlug($('input[name="slug"]').val());
            });

            function checkSlug(slug) {

                if (!slug) return false;

                $.ajax({
                    url: '{{ route('admin.products.slug.check') }}',
                    type: 'POST',
                    data: {
                        slug: slug,
                        id: '{{ $product->id ?? 0 }}',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('.slugMessage').html(response.message).removeClass('d-none');
                        if (response.status) {
                            $('[type="submit"]').prop('disabled', false);
                            $('.slugMessage').addClass('d-none');
                        } else {
                            $('[type="submit"]').prop('disabled', true);
                            $('.slugMessage').removeClass('d-none').addClass('text--danger');
                        }
                    }
                });
            }

        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        .generate-slug {
            cursor: pointer;
        }
    </style>
@endpush
