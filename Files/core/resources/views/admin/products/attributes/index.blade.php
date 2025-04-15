@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Name for Customer')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($attributes as $attr)
                                    <tr>
                                        <td>{{ $attributes->firstItem() + $loop->index }}</td>
                                        <td>{{ __($attr->name) }}</td>
                                        <td>{{ __($attr->name_for_user) }}</td>
                                        <td>
                                            @if ($attr->type == 1)
                                                @lang('Text')
                                            @elseif($attr->type == 2)
                                                @lang('Color')
                                            @else
                                                @lang('Image')
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Edit Attribute Type')" data-resource="{{ $attr }}"><i class="la la-pencil"></i>@lang('Edit')</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($attributes->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($attributes) }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div id="cuModal" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.attributes.store') }}" method="POST" class="disableSubmission">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>@lang('Name')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="@lang('Enter Name For Admin')" value="" name="name" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>@lang('Name for User')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="@lang('Enter Name For User')" value="" name="name_for_user" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>@lang('Type')</label>
                            </div>
                            <div class="col-md-9">
                                <select class="select2" name="type" data-minimum-results-for-search="-1" required>
                                    <option value="" disabled selected>@lang('Select One')</option>
                                    <option value="1">@lang('Text')</option>
                                    <option value="2">@lang('Color')</option>
                                    <option value="3">@lang('Image')</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-outline--primary cuModalBtn" data-modal_title="@lang('Add New Attribute Type')"> <i class="las la-plus"></i>@lang('Add New')</button>
    <x-search-form />
@endpush

@push('script')
    <script>
        "use strict";
        $('.cuModalBtn').on('click', function() {
            let resource = $(this).data('resource');
            if (resource != undefined) {
                $('#cuModal').find('.select2').val(resource.type).trigger('change');
            } else {
                $('#cuModal').find('.select2').val("").trigger('change');
            }
        });
    </script>
@endpush
