@extends('seller.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12 mb-4">
            <div class="card-body bg--10 ps-3 py-2 border-radius-5">
                <h4 class="text-white">@lang('Product Name') : {{ __($product->name) }}</h4>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card">
                    <div class="card-body p-0">
                        @if ($data && $product->has_variants)
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light">

                                    <thead>
                                        <tr>
                                            <th>@lang('S.N.')</th>
                                            <th>@lang('Variant')</th>
                                            <th>@lang('SKU')</th>
                                            <th>@lang('Quantity')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ __($item['combination']) }}</td>
                                                <td>{{ @$item['sku'] }}</td>
                                                <td>
                                                    <span class="text--small badge @if ($item['quantity'] == 0) badge--danger @elseif($item['quantity'] < 10) badge--warning @else badge--success @endif font-weight-normal">{{ $item['quantity'] ?? 0 }}</span>
                                                </td>

                                                <td>
                                                    <div class="button--group">
                                                        <button type="button" data-sku="{{ $item['sku'] }}" data-attributes="{{ $item['attributes'] }}" class="btn icon-btn btn--primary editBtn" data-bs-toggle="tooltip" title="@lang('Update Inventory')"><i class="la la-pencil-alt me-0"></i></button>

                                                        <a href="{{ route('seller.products.stock.log', $item['stock_id'] ?? 0) }}" class="btn icon-btn btn--info" data-bs-toggle="tooltip" title="@lang('See Logs')"><i class="fas fa-list me-0"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif(!$data && $product->has_variants)
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light">
                                    <tr>
                                        <td colspan="100%" class="text-center">
                                            <h3 class="text--danger">@lang('You did\'t add any variant for this product yet. ')</h3>
                                            <a class="btn btn--dark mt-3" href="{{ route('seller.products.variant.store', [$product->id]) }}">@lang('Add Variant')</a>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        @else
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light">
                                    <tr>
                                        <th>@lang('SKU')</th>
                                        <th>@lang('Quantity')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            @php $stock = \App\Models\ProductStock::showAvailableStock($product->id, $attr_val = null); @endphp
                                            {{ sprintf('%02d', $stock->quantity ?? 0) }}
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                <button type="button" data-sku="{{ $product->sku }}" data-attributes="0" class="btn icon-btn btn--primary editBtn" data-bs-toggle="tooltip" title="@lang('Update Inventory')"><i class="la la-pencil-alt"></i>
                                                </button>

                                                <a href="{{ route('seller.products.stock.log', $product->stocks[0] ?? 0) }}" class="btn icon-btn btn--info" data-bs-toggle="tooltip" title="@lang('See Logs')"><i class="fas fa-list"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editModal">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('Update Inventory')</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="las la-times"></i>
                            </button>
                        </div>

                        <form action="{{ route('seller.products.stock.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="attr" value="">
                            <div class="modal-body">
                                <div class="form-group sku">
                                    <label>@lang('SKU')</label>
                                    <input type="text" name="sku" id="" class="form-control" />
                                    <span class="text--small text--info"> <i class="fas fa-info-circle"></i> @lang('If you want to update only SKU keep the Quantity field 0')</span>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Quantity')</label>
                                    <div class="input-group">
                                        <div class="input-group-text p-0 border-0">
                                            <select class="form-control input-group-select" name="type">
                                                <option value="1">+</option>
                                                <option value="2">-</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control integer-validation" name="quantity">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn h-45 w-100 btn--primary">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-back route="{{ route('seller.products.all') }}" />
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {

            $('.editBtn').on('click', function() {
                var modal = $('#editModal');
                var attrArray = $(this).data('attributes');
                modal.find('input[name=sku]').val($(this).data('sku'));
                if (attrArray != 0) {
                    modal.find('input[name=attr]').val(JSON.stringify(attrArray));
                } else {
                    modal.find('.sku').hide();
                    modal.find('input[name=attr]').remove();

                }
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush

@push('style')
    <style>
        a.icon-btn {
            padding: 3px 8px !important;
        }

        .input-group-select {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
    </style>
@endpush
