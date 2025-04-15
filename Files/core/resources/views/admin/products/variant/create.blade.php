@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card-body bg--10 py-2 ps-3">
                <h4 class="text-white">@lang('Product Name') : {{ __($productName) }}</h4>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                @if ($currentAttributes->count())
                    <div class="card-header">
                        <h5 class="ml-3 text-danger">
                            @lang('If you add a new type of variant, your previous stock records for this product will be removed.')
                        </h5>
                    </div>
                @endif
                <form action="{{ route('admin.products.variant.store', $productId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="attr_type">
                    <div class="card-body has_select2">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label>@lang('Type')</label>
                                    <select class="form-control select2-basic attrId" name="attr_id" required>
                                        <option selected value="" disabled>@lang('Select One')</option>
                                        @foreach ($attributes as $attr)
                                            <option data-type="{{ $attr->type }}" value="{{ $attr->id }}">{{ $attr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="attr-wrapper"></div>
                        <button type="button" class="btn btn-outline--success add_more d-none"><i class="la la-plus me-0"></i></button>
                    </div>
                    <div class="card-footer">
                        <div class="form-row justify-content-center">
                            <div class="form-group col-xl-12">
                                <button type="submit" class="btn btn-block btn--success">@lang('Add')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row variant-wrapper">
        <div class="col-lg-12 mt-5">
            <h6 class="page-title">@lang('Current Variants')</h6>
        </div>

        @forelse ($currentAttributes as $attr)
            <div class="col-lg-12">
                <div class="card my-3">
                    <div class="card-header border-bottom-0">
                        <h5 class="card-title mb-1">{{ $attr[0]->productAttribute->name }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive--md table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                    <tr>
                                        <th>@lang('S.N.')</th>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Value')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attr as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ __($item->name) }}</td>
                                            <td>
                                                @if ($attr[0]->productAttribute->type == 2)
                                                    <span class="px-3 p-2 w-50 border" style="background-color: #{{ $item->value }}">&nbsp;</span>
                                                @elseif($attr[0]->productAttribute->type == 3)
                                                    <div class="thumbnails d-inline-block">
                                                        <div class="thumb">
                                                            <a href="{{ getImage(getFilePath('attribute') . '/' . @$item->value, getFileSize('attribute')) }}" class="image-popup">
                                                                <img src="{{ getImage(getFilePath('attribute') . '/' . @$item->value, getFileSize('attribute')) }}" alt="@lang('image')">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{ $item->value }}
                                                @endif
                                            </td>
                                            <td>{{ showAmount($item->extra_price) }}</td>
                                            <td>
                                                <div class="button--group">
                                                    @if ($attr[0]->productAttribute->type == 2 || $attr[0]->productAttribute->type == 3)
                                                        <a href="{{ route('admin.products.add-variant-images', $item->id) }}" data-bs-toggle="tooltip" title="@lang('Add Variant Images')" class="btn icon-btn btn--dark"><i class="la la-image me-0"></i>
                                                        </a>
                                                    @endif

                                                    <button type="button" data-item="{{ $item }}" @if ($attr[0]->productAttribute->type == 3) data-image="{{ getImage(getFilePath('attribute') . '/' . $item->value, getFileSize('attribute')) }}" @endif class="btn icon-btn btn--primary editBtn">
                                                        <i class="la la-pencil me-0"></i>
                                                    </button>

                                                    <button type="button" class="btn icon-btn btn--danger confirmationBtn" data-question="@lang('Are you sure to delete this Variant?')" data-action="{{ route('admin.products.variant.delete', $item->id) }}">
                                                        <i class="la la-trash-alt me-0"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-lg-12 mt-3">
                <div class="alert border border--warning" role="alert">
                    <div class="alert__icon bg--warning"><i class="far fa-bell"></i></div>
                    <p class="alert__message">{{ __($emptyMessage) }}</p>

                </div>
            </div>
        @endforelse
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="POST" id="editForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Modal title')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@if (!request()->session()->has('label') && request()->session()->get('label') != 'new')
    @push('breadcrumb-plugins')
        <x-back route="{{ route('admin.products.all') }}" />
    @endpush
@endif

@push('script')
    <script>
        'use strict';
        var itr = 0;
        (function($) {

            $(document).on('click', '.deleteBtn', function() {
                var modal = $('#deleteModal');
                var link = $(this).data('link');
                var form = $('#deleteForm');
                $(form).attr('action', link);
                modal.modal('show');
            });

            $('.image-popup').magnificPopup({
                type: 'image'
            });

            $('.select2-basic').select2({
                dropdownParent: $('.has_select2')
            });

            $(document).on('click', '.add_more', function() {
                var type = $('input[name="attr_type"]').val();
                itr++;
                if (type == 1) {
                    var content = textContent();
                    $(content).appendTo('.attr-wrapper').hide().slideDown('slow');
                } else if (type == 2) {
                    var content = colorContent();
                    $(content).appendTo('.attr-wrapper').hide().slideDown('slow');
                    addSpectrum();
                } else if (type == 3) {
                    var content = imageContent();
                    $(content).appendTo('.attr-wrapper').hide().slideDown('slow');
                }
            });

            $(document).on('change', '.attrId', function() {
                itr = 0;
                var type = $(this).children('option:selected').data('type');
                $('input[name="attr_type"]').val(type);
                if (type == 1) {
                    var content = textContent();
                    $('.attr-wrapper').html('');
                    $(content).appendTo('.attr-wrapper').hide().slideDown('slow');
                } else if (type == 2) {
                    var content = colorContent();
                    $('.attr-wrapper').html('');
                    $(content).appendTo('.attr-wrapper').hide().slideDown('slow');
                    addSpectrum();
                } else if (type == 3) {
                    var content = imageContent();

                    $('.attr-wrapper').html('');
                    $(content).appendTo('.attr-wrapper').hide().slideDown('slow')
                }

                $('.add_more').removeClass('d-none');

                $('.select2-basic').select2({
                    dropdownParent: $(this).parents('.card-body')
                });
            });

            $(document).on('click', '.removeBtn', function() {
                var parent = $(this).parents('.single-attr');
                parent.slideUp('slow', function() {
                    this.remove();
                });
            });

            $(document).on('change', ".profilePicUpload", function() {
                proPicURL(this);
            });

            $('.editBtn').on('click', function() {
                var modal = $('#editModal');
                var item = $(this).data('item');
                modal.find('.modal-title').text(`@lang('Edit -  ${item.product_attribute.name}')`);
                var content = ``;
                if (item.product_attribute.type == 1) {
                    content = `<div class="form-group">
                                <label>@lang('Name')</label>
                                <input type="text" class="form-control" name="name" value="${item.name}" required />
                            </div>

                            <div class="form-group">
                                <label>@lang('Value')</label>
                                <input type="text" class="form-control" name="value" value="${item.value}" required />
                            </div>

                            <div class="form-group">
                                <label>@lang('Price')</label>
                                <input type="text" class="form-control numeric-validation" name="price" value="${item.extra_price}" required />
                            </div>
                        `;
                    modal.find('.modal-body').html(content);

                } else if (item.product_attribute.type == 2) {
                    content = `
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <input type="text" class="form-control" name="name" value="${item.name}" required />
                            </div>
                            <div class="form-group">
                            <label>@lang('Color')</label>
                            <div class="input-group">
                                <span class="input-group-addon ">
                                    <input type='text' class="form-control colorPicker" value="${item.value}"/>
                                </span>
                                <input type="text" class="form-control colorCode" name="value" value="${item.value}" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('Price')</label>
                                <input type="text" class="form-control numeric-validation" name="price" value="${item.extra_price}" required />
                            </div>
                        </div>
                `;
                    modal.find('.modal-body').html(content);
                    addSpectrum();

                } else if (item.product_attribute.type == 3) {

                    var image = $(this).data('image');
                    content = `
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <input type="text" class="form-control" name="name" value="${item.name}" required />
                            </div>
                            <div class="form-group">
                                <div class="payment-method-item">
                                    <label>@lang('Value')</label>
                                    <div class="image-upload-wrapper">
                                        <div class="image-upload-preview" style="background-image: url(${image})">
                                        </div>
                                        <div class="image-upload-input-wrapper">
                                            <input type="file" class="image-upload-input" name="image" id="image-upload-input1" accept=".png, .jpg, .jpeg">
                                            <label for="image-upload-input1" class="bg--primary"><i class="la la-cloud-upload"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('Price')</label>
                                <input type="text" class="form-control numeric-validation" name="price" value="${item.extra_price}" required />
                            </div>
                        `;
                    modal.find('.modal-body').html(content);
                } else {
                    modal.find('.modal-body').html(content);
                }
                var form = document.getElementById('editForm');
                form.action = `{{ route('admin.products.variant.update', '') }}/${item.id}`;
                modal.modal('show');
            });
        })(jQuery);

        function addSpectrum() {
            console.log($('.colorPicker:empty'));

            $('.colorPicker:empty').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });
        }

        function textContent() {
            return `
                    <div class="row single-attr">
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <input type="text" class="form-control" name="text[${itr}][name]" required />
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="form-group">
                                <label>@lang('Value')</label>
                                <input type="text" class="form-control" name="text[${itr}][value]" required />
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <label>@lang('Price')</label>
                            <div class="form-group abs-form-group d-flex justify-content-between">
                                <input type="text" class="form-control numeric-validation" name="text[${itr}][price]" value ="0" required />
                                <button type="button" class="btn btn-outline--danger ms-2 removeBtn abs-button"><i class="la la-minus me-0"></i></button>
                            </div>
                        </div>
                    </div>

            `;
        }

        function colorContent() {
            return `
                <div class="row single-attr">
                    <div class="col-xl-4">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="color[${itr}][name]" required />
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <label>@lang('Color')</label>
                        <div class="input-group">
                            <span class="input-group-text p-0 border-0">
                                <input type='text' class="form-control colorPicker" value="e81f1f"/>
                            </span>
                            <input type="text" class="form-control colorCode" name="color[${itr}][value]" value="e81f1f" required/>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <label>@lang('Price')</label>
                        <div class="form-group abs-form-group d-flex justify-content-between">
                            <input type="text" class="form-control numeric-validation" name="color[${itr}][price]" value ="0" required />
                            <button type="button" class="btn btn-outline--danger ms-2 removeBtn abs-button"><i class="la la-minus me-0"></i></button>
                        </div>
                    </div>
                </div>
            `;
        }

        function imageContent() {
            return `
            <div class="row single-attr">
                <div class="col-xl-4">
                    <div class="form-group">
                        <label>@lang('Name')</label>
                        <input type="text" class="form-control" name="img[${itr}][name]" required />
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="form-group">
                        <label for="inputAttachments">@lang('Value')</label>
                        <input type="file" name="img[${itr}][value]" class="form-control file-upload-field" accept=".png, .jpg, .jpeg" required/>
                    </div>
                </div>
                <div class="col-xl-4">
                    <label>@lang('Price')</label>
                    <div class="form-group abs-form-group d-flex justify-content-between">
                        <input type="text" class="form-control numeric-validation" name="img[${itr}][price]" value ="0" required />
                        <button type="button" class="btn ms-2 btn-outline--danger removeBtn abs-button"><i class="la la-minus me-0"></i></button>
                    </div>
                </div>
            </div>
            `;
        }
    </script>
@endpush

@push('style')
    <style>
        .variant-wrapper table thead th:first-child,
        .variant-wrapper table thead th:last-child {
            border-radius: 0 !important;
        }

        a.icon-btn{
            padding: 3px 8px !important;
        }
    </style>
@endpush
