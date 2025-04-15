@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center gy-4">
        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-5">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button type="reset" class="btn btn--primary addRootCategory flex-grow-1"> <i class="las la-plus"></i> @lang('Add Root Category')</button>

                        <button type="button" class="btn btn-sm btn--success flex-grow-1" id="addChildBtn" disabled><i class="la la-plus"></i> @lang('Add Child ')</button>

                        <button type="button" class="btn btn-sm btn--danger confirmationBtn flex-grow-1" id="deleteChildBtn" disabled data-action="" data-question="@lang('Are you sure to delete this category?')"><i class="las la-trash"></i> @lang('Delete Selected')</button>

                    </div>

                    <input type="text" class="form-control" value="" id="treeSearch" placeholder="@lang('Search')" />
                    <button type="button" class="expand-collapse-btn text-muted text-sm" data-state="close_all">@lang('Expand All')</button>
                    <div id="categoryTree">
                        <ul>
                            @include('admin.category.tree')
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-8 col-xl-7 col-lg-6 col-md-7 col-sm-6">
            <div class="card right-sticky">
                <div class="card-body">
                    <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data" id="addForm">
                        @csrf
                        <input type="hidden" name="parent_id">
                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>
                                    @lang('Image')
                                    <i class="la la-question-circle" title="@lang('This image will be displayed as thumbnails of this category')"></i>
                                </label>
                            </div>
                            <div class="col-xxl-10 col-xl-9 category-thumb">
                                <x-image-uploader type="category" :required="false" />

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Name')</label>
                            </div>
                            <div class="col-xxl-10 col-xl-9">
                                <input type="text" class="form-control" value="{{ old('name') }}" name="name" required>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Icon')</label>
                            </div>
                            <div class="col-xxl-10 col-xl-9 category-icon">
                                <div class="input-group">
                                    <input type="text" class="form-control iconPicker icon" autocomplete="off" name="icon" value="{{ old('icon') }}" required>
                                    <span class="input-group-text  input-group-addon" data-icon="las la-home" role="iconpicker"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Meta Title')</label>
                            </div>
                            <div class="col-xxl-10 col-xl-9">
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Meta Description')</label>
                            </div>

                            <div class="col-xxl-10 col-xl-9">
                                <textarea name="meta_description" rows="3" class="form-control">{{ old('meta_description') }} </textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Meta Keywords')</label>
                            </div>
                            <div class="col-xxl-10 col-xl-9">
                                <select name="meta_keywords[]" class="form-control select2-auto-tokenize" multiple="multiple"></select>
                                <small class="form-text text-muted">
                                    <i class="las la-info-circle"></i>
                                    @lang('Type , or hit enter to separate keywords')
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-2">
                                <label>@lang('Highlight In')</label>
                            </div>
                            <div class="col-md-10">
                                <div class="custom-control custom-checkbox form-check-primary">
                                    <input type="checkbox" name="is_top" value="1" class="custom-control-input" id="is_top">
                                    <label class="custom-control-label" for="is_top">@lang('Top Category')</label>
                                </div>

                                <div class="custom-control custom-checkbox form-check-primary">
                                    <input type="checkbox" name="is_special" value="1" class="custom-control-input" id="is_special">
                                    <label class="custom-control-label" for="is_special">@lang('Special Category')</label>
                                </div>

                                <div class="custom-control custom-checkbox form-check-primary">
                                    <input type="checkbox" name="in_filter_menu" value="1" class="custom-control-input" id="in_filter_menu">
                                    <label class="custom-control-label" for="in_filter_menu">@lang('Filter Menu')</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn--primary h-45 flex-grow-1" id="submitButton">@lang('Submit')</button>
                            <button type="reset" class="btn btn--dark addRootCategory" title="@lang('Clear Form')"> <i class="las la-redo-alt"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal>
        <div class="form-check mb-0 d-flex align-items-cneter gap-2 mt-3">
            <input class="form-check-input" type="checkbox" name="delete_child" id="delete_child" value="1">
            <label class="form-check-label mb-0" for="delete_child">@lang('Delete child categories')</label>
        </div>
    </x-confirmation-modal>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.category.trashed') }}" class="btn btn-outline--danger"><i class="las la-trash-alt"></i>
        @lang('Trashed')</a>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/jsTree/style.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/jstree.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";

            $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });

            const form = $('#addForm');
            const submitButton = $('#submitButton');
            const formAction = `{{ route('admin.category.store', '') }}`;
            const addRootCategoryButton = $('.addRootCategory');
            const addChildButton = $('#addChildBtn');
            const deleteChildButton = $('#deleteChildBtn');


            let selectedNode;
            let timeout = false;


            // Initialization of jstree
            $('#categoryTree').jstree({
                core: {
                    check_callback: (operation, node, parent) => {
                        // Always allow moving child nodes
                        if (operation === "move_node" && node.parent === parent) {
                            return true;
                        }

                        // Allow moving parent nodes to any position except as a child of their descendants
                        if (operation === "move_node" && node.id !== parent.id) {
                            return !$(parent).find(`#${node.id}`).length;
                        }
                    }
                },
                plugins: ["dnd", "search", "unique", "types"]
            });


            const treeSearchKeyupHandler = () => {
                // Clear the previous timeout if exists
                if (timeout) {
                    clearTimeout(timeout);
                }

                // Set a new timeout for 250 milliseconds
                timeout = setTimeout(function() {
                    // Perform the search on the jstree with the entered value
                    $('#categoryTree').jstree(true).search($('#treeSearch').val());
                }, 250);
            }

            const selectNodeHandler = (e, data) => {
                selectedNode = data.node;
                addChildButton.removeAttr('disabled');
                deleteChildButton.removeAttr('disabled');
                deleteChildButton.attr('data-action',
                    `{{ route('admin.category.delete', '') }}/${selectedNode.id}`);
                form.find('.image-upload-input').val('');
                form.find('.select2-auto-tokenize').empty();
                const fieldMappings = ['parent_id', 'name', 'meta_title', 'meta_description', 'is_top', 'is_special', 'in_filter_menu'];

                form.parents('.card').showPreloader();

                $.get(`{{ route('admin.category.get.single', '') }}/${selectedNode.id}`).done((response) => {
                    if (response.category) {
                        const data = response.category;

                        fieldMappings.forEach(field => {
                            const value = data[field];
                            const inputField = form.find(`[name=${field}]`)[0];
                            if (inputField.type === 'checkbox') {
                                $(inputField).prop('checked', value == 1 ? true : false);
                            } else {
                                $(inputField).val(value);
                            }
                        });

                        if (data.meta_keywords) {
                            data.meta_keywords.forEach(item => {
                                form.find('.select2-auto-tokenize').append($('<option>', {
                                    value: item,
                                    text: item,
                                }));
                            });
                        }

                        form.find('[name=icon]').val(data.icon).trigger('change');
                        form.find('[name=icon]').closest('.form-group').find('.input-group-addon').html(data.icon);

                        form.find('.select2-auto-tokenize').val(data.meta_keywords);
                        form.find('.category-thumb .image-upload-preview').css('background-image',
                            `url(${data.image_path})`);
                        form.find('.category-icon .image-upload-preview').css('background-image',
                            `url(${data.icon_path})`);
                        form.attr('action', `${formAction}/${data.id}`);
                    }

                    form.parents('.card').removePreloader();
                });
            }

            const moveNodeHandler = (e, data) => {
                const draggedNode = data.node;
                const newParent = data.parent;

                if (!newParent || draggedNode.id === newParent.id) {
                    return;
                }

                // Update category model in database using AJAX
                $.post("{{ route('admin.category.update.position') }}", {
                    _token: `{{ csrf_token() }}`,
                    category_id: draggedNode.id,
                    parent_id: newParent === "#" ? null : newParent,
                    old_position: data.old_position,
                    position: data.position,
                });
            }

            const submitAddCategoryForm = function(e) {
                e.preventDefault();
                form.parents('.card').showPreloader();
                $.ajax({
                    url: this.action,
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: new FormData(this),
                    success: function(response) {
                        if (response.status == 'error') {
                            notify('error', response.message);
                        } else {
                            let data = response.data;
                            if (data.action === 'updated') {
                                $('#categoryTree').jstree('rename_node', data.categoryId, data.name);
                            } else {
                                $('#categoryTree').jstree('create_node', data.parentId, {
                                    "id": data.categoryId,
                                    "text": data.name,
                                });
                            }
                            notify('success', response.message);
                        }

                        form.parents('.card').removePreloader();
                    }
                });
            }

            const clearFormFields = () => {
                form.attr('action', `${formAction}/0`);
                form.find("input[type=text], textarea, select").val("");
                form.find("[name=parent_id]").val('');
                form.find('input[type=checkbox]').prop('checked', false);
                form.find('.select2-auto-tokenize').empty();
                form.find('.category-thumb .image-upload-preview').css('background-image',
                    `url('{{ getImage(null, getFileSize('category')) }}')`);
            }

            const addChildButtonClickHandler = function() {
                clearFormFields();
                // Update the parent category value in the form
                form.find("[name=parent_id]").val(selectedNode.id);
            }

            const addRootCategoryButtonClickHandler = () => {
                $('#categoryTree').jstree('deselect_all');
                clearFormFields();
            }

            const expandCollapseBtnClickHandler = function() {
                const currentState = $(this).data('state');
                const newState = currentState == 'open_all' ? 'close_all' : 'open_all';
                // Update the text content of the element
                $(this).text(newState == 'open_all' ? 'Collapse All' : 'Expand All');
                // Update the new state in data attribute
                $(this).data('state', newState);
                $('#categoryTree').jstree(newState)

            }

            const deSelectAllNodeHandler = (e, data) => {
                addChildButton.attr('disabled', true);
                deleteChildButton.attr('disabled', true);
            }

            $('#categoryTree').on('deselect_all.jstree', deSelectAllNodeHandler);
            $('#categoryTree').on('move_node.jstree', moveNodeHandler);
            $('#categoryTree').on('select_node.jstree', selectNodeHandler);
            $('#treeSearch').on('keyup', treeSearchKeyupHandler);
            $('.expand-collapse-btn').on('click', expandCollapseBtnClickHandler);
            form.on('submit', submitAddCategoryForm);
            addChildButton.on('click', addChildButtonClickHandler);
            addRootCategoryButton.on('click', addRootCategoryButtonClickHandler);

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .select2-container {
            z-index: 0 !important;
        }

        .image--uploader .image-upload-wrapper {
            height: 210px !important;
        }

        .image--uploader .image-upload-input-wrapper {
            position: absolute;
            display: inline-flex;
            bottom: -17px;
            right: 10px;
        }

        .image--uploader .image-upload-preview {
            height: 210px !important;
            width: 210px !important;
        }

        .category-icon .image--uploader,
        .category-icon .image-upload-wrapper {
            height: 45px;
        }

        .jstree-default .jstree-themeicon-custom {
            color: #1c1f26;
        }

        .category-icon .image-upload-wrapper {
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .category-icon .image--uploader .image-upload-preview {
            position: absolute;
            height: 43px;
            width: 43px;
            border-radius: 5px;
            border: none;
        }

        .card.right-sticky {
            position: sticky;
            top: 30px;
        }

        .btn.expand-collapse-btn {
            background: transparent !important;
        }

        .expand-collapse-btn:focus,
        .btn.expand-collapse-btn:active {
            border: none !important;
        }

        .expand-collapse-btn:focus-within {
            background-color: transparent !important;
        }

        .expand-collapse-btn:hover {
            color: #6c757d !important;
        }

        button.expand-collapse-btn.text-muted {
            background: transparent;
            padding: 10px;
        }
    </style>
@endpush
