@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <!-- Category Section Starts Here -->
    <div class="category-section padding-bottom padding-top">
        <div class="container">
            @if ($products->count() == 0)
                <div class="mb-30">
                    @include($activeTemplate . 'partials.empty_page', ['message' => __($emptyMessage)])
                </div>
            @else
                <div class="alert cl-title alert--base" role="alert">
                    @lang('Search Result') : {{ $products->count() }} @lang('Products Found For ') "{{ $searchKey }}"
                </div>

                <div class="filter-category-header">
                    <div class="fileter-select-item">
                        <div class="select-item product-page-per-view">
                            <select class="select-bar">
                                <option value="">@lang('Products Per Page')</option>
                                <option value="5" {{ @$perpage == 5 ? 'selected' : '' }}>@lang('5 Items Per Page') </option>
                                <option value="15" {{ @$perpage == 15 ? 'selected' : '' }}>@lang('15 Items Per Page') </option>
                                <option value="30" {{ @$perpage == 30 ? 'selected' : '' }}>@lang('30 Items Per Page') </option>
                                <option value="50" {{ @$perpage == 50 ? 'selected' : '' }}>@lang('50 Items Per Page') </option>
                                <option value="100" {{ @$perpage == 100 ? 'selected' : '' }}>@lang('100 Items Per Page') </option>
                                <option value="200" {{ @$perpage == 200 ? 'selected' : '' }}>@lang('200 Items Per Page') </option>
                            </select>
                        </div>
                    </div>

                    <div class="fileter-select-item d-none d-lg-block ml-auto align-self-end">
                        <ul class="view-number">
                            <li class="change-grid-to-6">
                                <span class="bar"></span>
                                <span class="bar"></span>
                            </li>
                            <li class="change-grid-to-4">
                                <span class="bar"></span>
                                <span class="bar"></span>
                                <span class="bar"></span>
                            </li>
                            <li class="change-grid-to-3  active">
                                <span class="bar"></span>
                                <span class="bar"></span>
                                <span class="bar"></span>
                                <span class="bar"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="fileter-select-item d-none d-lg-block ml-auto ml-lg-0 align-self-end">
                        <ul class="view-style d-flex">
                            <li>
                                <a href="javascript:void(0)" class="active view-grid-style"><i class="las la-border-all"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="view-list-style"><i class="las la-list-ul"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="position-relative">
                    <div id="overlay">
                        <div class="cv-spinner">
                            <span class="spinner"></span>
                        </div>
                    </div>
                    <div class="overlay-2" id="overlay2"></div>
                    <div class="page-main-content">
                        <div class="row mb-30-none page-main-content" id="grid-view">
                            @include($activeTemplate . 'partials.product_card', ['products' => $products, 'class' => 'col-lg-3 col-sm-6 grid-control mb-30', 'noMargin' => true])
                        </div>

                        {{ $products->appends(['perpage' => @$perpage, 'category_id' => $category_id])->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Category Section Ends Here -->
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $(document).on('change', '.product-page-per-view select', function() {
                $("#overlay, #overlay2").fadeIn(300);
                $.ajax({
                    url: "{{ route('product.search.filter') }}",
                    method: "get",
                    data: {
                        'perpage': $(this).val(),
                        'category_id': '{{ $category_id }}',
                        'search_key': '{{ $searchKey }}'
                    },
                    success: function(result) {
                        $('.ajax-preloader').addClass('d-none');
                        $('.page-main-content').html(result);

                    }
                }).done(function() {
                    setTimeout(function() {
                        $("#overlay, #overlay2").fadeOut(300);
                    }, 500);
                });
            });

        })(jQuery)
    </script>
@endpush
