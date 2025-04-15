<script>
    (function($) {
        "use strict";
        var product_id = 0;

        /*
        ==========TRIGGER NECESSARY FUNCTIONS==========
         */
        background();
        backgroundColor();
        triggerOwl();
        getCompareData();
        getCartData();
        getCartTotal();
        getWishlistData();
        getWishlistTotal();

        $(".selectLanguage").on("change", function() {
            window.location.href = "{{ route('home') }}/change/" + $(this).val();
        });

        /*
        ==========PRODUCT QUICK VIEW ON MODAL==========
         */
        $(document).on('click', '.quick-view-btn', function() {

            var modal = $('#quickView');
            var product_id = $(this).data('product');
            $.ajax({
                url: "{{ route('quick.view') }}",
                method: "get",
                data: {
                    id: $(this).data('product')
                },
                success: function(response) {
                    modal.find('.modal-body').html(response);
                    background();
                    backgroundColor();
                    triggerOwl();
                }
            });
            modal.modal('show');
        });

        /*
        ==========QUANTITY BUTTONS FUNCTIONALITIES==========
        */
        $(document).on("click", ".qtybutton", function() {
            var $button = $(this);
            $button.parent().find('.qtybutton').removeClass('active')
            $button.addClass('active');
            var oldValue = $button.parent().find("input").val();
            if ($button.hasClass('inc')) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                if (oldValue > 1) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 1;
                }
            }
            $button.parent().find("input").val(newVal);
        });

        /*
        ==========FUNCTIONALITIES BEFORE ADD TO CART==========
        */

        /*------VARIANT FUNCTIONALITIES-----*/
        $(document).on('click', '.attribute-btn', function() {
            var btn = $(this);
            var ti = btn.data('ti'); // Track Inventory
            var count_total = btn.data('attr_count');
            var discount = btn.data('discount');
            var product_id = btn.data('product_id');
            var attr_data_size = btn.data('size');
            var attr_data_color = btn.data('bg');
            var attr_arr = [];
            var base_price = parseFloat(btn.data('base_price'));
            var extra_price = 0;
            btn.parents('.attr-area:first').find('.attribute-btn').removeClass('active');
            btn.addClass('active');

            if (btn.data('type') == 2 || btn.data('type') == 3) {
                $.ajax({
                    url: "{{ route('product.variant.image') }}",
                    method: "GET",
                    data: {
                        'id': btn.data('id')
                    },
                    success: function(data) {
                        if (!data.error) {
                            btn.parents('.product-details-wrapper').find('.variant-images').html(data);
                            initZoom($('.variant-images').find('.zoom_img'));
                            triggerOwl();
                        }
                    }
                });
            }

            if ($(document).find('.attribute-btn.active').length == count_total) {
                var activeAttributes = $(document).find('attribute-btn.active');

                $(document).find('.attribute-btn.active').each(function(key, attr) {
                    extra_price += parseFloat($(attr).data('price'));
                    var id = $(attr).data('id');
                    attr_arr.push(id.toString());
                });

                var selectedAttributes = JSON.stringify(attr_arr.sort());
                if (ti == 1) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('product.variant.stock') }}",
                        data: {
                            product_id: product_id,
                            attr_id: selectedAttributes
                        },
                        dataType: "json",
                        success: function(response) {
                            $('.product-sku').text(`${response.sku}`);
                            $('.stock-qty').text(`${response.quantity}`);
                            if (response.quantity == 0) {
                                $('.stock-status').addClass('badge--danger').removeClass('badge--success');
                                notify('error', 'Out of stock');
                                $('.cart-add-btn').attr('disabled', true);
                            } else {
                                $('.stock-status').addClass('badge--success').removeClass('badge--danger');
                                $('.cart-add-btn').attr('disabled', false);
                            }
                        }
                    });
                } else {
                    $('.stock-status').addClass('d-none');
                }
            }

            if (extra_price > 0) {
                base_price += extra_price;
                $('.price-data').text(base_price.toFixed(2));
                $('.special_price').text(base_price.toFixed(2) - discount);

            } else {
                $('.price-data').text(base_price.toFixed(2));
                $('.special_price').text(base_price.toFixed(2) - discount);
            }

        });


        /*
        ==========FUNCTIONALITIES AFTER ADD TO CART==========
        */

        /*------ADD TO CART-----*/
        $(document).on('click', '.cart-add-btn', function(e) {
            var product_id = $(this).data('id');
            var attributes = $('.attribute-btn.active');
            var output = '';
            attributes.each(function(key, attr) {
                output += `<input type="hidden" name="selected_attributes[]" value="${$(attr).data('id')}">`
            });
            $('.attr-data').html(output);

            var quantity = $('input[name="quantity"]').val();
            var attributes = $("input[name='selected_attributes[]']").map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                url: "{{ route('add-to-cart') }}",
                method: "POST",
                data: {
                    product_id: product_id,
                    quantity: quantity,
                    attributes: attributes
                },
                success: function(response) {
                    if (response.success) {
                        getCartData();
                        getCartTotal();
                        notify('success', response.success);
                    } else {
                        notify('error', response);
                    }
                }
            });

        });

        /*------REMOVE PRODUCTS FROM CART-----*/
        $(document).on('click', '.remove-cart-item', function(e) {
            var btn = $(this);
            var id = btn.data('id');
            var parent = btn.parents('.cart-row');
            var subTotal = parseFloat($('#cartSubtotal').text());
            var thisPrice = parseFloat(parent.find('.total_price').text());

            var url = `{{ route('remove-cart-item', '') }}/${id}`;
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                url: url,
                method: "POST",
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success);
                        parent.hide(300);

                        if (thisPrice) {
                            $('#cartSubtotal').text((subTotal - thisPrice).toFixed(2));
                        }
                        getCartData();
                        getCartTotal();
                    } else {
                        notify('error', response.error);
                    }
                }
            });
        });


        /*------REMOVE APPLIED COUPON FROM CART-----*/
        $(document).on('click', '.remove-coupon', function(e) {
            var btn = $(this);
            var url = '{{ route('removeCoupon', '') }}';
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                url: url,
                method: "POST",
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success);
                        getCartData();

                        $('.coupon-amount-total').hide('slow');
                        $('input[name=coupon_code]').val('')
                    }
                }
            });
        });

        /*
        ==========WISHLIST FUNCTIONALITIES==========
        */

        /* ADD TO WISHLIST */
        $(document).on('click', '.add-to-wish-list', function() {

            if ($(this).hasClass('active')) {
                notify('error', 'Already in the wishlist');
                return;
            }

            var productId = $(this).data('id');
            var products = $(`.add-to-wish-list[data-id="${productId}"]`);

            $.ajax({
                url: "{{ route('wishlist.add.product') }}",
                method: "get",
                data: {
                    product_id: productId
                },
                success: function(response) {
                    if (response.status) {
                        getWishlistData();
                        getWishlistTotal();

                        $.each(products, function(i, v) {
                            if (!$(v).hasClass('active')) {
                                $(v).addClass('active');
                            }
                        });
                        notify('success', response.message);

                    } else {
                        notify('error', response.message);
                    }
                }
            });
        });

        /* REMOVE FROM WISHLIST */
        $(document).on('click', '.remove-wishlist', function(e) {
            var id = $(this).data('id');
            var url = '{{ route('wishlist.remove.product', '') }}' + '/' + id;
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                url: url,
                method: "POST",
                success: function(response) {
                    if (response.status) {
                        getWishlistData();
                        getWishlistTotal();
                        notify('success', response.message);
                    } else {
                        notify('error', response.message);
                    }
                }
            });

        });

        //ADD TO Compare
        $(document).on('click', '.add-to-compare', function() {
            var product_id = $(this).data('id');
            var products = $(`.add-to-compare[data-id="${product_id}"]`);

            var data = {
                product_id: product_id
            }

            if ($(this).hasClass('active')) {
                notify('error', 'Already in the comparison list');
            } else {
                $.ajax({
                    url: "{{ route('addToCompare') }}",
                    method: "get",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            getCompareData();
                            $.each(products, function(i, v) {
                                if (!$(v).hasClass('active')) {
                                    $(v).addClass('active');
                                }
                            });
                            notify('success', response.success);
                        } else {
                            notify('error', response.error);
                        }
                    }
                });
            }
        });

    })(jQuery);

    function getWishlistData() {
        $.ajax({
            url: "{{ route('wishlist.get.data') }}",
            method: "get",
            success: function(response) {
                $('.wish-products').html(response);
            }
        });
    }

    function getWishlistTotal() {
        $.ajax({
            url: "{{ route('get-wishlist-total') }}",
            method: "get",
            success: function(response) {
                $('.wishlist-count').text(response);
            }
        });
    }

    function getCartTotal() {
        $.ajax({
            url: "{{ route('get-cart-total') }}",
            method: "get",
            success: function(response) {
                $('.cart-count').text(response);
            }
        });
    }

    function getCartData() {
        $.ajax({
            url: "{{ route('get-cart-data') }}",
            method: "get",
            success: function(response) {
                $('.cart--products').html(response);
            }
        });
    }

    function backgroundColor() {
        var customBg2 = $('.product-single-color');
        customBg2.css('background', function() {
            var bg = ('#' + $(this).data('bg'));
            return bg;
        });
    }

    function background() {
        var img = $('.bg_img');
        img.css('background-image', function() {
            var bg = ('url(' + $(this).data('background') + ')');
            return bg;
        });
    }

    function getCompareData() {
        $.ajax({
            url: "{{ route('get-compare-data') }}",
            method: "get",
            success: function(response) {
                $('.compare-count').text(response.total);
            }
        });
    }
</script>
