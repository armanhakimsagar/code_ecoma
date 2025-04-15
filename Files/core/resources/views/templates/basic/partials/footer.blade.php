@php
    $footer = getContent('footer.content', true);
    if ($footer) {
        $footer = $footer->data_values;
    }

    $categories = \App\Models\Category::where('is_top', 1)->inRandomOrder()->take(6)->get();
    $topBrands = \App\Models\Brand::top()->inRandomOrder()->take(6)->get();

@endphp

<footer class="section-bg">
    <div class="container">
        <div class="padding-bottom padding-top">
            <div class="row gy-5">
                <div class="col-lg-6">
                    <div class="row justify-content-between g-4">
                        <div class="col-md-5">
                            <div class="footer__widget footer__widget-about">
                                <div class="logo">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ siteLogo('dark') }}" alt="logo">
                                    </a>
                                </div>
                                <p class="addr">
                                    @lang(@$footer->footer_note)
                                </p>
                                @php
                                    $socials = getContent('social_media_links.element');
                                @endphp

                                <ul class="social__icons">
                                    @if ($socials->count() > 0)
                                        @foreach ($socials as $item)
                                            <li>
                                                <a href="{{ $item->data_values->url }}" target="blank">
                                                    @php
                                                        echo $item->data_values->social_icon;
                                                    @endphp
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-7 col-xl-6">
                            <div class="footer__widget widget__info">
                                <h5 class="widget--title">@lang('Contact Us')</h5>
                                <div>
                                    <div class="contact__info">
                                        <div class="icon">
                                            <i class="las la-headset"></i>
                                        </div>
                                        <div class="content">
                                            <h6 class="contact__info-title">
                                                <a href="tel:{{ @$footer->cell_number }}">{{ @$footer->cell_number }}</a>
                                            </h6>
                                            <span class="info">{{ @$footer->time }}</span>
                                        </div>
                                    </div>
                                    <div class="contact__info style-two">
                                        <div class="icon">
                                            <i class="las la-envelope-open"></i>
                                        </div>
                                        <div class="content">
                                            <h6 class="contact__info-title">
                                                <a href="mailto:{{ @$footer->email }}">{{ @$footer->email }}</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4 pl-xl-5">
                        <div class="col-lg-4 col-6">
                            <div class="footer__widget">
                                <h5 class="widget--title">@lang('Accounts')</h5>
                                <ul class="footer__links">
                                    <li>
                                        <a href="{{ route('user.login') }}">@lang('Login as Customer')</a>
                                    </li>

                                    @if (gs('registration'))
                                        <li>
                                            <a href="{{ route('user.register') }}">@lang('Register as Customer')</a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{ route('seller.login') }}">@lang('Login as Seller')</a>
                                    </li>

                                    @if (gs('registration'))
                                        <li>
                                            <a href="{{ route('seller.register') }}">@lang('Register as Seller')</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <div class="footer__widget">
                                <h5 class="widget--title">@lang('Top Brands')</h5>
                                <ul class="footer__links">
                                    @foreach ($topBrands as $brand)
                                        <li>
                                            <a href="{{ route('products.brand', [$brand->id, slug($brand->name)]) }}">{{ $brand->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @php
                            $pages = \App\Models\Frontend::where('data_keys', 'pages.element')->get();
                            $policyPages = \App\Models\Frontend::where('data_keys', 'policy_pages.element')->get();
                        @endphp
                        <div class="col-lg-4 col-md-6">
                            <div class="footer__widget">
                                <h5 class="widget--title">@lang('Useful Links')</h5>
                                <ul class="footer__links">
                                    @foreach ($policyPages as $item)
                                        <li><a href="{{ route('policy.pages', $item->slug) }}">{{ __($item->data_values->title) }}</a></li>
                                    @endforeach
                                    @if ($pages->count() > 0)
                                        @foreach ($pages as $item)
                                            <li><a href="{{ route('page.details', $item->slug) }}">{{ __($item->data_values->title) }}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom body-bg text-center">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-around justify-content-lg-between align-items-center">
                <div class="left py-2">
                    {{ __(@$footer->copyright_text) }}
                </div>
                <div class="right py-2">
                    @isset($footer->payment_methods)
                        <img src="{{ getImage('assets/images/frontend/footer/' . @$footer->payment_methods, '250x30') }}" alt="@lang('footer')">
                    @endisset
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="quickView">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content py-4">
            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="ajax-loader-wrapper d-flex align-items-center justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">@lang('Loading')...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
