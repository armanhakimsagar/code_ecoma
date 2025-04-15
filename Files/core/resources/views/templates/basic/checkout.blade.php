@extends(activeTemplate() . 'layouts.frontend')

@section('content')
    <!-- Checkout Section Starts Here -->
    <div class="checkout-section padding-bottom padding-top">
        <div class="container">
            <div class="checkout-area section-bg">
                <div class="row flex-wrap-reverse">
                    <div class="col-md-6 col-lg-7 col-xl-8">
                        <div class="checkout-wrapper">
                            <h4 class="title text-center">@lang('Shipping Address')</h4>
                            <ul class="nav-tabs nav justify-content-center">
                                <li>
                                    <a href="#self" data-bs-toggle="tab" class="active">@lang('For Yourself')</a>
                                </li>
                                <li>
                                    <a href="#guest" data-bs-toggle="tab">@lang('Order As Gift')</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane show fade active" id="self">
                                    <form action="{{ route('user.checkout-to-payment', 1) }}" method="post" class="billing-form mb--20">
                                        @csrf

                                        <div class="row">
                                            <div class="col-lg-12 mb-20">
                                                <label for="shipping-method" class="billing-label">@lang('Shipping Methods')</label>
                                                <div class="billing-select">
                                                    <select name="shipping_method" class="select-bar false px-2" required>
                                                        <option value="">@lang('Select One')</option>
                                                        @foreach ($shippingMethods as $shippingMethod)
                                                            <option value="{{ $shippingMethod->id }}" data-charge="{{ getAmount($shippingMethod->charge) }}" data-description="{{ $shippingMethod->description }}">{{ $shippingMethod->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-20">
                                                <label class="billing-label">@lang('Payment Method')</label>
                                                <div class="billing-select">
                                                    <select name="payment" class="select-bar false px-2" required>
                                                        <option value="">@lang('Select One')</option>
                                                        <option value="1">@lang('Direct Payment')</option>
                                                        @if (gs('cod'))
                                                            <option value="2">@lang('Cash On Delivery')</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-20">
                                                <label for="fname" class="billing-label">@lang('First Name')</label>
                                                <input class="form-control custom--style" id="fname" type="text" name="firstname" value="{{ auth()->user()->firstname ?? old('firstname') }}" required>
                                            </div>
                                            <div class="col-lg-6 mb-20">
                                                <label for="lname" class="billing-label">@lang('Last Name')</label>
                                                <input class="form-control custom--style" id="lname" name="lastname" type="text" value="{{ auth()->user()->lastname ?? old('lastname') }}" required>
                                            </div>

                                            <div class="col-lg-6 mb-20">
                                                <label for="country" class="billing-label">@lang('Country')</label>
                                                <div class="billing-select">
                                                    <select name="country" id="country" class="select-bar" required>
                                                        @foreach ($countries as $key => $country)
                                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobile" class="billing-label">@lang('Mobile')</label>

                                                    <input type="text" name="mobile" id="mobile" value="{{ old('mobile') ?? auth()->user()->mobile }}" class="form-control custom--style" placeholder="@lang('Your Phone Number')">
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-20">
                                                <label for="email" class="billing-label">@lang('Email')</label>
                                                <input class="form-control custom--style" id="email" name="email" type="text" value="{{ auth()->user()->email ?? old('email') }}" required>
                                            </div>

                                            <div class="col-md-6 mb-20">
                                                <label for="city" class="billing-label">@lang('City')</label>
                                                <input class="form-control custom--style" id="city" name="city" type="text" value="{{ auth()->user()->address->city ?? old('city') }}" required>
                                            </div>

                                            <div class="col-md-6 mb-20">
                                                <label for="state" class="billing-label">@lang('State')</label>
                                                <input class="form-control custom--style" id="state" name="state" type="text" value="{{ auth()->user()->address->state ?? old('state') }}" required>
                                            </div>

                                            <div class="col-md-6 mb-20">
                                                <label for="zip" class="billing-label">@lang('Zip/Post Code')</label>
                                                <input class="form-control custom--style" id="zip" name="zip" type="text" value="{{ auth()->user()->address->zip ?? old('zip') }}" required>
                                            </div>

                                            <div class="col-md-12 mb-20">
                                                <label for="address" class="billing-label">@lang('Address')</label>
                                                <textarea class="form-control custom--style" name="address" id="address" required>{{ auth()->user()->address->address ?? old('address') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row ">

                                            <div class="col-lg-12 mb-20">
                                                <button type="submit" class="bill-button w-100">@lang('Confirm Order')</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="tab-pane fade" id="guest">
                                    <form action="{{ route('user.checkout-to-payment', 2) }}" method="post" class="guest-form mb--20">
                                        @csrf

                                        <div class="row">
                                            <div class="col-lg-12 mb-20">
                                                <label for="shipping-method-2" class="billing-label">@lang('Shipping Method')</label>
                                                <div class="billing-select">
                                                    <select name="shipping_method" id="shipping-method-2" class="select-bar false px-2" required>
                                                        <option value="" disabled>@lang('Select One')</option>
                                                        @foreach ($shippingMethods as $shippingMethod)
                                                            <option value="{{ $shippingMethod->id }}" data-charge="{{ getAmount($shippingMethod->charge) }}" data-description="{{ $shippingMethod->description }}">{{ $shippingMethod->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-20">
                                                <label class="billing-label">@lang('Payment Method')</label>
                                                <div class="billing-select">
                                                    <select name="payment" class="select-bar false px-2" required>
                                                        <option value="">@lang('Select One')</option>
                                                        <option value="1">@lang('Direct Payment')</option>
                                                        @if (gs('cod'))
                                                            <option value="2">@lang('Cash On Delivery')</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-20">
                                                <label for="firstname" class="billing-label">@lang('First Name')</label>
                                                <input class="form-control custom--style" id="firstname" type="text" name="firstname" value="{{ old('firstname') }}" required>
                                            </div>
                                            <div class="col-lg-6 mb-20">
                                                <label for="lastname" class="billing-label">@lang('Last Name')</label>
                                                <input class="form-control custom--style" id="lastname" name="lastname" type="text" value="{{ old('lastname') }}" required>
                                            </div>

                                            <div class="col-lg-6 mb-20">
                                                <label for="mobile" class="billing-label">@lang('Mobile')</label>
                                                <input class="form-control custom--style" id="mobile" name="mobile" type="text" value="{{ old('mobile') }}" required>
                                            </div>

                                            <div class="col-lg-6 mb-20">
                                                <label for="e-mail" class="billing-label">@lang('Email')</label>
                                                <input class="form-control custom--style" id="e-mail" name="email" type="text" required>
                                            </div>

                                            <div class="col-lg-6 mb-20">
                                                <label for="country-2" class="billing-label">@lang('Country')</label>
                                                <div class="billing-select">
                                                    <select name="country" id="country-2" class="select-bar" required>
                                                        @foreach ($countries as $key => $country)
                                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-20">
                                                <label for="city-2" class="billing-label">@lang('City')</label>
                                                <input class="form-control custom--style" id="city-2" name="city" type="text" value="{{ old('city') }}" required>
                                            </div>

                                            <div class="col-md-6 mb-20">
                                                <label for="state-2" class="billing-label">@lang('State')</label>
                                                <input class="form-control custom--style" id="state-2" name="state" type="text" value="{{ old('state') }}" required>
                                            </div>

                                            <div class="col-md-6 mb-20">
                                                <label for="zip-2" class="billing-label">@lang('Zip/Post Code')</label>
                                                <input class="form-control custom--style" id="zip-2" name="zip" type="text" value="{{ old('zip') }}" required>
                                            </div>

                                            <div class="col-md-12 mb-20">
                                                <label for="address-2" class="billing-label">@lang('Address')</label>
                                                <textarea class="form-control custom--style" id="address-2" name="address" required>{{ old('address') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-12 mb-20">
                                                <button type="submit" class="bill-button w-100">@lang('Confirm Order')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5 col-xl-4">
                        <div class="payment-details">
                            <h4 class="title text-center">@lang('Payment Details')</h4>
                            <ul>
                                <li>
                                    <span class="subtitle">@lang('Subtotal')</span>
                                    <span class="text-success" id="cartSubtotal">{{ gs('cur_sym') }}0</span>
                                </li>
                                @if (session()->has('coupon'))
                                    <li>
                                        <span class="subtitle">@lang('Coupon') ({{ session('coupon')['code'] }})</span>
                                        <span class="text-success" id="couponAmount">{{ gs('cur_sym') }}{{ getAmount(session('coupon')['amount'], 2) }}</span>
                                    </li>

                                    <li>
                                        <span class="subtitle">(<i class="la la-minus"></i>)</span>
                                        <span class="text-success" id="afterCouponAmount">{{ gs('cur_sym') }}0</span>
                                    </li>
                                @endif
                                <li>
                                    <span class="subtitle">@lang('Shipping Charge')</span>
                                    <span class="text-danger" id="shippingCharge">{{ gs('cur_sym') }}0</span>
                                </li>
                                <li class="border-0">
                                    <span class="subtitle bold">@lang('Total')</span>
                                    <span class="cl-title" id="cartTotal">{{ gs('cur_sym') }}0</span>
                                </li>
                            </ul>
                            <div id="shipping-details"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Checkout Section Ends Here -->
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            var subTotal = Number(sessionStorage.getItem('subtotal'));
            $('#cartSubtotal').text(`{{ gs('cur_sym') }}` + parseFloat(subTotal).toFixed(2));

            var couponAmount =
                @if (session()->has('coupon'))
                    {{ session('coupon')['amount'] }}
                @else
                    0
                @endif ;

            var afterCouponAmount = (subTotal - couponAmount).toFixed(2);


            $('#afterCouponAmount').text(afterCouponAmount)

            $('#cartTotal').text(`{{ gs('cur_sym') }}` + parseFloat(subTotal - couponAmount).toFixed(2));

            $('select[name=shipping_method]').on('change', function() {

                if (!$(this).val()) {
                    $('#shippingCharge').text(`{{ gs('cur_sym') }}0`);
                    $('#cartTotal').text(`{{ gs('cur_sym') }}${afterCouponAmount}`);
                    $('#shipping-details').html('');
                    return;
                }

                var data = $('option:selected', this).data();
                let charge = Number(data.charge);
                let cartTotal = Number(afterCouponAmount) + charge;

                $('#shippingCharge').text(`{{ gs('cur_sym') }}${charge.toFixed(2)}`);
                $('#cartTotal').text(`{{ gs('cur_sym') }}${cartTotal.toFixed(2)}`);
                $('#shipping-details').html(data.description ?? '');
            });

            $('select[name=country]').val("{{ auth()->user()->country_name }}");

        })(jQuery)
    </script>
@endpush
