@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">
            <div class="card b-radius--10 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="">
                            <img src="{{ getImage(getFilePath('sellerProfile') . '/' . $seller->image, getFileSize('sellerProfile')) }}" alt="@lang('Profile Image')" class="b-radius--10 w-100">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card b-radius--10 overflow-hidden mt-20">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User Information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span>{{ $seller->username }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Balance')
                            <span>{{ showAmount($seller->balance) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Joined At') <strong>{{ showDateTime($seller->created_at, 'd M, Y h:i A') }}</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if ($seller->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @elseif($seller->status == 0)
                                <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-20">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Seller Action')</h5>
                    @if ($seller->shop)
                        <a href="{{ route('admin.sellers.shop.details', $seller->id) }}" class="btn btn--primary w-100 btn-lg mb-2">
                            @lang('Shop Info.')
                        </a>
                    @endif

                    <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--success w-100 btn-lg bal-btn mb-2" data-act="add">
                        <i class="las la-plus-circle"></i> @lang('Balance')
                    </button>

                    <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--danger w-100 btn-lg bal-btn mb-2" data-act="sub">
                        <i class="las la-minus-circle"></i> @lang('Balance')
                    </button>

                    <a href="{{ route('admin.report.seller.login.history', $seller->id) }}" class="btn btn--secondary w-100 btn-lg mb-2">
                        @lang('Logins')
                    </a>

                    <a href="{{ route('admin.sellers.notification.log', $seller->id) }}" class="btn btn--warning w-100 btn-lg">
                        @lang('Notifications')
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">

            <div class="row gy-4">
                <div class="col-xxl-3 col-lg-6 col-sm-6">
                    <x-widget style="2" link="{{ route('admin.sellers.sell.logs', $seller->id) }}" icon="fa fa-credit-card" title="Total Sold" value="{{ gs('cur_sym') . showAmount($totalSold, currencyFormat: false) }}" color="deep-purple" cover_cursor="1" overlay_icon="0" />

                </div><!-- dashboard-w1 end -->

                <div class="col-xxl-3 col-lg-6 col-sm-6">
                    <x-widget style="2" link="{{ route('admin.withdraw.data.all', $seller->id) }}" icon="fa fa-wallet" title="Total Withdraw" value="{{ gs('cur_sym') . showAmount($totalWithdraw, currencyFormat: false) }}" color="indigo" cover_cursor="1" overlay_icon="0" />
                </div><!-- dashboard-w1 end -->

                <div class="col-xxl-3 col-lg-6 col-sm-6">
                    <x-widget style="2" link="{{ route('admin.report.seller.transaction', $seller->id) }}" icon="la la-exchange-alt" title="Total Transaction" value="{{ $totalTransaction }}" color="12" cover_cursor="1" overlay_icon="0" />
                </div><!-- dashboard-w1 end -->

                <div class="col-xxl-3 col-lg-6 col-sm-6">
                    <x-widget style="2" link="{{ route('admin.sellers.products', $seller->id) }}" icon="las la-tshirt" title="Total Products" value="{{ $totalProducts }}" color="17" cover_cursor="1" overlay_icon="0" />
                </div><!-- dashboard-w1 end -->
            </div>

            <div class="card mt-20">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Information of') {{ $seller->fullname }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sellers.update', $seller->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>@lang('First Name')</label>
                                    <input class="form-control" type="text" name="firstname" value="{{ $seller->firstname }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Last Name') </label>
                                    <input class="form-control" type="text" name="lastname" value="{{ $seller->lastname }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Email') </label>
                                    <input class="form-control" type="email" name="email" value="{{ $seller->email }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile Number') </label>
                                    <div class="input-group ">
                                        <span class="input-group-text mobile-code">+{{ $seller->dial_code }}</span>
                                        <input type="number" name="mobile" value="{{ $seller->mobile }}" id="mobile" class="form-control checkUser" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label>@lang('Address') </label>
                                    <input class="form-control" type="text" name="address" value="{{ @$seller->address }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('City') </label>
                                    <input class="form-control" type="text" name="city" value="{{ @$seller->city }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>@lang('State') </label>
                                    <input class="form-control" type="text" name="state" value="{{ @$seller->state }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Zip/Postal') </label>
                                    <input class="form-control" type="text" name="zip" value="{{ @$seller->zip }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Country')</label>
                                    <select name="country" class="form-control select2" required>
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" @selected($seller->country_code == $key)>
                                                {{ __($country->country) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-xl-4 col-md-6 col-sm-3 col-12">
                                <label>@lang('Status') </label>
                                <input type="checkbox" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Banned')" data-width="100%" name="status" @if ($seller->status) checked @endif>
                            </div>

                            <div class="form-group col-xl-4 col-md-6 col-sm-3 col-12">
                                <label>@lang('Email Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev" @if ($seller->ev) checked @endif>

                            </div>

                            <div class="form-group col-xl-4 col-md-6 col-sm-3 col-12">
                                <label>@lang('SMS Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv" @if ($seller->sv) checked @endif>

                            </div>
                            <div class="form-group col-xl-4 col-md-6 col-sm-3 col-12">
                                <label>@lang('KYC Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="kv" @if ($seller->kv) checked @endif>
                            </div>
                            <div class="form-group col-xl-4 col-md-6 col-sm-3 col-12">
                                <label>@lang('2FA Status') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Deactive')" name="ts" @if ($seller->ts) checked @endif>
                            </div>

                            <div class="form-group col-xl-4 col-md-6 col-sm-3 col-12">
                                <label>@lang('2FA Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="tv" @if ($seller->tv) checked @endif>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Sub Balance MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>@lang('Balance')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.sellers.add.sub.balance', $seller->id) }}" class="balanceAddSub disableSubmission" method="POST">
                    @csrf
                    <input type="hidden" name="act">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')" required>
                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Remark')</label>
                            <textarea class="form-control" placeholder="@lang('Remark')" name="remark" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.sellers.login', $seller->id) }}" target="_blank" class="btn btn-outline--primary w-100">
        <i class="las la-sign-in-alt"></i>@lang('Login as Seller')
    </a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.bal-btn').on('click', function() {

                $('.balanceAddSub')[0].reset();

                var act = $(this).data('act');
                $('#addSubModal').find('input[name=act]').val(act);
                if (act == 'add') {
                    $('.type').text("@lang('Add')");
                } else {
                    $('.type').text("@lang('Subtract')");
                }
            });

            let mobileElement = $('.mobile-code');
            $('select[name=country]').on('change', function() {
                mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
            });
        })(jQuery);
    </script>
@endpush
