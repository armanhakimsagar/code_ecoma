<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

// Product Details
Route::controller('ShopController')->group(function () {
    Route::get('products', 'products')->name('products');
    Route::get('products/filter', 'productsFilter')->name('products.filter');

    Route::get('quick-view', 'quickView')->name('quick.view');
    Route::get('product/{slug}', 'productDetails')->name('product.detail');

    Route::get('product-variant-stock', 'getVariantStock')->name('product.variant.stock');
    Route::get('product-variant-image', 'getVariantImage')->name('product.variant.image');
    Route::get('products/search', 'productSearch')->name('product.search');
    Route::get('products/search/{perpage?}', 'productSearch')->name('product.search.filter');
    Route::get('product-reviews', 'loadMoreReviews')->name('product.review.load.more');


    //Compare
    Route::get('add_to_compare/', 'addToCompare')->name('addToCompare');
    Route::get('get_compare_data/', 'getCompare')->name('get-compare-data');
    Route::get('compare/', 'compare')->name('compare');
    Route::post('remove_from_compare/{id}', 'removeFromCompare')->name('del-from-compare');


    // Categories
    Route::get('categories', 'categories')->name('categories');
    Route::get('category/{id}/{slug}', 'productsByCategory')->name('products.category');
    Route::get('category/filter/{id}/{slug}', 'productsByCategory')->name('category.filter');

    // Brands
    Route::get('brands', 'brands')->name('brands');
    Route::get('brands/{id}/{slug}', 'productsByBrand')->name('products.brand');
    Route::get('brands/filter/{id}/{slug}', 'productsByBrand')->name('brands.filter');

    Route::get('our-sellers', 'allSellers')->name('all.sellers');
    Route::get('seller/{id}-{slug}', 'sellerDetails')->name('seller.details');
});

//Cart
Route::controller('CartController')->group(function () {
    Route::post('add-to-cart/', 'addToCart')->name('add-to-cart');
    Route::get('cart-data', 'getCart')->name('get-cart-data');
    Route::get('get_cart-total/', 'getCartTotal')->name('get-cart-total');
    Route::get('cart/shipping-charge', 'getCartShippingCharge')->name('cart.shipping.charge');
    Route::get('my-cart/', 'shoppingCart')->name('shopping-cart');
    Route::post('remove_cart_item/{id}', 'removeCartItem')->name('remove-cart-item');
    Route::post('update_cart_item/{id}', 'updateCartItem')->name('update-cart-item');
});


Route::controller('CouponController')->group(function () {
    Route::post('apply_coupon/', 'applyCoupon')->name('applyCoupon');
    Route::post('remove_coupon/', 'removeCoupon')->name('removeCoupon');
});

//Wishlist
Route::controller('WishlistController')->group(function () {
    Route::get('wishlist/add-product', 'addToWishList')->name('wishlist.add.product');
    Route::get('get_wishlist_data/', 'getWishList')->name('wishlist.get.data');
    Route::get('get_wishlist_total/', 'getWishListTotal')->name('get-wishlist-total');
    Route::get('wishlist/', 'wishList')->name('wishlist');
    Route::post('wishlist/remove/{id?}', 'removeFromWishList')->name('wishlist.remove.product');
});

Route::controller('OrderController')->group(function () {
    Route::get('track-order', 'trackOrder')->name('orderTrack');
    Route::post('track-order', 'getOrderTrackData')->name('order.track');
});

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('pages/{slug}', 'pageDetails')->name('page.details');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::post('subscribe', 'subscribe')->name('subscribe');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
