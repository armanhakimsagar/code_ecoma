<?php

use Illuminate\Support\Facades\Route;

Route::name('seller.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('login', 'showLoginForm')->name('login');
            Route::post('login', 'login');
            Route::get('logout', 'logout')->name('logout');
        });

        Route::controller('RegisterController')->group(function () {
            Route::get('register', 'showRegistrationForm')->name('register');
            Route::post('register', 'register');
            Route::post('check-mail', 'checkSeller')->name('check.seller');
        });

        Route::controller('ForgotPasswordController')->group(function () {
            Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
            Route::post('password/email', 'sendResetCodeEmail')->name('password.email');
            Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
            Route::post('password/reset', 'reset')->name('password.update');
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
            Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
        });
    });

    Route::middleware('seller')->group(function () {
        Route::controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorizeForm')->name('authorization');
            Route::get('resend-verify', 'sendVerifyCode')->name('send.verify.code');
            Route::post('verify-email', 'emailVerification')->name('verify.email');
            Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
            Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
        });

        Route::middleware('sellerCheckStatus')->group(function () {
            Route::get('seller-data', 'SellerController@sellerData')->name('data');
            Route::post('seller-data-submit', 'SellerController@sellerDataSubmit')->name('data.submit');

            Route::middleware('seller.registration.complete')->group(function () {
                Route::controller('SellerController')->group(function () {
                    Route::get('/', 'home')->name('home');
                    Route::get('profile', 'profile')->name('profile');
                    Route::post('profile', 'submitProfile');
                    Route::get('change-password', 'changePassword')->name('password');
                    Route::post('password-update', 'submitPassword')->name('password.update');
                    Route::get('twofactor', 'show2faForm')->name('twofactor');
                    Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                    Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                    //KYC
                    Route::get('kyc-form', 'kycForm')->name('kyc.form');
                    Route::get('kyc-data', 'kycData')->name('kyc.data');
                    Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                    // Shop Setting
                    Route::get('/shop', 'shop')->name('shop');
                    Route::post('/shop', 'shopUpdate');

                    //Sell log
                    Route::get('sales-log', 'sellLogs')->name('sell.log');
                    Route::get('transaction-logs', 'transactions')->name('transactions');

                    // Withdraw
                    Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
                    Route::get('withdraw-chart-data', 'withdrawChartData')->name('withdraw.chart.data');
                });

                //Manage Products
                Route::controller('ProductController')->group(function () {
                    Route::get('products', 'index')->name('products.all');
                    Route::get('products/pending', 'pending')->name('products.pending');
                    Route::get('product/create', 'create')->name('products.create');
                    Route::post('product/store/{id}', 'store')->name('products.product.store');
                    Route::get('product/edit/{id}', 'edit')->name('products.edit');
                    Route::post('product/delete/{id}', 'delete')->name('products.delete');
                    Route::post('product/restore/{id}', 'restore')->name('products.restore');
                    Route::get('product/search/', 'productSearch')->name('products.search');
                    Route::get('product/trashed', 'trashed')->name('products.trashed');
                    Route::get('product/trashed/search', 'productTrashedSearch')->name('products.trashed.search');
                    Route::get('product/reviews', 'reviews')->name('products.reviews');
                    Route::get('product/reviews/search/{key?}', 'reviewSearch')->name('products.reviews.search');

                    Route::get('product/add-variant/{id}', 'addVariant')->name('products.variant.store');
                    Route::post('product/add-variant/{id}', 'storeVariant')->name('products.variant.store');
                    Route::get('product/edit-variant/{pid}/{aid}', 'editAttribute')->name('products.variant.edit');
                    Route::post('product/edit-variant-update/{id}', 'updateVariant')->name('products.variant.update');
                    Route::post('product/delete-variant/{id}', 'deleteVariant')->name('products.variant.delete');

                    Route::get('product/add-variant-images/{id}', 'addVariantImages')->name('products.add-variant-images');
                    Route::post('product/add-variant-images/{id}', 'saveVariantImages');

                    Route::post('check-slug', 'checkSlug')->name('products.slug.check');
                });

                //Stock
                Route::controller('ProductStockController')->group(function () {
                    Route::any('product/stock/create/{product_id}', 'stockCreate')->name('products.stock.create');
                    Route::post('product/add-to-stock/{product_id}', 'stockAdd')->name('products.stock.add');
                    Route::get('product/stock/{id}/', 'stockLog')->name('products.stock.log');
                });

                //Order
                Route::controller('OrderController')->name('order.')->prefix('orders')->group(function () {
                    Route::get('all', 'all')->name('all');
                    Route::get('pending', 'pending')->name('pending');
                    Route::get('processing', 'processing')->name('processing');
                    Route::get('ready-to-pickup', 'readyToPickUp')->name('ready.to.pickup');
                    Route::get('rejected', 'rejected')->name('rejected');
                    Route::get('delivered', 'delivered')->name('delivered');

                    Route::post('mark-as-processing/{id}', 'markAsProcessing')->name('mark.as.processing');
                    Route::post('mark-as-ready-to-pickup/{id}', 'markAsReadyToPickUp')->name('mark.as.ready.to.pickup');
                    Route::post('reject/{id}', 'reject')->name('reject');

                    Route::get('details/{id}', 'orderDetails')->name('details');
                });

                // Withdraw
                Route::middleware('kyc')->controller('WithdrawController')->prefix('withdraw')->group(function () {
                    Route::get('/', 'withdrawMoney')->name('withdraw');
                    Route::post('withdraw-store', 'withdrawStore')->name('withdraw.money');
                    Route::get('preview', 'withdrawPreview')->name('withdraw.preview');
                    Route::post('withdraw-submit', 'withdrawSubmit')->name('withdraw.submit');
                    Route::get('history', 'withdrawLog')->name('withdraw.history');
                });

                // Support Ticket
                Route::controller('TicketController')->prefix('tickets')->group(function () {
                    Route::get('/', 'index')->name('ticket.index');
                    Route::get('open', 'openNewTicket')->name('ticket.open');
                    Route::post('open/store', 'storeSupportTicket')->name('ticket.store');
                    Route::get('view/{ticket}', 'viewTicket')->name('ticket.view');
                    Route::post('reply/{ticket}', 'reply')->name('ticket.reply');
                });
            });
        });
    });
});
