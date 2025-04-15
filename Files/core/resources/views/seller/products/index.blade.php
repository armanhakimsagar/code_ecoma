@extends('seller.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Thumbnail')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('In Stock')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            <div class="user">
                                                <div class="thumb">
                                                    <a href="{{ getImage(getFilePath('product') . '/thumb_' . @$product->main_image, getFileSize('product')) }}" class="image-popup">
                                                        <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$product->main_image, getFileSize('product')) }}" alt="@lang('image')">
                                                    </a>
                                                </div>
                                                <span class="name">{{ strLimit(__($product->name), 50) }}
                                                    @if ($product->is_featured)
                                                        <span class="text--danger" title="@lang('Featured')"><i class="fas fa-2x fa-fire"></i></span>
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            {{ showAmount($product->base_price) }}
                                        </td>
                                        <td>
                                            @if ($product->track_inventory)
                                                {{ optional($product->stocks)->sum('quantity') }}
                                            @else
                                                @lang('Infinite')
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->status == 1)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @endif
                                        </td>
                                        <td>

                                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                                @if ($product->trashed())
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to restore this product?')" data-action="{{ route('admin.products.restore', $product->id) }}"><i class="la la-undo"></i> @lang('Restore')</button>
                                                @else
                                                    <a href="{{ route('seller.products.edit', $product->id) }}" class="btn btn-sm btn-outline--primary"><i class="la la-pencil"></i>@lang('Edit')</a>

                                                    @if ($product->status == 0)
                                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('seller.products.delete', $product->id) }}" data-question="@lang('Are you sure you want to delete this product?')">
                                                            <i class="la la-trash"></i>@lang('Delete')
                                                        </button>
                                                    @else
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline--dark dropdown-toggle" type="button" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                                @lang('More')
                                                            </button>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                                                @if ($product->track_inventory)
                                                                    <a href="{{ route('seller.products.stock.create', [$product->id]) }}" class="dropdown-item"><i class="las la-layer-group"></i> @lang('Manage Inventory')</a>
                                                                @endif

                                                                @if ($product->has_variants)
                                                                    <a href="{{ route('seller.products.variant.store', [$product->id]) }}" class="dropdown-item">
                                                                        <i class="las la-list-alt"></i> @lang('Add Variants')
                                                                    </a>
                                                                @endif

                                                                <a href="javascript:void(0)" class="dropdown-item confirmationBtn" data-question="@lang('Are you sure to delete this product?')" data-action="{{ route('seller.products.delete', $product->id) }}"><i class="la la-trash"></i> @lang('Delete')</a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
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

                @if ($products->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($products) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form />
    @if (request()->routeIs('seller.products.all'))
        <a href="{{ route('seller.products.create') }}" title="@lang('Shortcut'): shift+n" class="btn btn-outline--primary"><i class="la la-plus"></i>@lang('Add New')</a>
    @else
        @if (request()->routeIs('seller.products.trashed.search'))
            <x-back route="{{ route('seller.products.trashed') }}" />
        @else
            <x-back route="{{ route('seller.products.all') }}" />
        @endif
    @endif

    @if (request()->routeIs('seller.products.all'))
        <a href="{{ route('seller.products.trashed') }}" class="btn btn-outline--danger"><i class="la la-trash-alt"></i>@lang('Trashed')</a>
    @endif
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $(document).keypress(function(e) {
                var unicode = e.charCode ? e.charCode : e.keyCode;
                if (unicode == 78) {
                    window.location = "{{ route('seller.products.create') }}";
                }
            });

            $('.image-popup').magnificPopup({
                type: 'image'
            });

        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        table .user .thumb img {
            border-radius: 10px;
        }
    </style>
@endpush
