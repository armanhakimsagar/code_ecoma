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
                    <div class="card border-0 shadow-md">
                        <div class="card-header bg-transparent">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h5 class="mb-0">{{ __($pageTitle) }}</h5>
                                <div class="text-end">
                                    <a href="{{ route('ticket.index') }}" class="btn btn--base">
                                        <i class="las la-list"></i> @lang('My Support Tickets')
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="form-group col-md-6">
                                        <label for="website">@lang('Subject')</label>
                                        <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="@lang('Subject')" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="priority">@lang('Priority')</label>
                                        <select name="priority" class="form-control form-select">
                                            <option value="3">@lang('High')</option>
                                            <option value="2">@lang('Medium')</option>
                                            <option value="1">@lang('Low')</option>
                                        </select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="inputMessage">@lang('Message')</label>
                                        <textarea name="message" id="inputMessage" rows="6" class="form-control" required>{{ old('message') }}</textarea>
                                    </div>
                                    <div class="col-md-9">
                                        <button type="button" class="btn btn-dark btn-sm addAttachment my-2"> <i class="fas fa-plus"></i> @lang('Add Attachment') </button>
                                        <p class="mb-2"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                        <div class="row fileUploadsContainer gy-4">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn--base w-100 my-2" type="submit"><i class="las la-paper-plane"></i> @lang('Submit')
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="file-upload-wrapper d-none">
        <div class="input-group mt-3">
            <input type="file" name="attachments[]" class="form-control form-control-lg" required accept=".jpeg,.png,.jpeg, .pdf, .doc, .docx" />
            <div class="input-group-append support-input-group">
                <span class="input-group-text btn btn--danger border-0 support-btn remove-btn"> <i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border-0 text--white"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
