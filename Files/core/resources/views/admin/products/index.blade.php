@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    @if ($products->count())
                        <div class="row {{ request()->routeIs('admin.products.pending') ? 'justify-content-between' : 'justify-content-end' }}">
                            @if (request()->routeIs('admin.products.pending'))
                                <div class="col-xl-9 m-3">
                                    <button type="button" class="btn btn--success confirmationBtn" data-action="{{ route('admin.products.approve.all') }}" data-question="@lang('Are you sure to approve all products?')"><i class="las la-check-double"></i> @lang('Approve All') </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    @if (request()->routeIs('admin.products.seller'))
                                        <th>@lang('Seller')</th>
                                    @endif
                                    <th>@lang('Product')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('In Stock')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        @if (request()->routeIs('admin.products.seller'))
                                            <td>
                                                <div>
                                                    <span class="fw-bold d-block">
                                                        {{ @$product->seller->fullname }}
                                                    </span>
                                                    <a href="{{ route('admin.sellers.detail', @$product->seller->id ?? 0) }}">{{ @$product->seller->username }}</a>
                                                </div>
                                            </td>
                                        @endif

                                        <td>
                                            <div class="d-flex flex-wrap justify-content-lg-start justify-content-end align-items-center gap-2">
                                                <div class="product-image">
                                                    <a href="{{ getImage(getFilePath('product') . '/thumb_' . @$product->main_image, getFileSize('product')) }}" class="image-popup">
                                                        <img src="{{ getImage(getFilePath('product') . '/' . @$product->main_image, getFileSize('product')) }}" alt="@lang('image')">
                                                    </a>
                                                </div>
                                                <span class="name" @if (strlen($product->name) > 50) data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __($product->name) }}" @endif>
                                                    {{ strLimit(__($product->name), 50) }}
                                                </span>
                                            </div>
                                        </td>

                                        <td>{{ showAmount($product->base_price) }}</td>
                                        <td>
                                            @if ($product->track_inventory)
                                                @php
                                                    $inStock = optional($product->stocks)->sum('quantity');
                                                @endphp
                                                <span class="@if ($inStock < 10) text--danger @endif">
                                                    {{ $inStock }}
                                                </span>
                                            @else
                                                @lang('Infinite')
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->status == Status::ENABLE)
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
                                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline--primary"><i class="la la-pencil"></i>@lang('Edit')</a>

                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline--dark dropdown-toggle" type="button" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                            @lang('More')
                                                        </button>

                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                                            {{-- If sellers product --}}
                                                            @if (request()->routeIs('admin.products.seller'))
                                                                @if ($product->status == Status::ENABLE)
                                                                    <a href="javascript:void(0)" class="confirmationBtn dropdown-item" data-action="{{ route('admin.products.action', $product->id) }}" data-question="@lang('Are you sure to mark as pending?')"><i class="la la-ban"></i> @lang('Mark as Pending')</a>
                                                                @else
                                                                    <a href="javascript:void(0)" class="confirmationBtn dropdown-item" data-action="{{ route('admin.products.action', $product->id) }}" data-question="@lang('Are you sure to mark as pending?')"><i class="las la-check-double"></i> @lang('Approve')</a>
                                                                @endif
                                                            @endif

                                                            @if ($product->is_featured == Status::YES)
                                                                <a href="javascript:void(0)" class="dropdown-item confirmationBtn" data-action="{{ route('admin.products.featured', $product->id) }}" data-question="@lang('Are you sure to remove from featured?')">
                                                                    <i class="la la-times-circle"></i> @lang('Remove from Featured')
                                                                </a>
                                                            @else
                                                                <a href="javascript:void(0)" class="dropdown-item confirmationBtn" data-action="{{ route('admin.products.featured', $product->id) }}" data-question="@lang('Are you sure to mark as featured?')">
                                                                    <i class="la la-list-alt"></i> @lang('Mark as Featured')
                                                                </a>
                                                            @endif

                                                            @if ($product->track_inventory)
                                                                <a href="{{ route('admin.products.stock.create', [$product->id]) }}" class="dropdown-item"><i class="las la-layer-group"></i> @lang('Manage Inventory')</a>
                                                            @endif

                                                            @if ($product->has_variants)
                                                                <a href="{{ route('admin.products.variant.store', [$product->id]) }}" class="dropdown-item">
                                                                    <i class="las la-list-alt"></i> @lang('Add Variants')
                                                                </a>
                                                            @endif

                                                            <a href="javascript:void(0)" class="dropdown-item confirmationBtn" data-question="@lang('Are you sure to delete this product?')" data-action="{{ route('admin.products.delete', $product->id) }}"><i class="la la-trash"></i> @lang('Delete')</a>

                                                        </div>
                                                    </div>
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
    @if (request()->routeIs('admin.products.seller'))
        <a href="{{ route('admin.products.pending') }}" class="btn btn-outline--warning">
            <i class="las la-hourglass-end"></i>@lang('Pending Products')
        </a>
    @endif

    @if (request()->routeIs('admin.products.all') || request()->routeIs('admin.products.admin'))
        <a href="{{ route('admin.products.create') }}" data-bs-toggle="tooltip" title="@lang('Shortcut'): shift+n" class="btn btn-outline--primary"><i class="la la-plus"></i>@lang('Add New')</a>
    @endif
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';

            $(document).keypress(function(e) {
                var unicode = e.charCode ? e.charCode : e.keyCode;
                if (unicode == 78) {
                    window.location = "{{ route('admin.products.create') }}";
                }
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .product-image {
            height: auto;
            width: 25px;
            overflow: hidden;
            border-radius: 10px;
        }
    </style>
@endpush
