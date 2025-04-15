@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <!-- Category Section Starts Here -->
    <div class="category-section padding-bottom padding-top">
        <div class="container">
            @if ($products->count() == 0)
                <div class="col-lg-12 mb-30">
                    @include($activeTemplate . 'partials.empty_page', ['message' => __($emptyMessage)])
                </div>
            @else
                <div class="row">
                    <div class="col-xl-3">
                        <aside class="category-sidebar">
                            <div class="widget d-xl-none">
                                <div class="d-flex justify-content-between">
                                    <h5 class="title border-0 pb-0 mb-0">@lang('Filter')</h5>
                                    <div class="close-sidebar"><i class="las la-times"></i></div>
                                </div>
                            </div>
                            <div class="widget">
                                <h5 class="title">@lang('Filter by Price')</h5>
                                <div class="widget-body">
                                    <div id="slider-range"></div>
                                    <div class="price-range">
                                        <label for="amount">@lang('Price') :</label>
                                        <input type="text" id="amount" readonly>
                                        <input type="hidden" name="min_price" value="{{ $min_price }}">
                                        <input type="hidden" name="max_price" value="{{ $max_price }}">
                                    </div>
                                </div>
                            </div>

                            <div class="widget">
                                <h5 class="title">@lang('Filter by Category')</h5>
                                <div class="widget-body">
                                    <ul class="filter-category">
                                        <li>
                                            <a href="javascript:void(0)" data-id="0" class="@if ($category_id == 0) ) active @endif"><i class="las la-angle-right"></i> {{ __('All Category') }} </a>
                                        </li>

                                        @foreach ($categories as $category)
                                            <li>
                                                <a href="javascript:void(0)" data-id="{{ $category->id }}" class="@if ($category_id == $category->id) ) active @endif"><i class="las la-angle-right"></i> {{ __($category->name) }} </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </aside>
                    </div>
                    <div class="col-xl-9">
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
                                    <li class="change-grid-to-4 active">
                                        <span class="bar"></span>
                                        <span class="bar"></span>
                                        <span class="bar"></span>
                                    </li>
                                    <li class="change-grid-to-3">
                                        <span class="bar"></span>
                                        <span class="bar"></span>
                                        <span class="bar"></span>
                                        <span class="bar"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="fileter-select-item ml-auto ml-lg-0 align-self-end">
                                <ul class="view-style d-flex">
                                    <li>
                                        <a href="javascript:void(0)" class="active view-grid-style"><i class="las la-border-all"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="view-list-style"><i class="las la-list-ul"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="fileter-select-item align-self-end d-xl-none pl-0">
                                <div class="filter-in">
                                    <i class="las la-filter"></i>
                                </div>
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
                                    @include($activeTemplate . 'partials.product_card', ['products' => $products, 'class' => 'col-lg-4 col-sm-6 grid-control mb-30', 'noMargin' => true])
                                </div>
                                {{ $products->appends(['perpage' => @$perpage, 'category_id' => $category_id, 'min' => @$min, 'max' => @$max])->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Category Section Ends Here -->
@endsection

@push('script')
    <script>
        (function($) {

            $('select[name=per_page]').val(`{{ $perpage }}`);

            function getFilteredData(min = null, max = null, category_id = null, perpage = `{{ $perpage }}`) {
                $("#overlay, #overlay2").fadeIn(300);
                $.ajax({
                    url: `{{ route('brands.filter', [$brand->id, slug($brand->name)]) }}`,
                    method: "get",
                    data: {
                        'perpage': perpage,
                        'min': min,
                        'max': max,
                        'category_id': category_id
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
            }


            $(document).on('change', '.product-page-per-view select', function() {
                var perpage = $(this).val();
                var min = $('input[name="min_price"]').val();
                var max = $('input[name="max_price"]').val();

                var category_id = $(document).find('.filter-category li a.active').data('id');
                getFilteredData(min, max, category_id, perpage);
            });

            $("#slider-range").slider({
                range: true,
                min: {{ $min_price }},
                max: {{ $max_price }},
                values: [{{ $min_price }}, {{ $max_price }}],
                slide: function(event, ui) {
                    $("#amount").val("{{ gs('cur_sym') }}" + ui.values[0] + " - $" + ui.values[1]);
                    $('input[name=min_price]').val(ui.values[0]);
                    $('input[name=max_price]').val(ui.values[1]);

                },
                change: function() {
                    var min = $('input[name="min_price"]').val();
                    var max = $('input[name="max_price"]').val();

                    var perpage = $('select[name=per_page]').val();
                    var category_id = $(document).find('.filter-category li a.active').data('id');
                }

            });


            $("#amount").val("{{ gs('cur_sym') }}" + $("#slider-range").slider("values", 0) + " - {{ gs('cur_sym') }}" + $("#slider-range").slider("values", 1));

            $('.filter-category li a').on('click', function() {

                $(document).find('.filter-category li a').removeClass('active');
                $(this).addClass('active');
                var category_id = $(this).data('id');
                var min = $('input[name="min_price"]').val();
                var max = $('input[name="max_price"]').val();
                var perpage = $('select[name=per_page]').val();
                getFilteredData(min, max, category_id, perpage);

            });

        })(jQuery)
    </script>
@endpush
