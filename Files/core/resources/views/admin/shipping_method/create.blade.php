@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.shipping.methods.store', $shippingMethod->id ?? 0) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Name')</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" value="{{ old('name', @$shippingMethod->name) }}" name="name" required/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Charge')</label>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="number" min="0" step="any" class="form-control" value="{{ old('charge', @$shippingMethod->charge > 0 ? getAmount($shippingMethod->charge) : '' ) }}" name="charge" required/>
                                    <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Deliver In')</label>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="number" class="form-control" value="{{ old('shipping_time', @$shippingMethod->shipping_time) }}" name="shipping_time" required/>
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Description')</label>
                            </div>
                            <div class="col-md-10">
                                <textarea rows="5" class="form-control" name="description">{{ old('description', @$shippingMethod->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.shipping.methods.index') }}" />
@endpush
