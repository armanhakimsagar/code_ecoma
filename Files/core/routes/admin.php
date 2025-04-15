<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Auth')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login');
            Route::get('logout', 'logout')->middleware('admin')->withoutMiddleware('admin.guest')->name('logout');
        });

        // Admin Password Reset
        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('reset');
            Route::post('reset', 'sendResetCodeEmail');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function () {
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
            Route::post('password/reset/change', 'reset')->name('password.change');
        });
    });
});

Route::middleware('admin')->group(function () {
    Route::controller('AdminController')->group(function () {
        Route::get('analytics/all-shop', 'dashboard')->name('dashboard');
        Route::get('analytics/my-shop', 'myDashboard')->name('dashboard.self');

        Route::get('chart/sales-withdraw', 'salesAndWithdrawReport')->name('chart.sales.withdraw');
        Route::get('chart/transaction', 'transactionReport')->name('chart.transaction');
        Route::get('chart/my-sales', 'mySalesReport')->name('chart.my.sales');

        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAllNotification')->name('notifications.read.all');
        Route::post('notifications/delete-all', 'deleteAllNotification')->name('notifications.delete.all');
        Route::post('notifications/delete-single/{id}', 'deleteSingleNotification')->name('notifications.delete.single');

        //Report Bugs
        Route::get('request-report', 'requestReport')->name('request.report');
        Route::post('request-report', 'reportSubmit');

        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });

    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('users')->group(function () {
        Route::get('/', 'allUsers')->name('all');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');
        Route::get('email-verified', 'emailVerifiedUsers')->name('email.verified');
        Route::get('email-unverified', 'emailUnverifiedUsers')->name('email.unverified');
        Route::get('mobile-unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
        Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');

        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
        Route::get('list', 'list')->name('list');
        Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
    });

    // Sellers Manager
    Route::controller('ManageSellersController')->name('sellers.')->prefix('sellers')->group(function () {
        Route::get('/', 'allSellers')->name('all');
        Route::get('active', 'activeSellers')->name('active');
        Route::get('banned', 'bannedSellers')->name('banned');
        Route::get('email-verified', 'emailVerifiedSellers')->name('email.verified');
        Route::get('email-unverified', 'emailUnverifiedSellers')->name('email.unverified');
        Route::get('mobile-unverified', 'mobileUnverifiedSellers')->name('mobile.unverified');
        Route::get('mobile-verified', 'mobileVerifiedSellers')->name('mobile.verified');
        Route::get('kyc-pending', 'kycPendingSellers')->name('kyc.pending');
        Route::get('kyc-verified', 'kycUnverifiedSellers')->name('kyc.unverified');
        Route::get('with-balance', 'sellersWithBalance')->name('with.balance');

        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('add/sub/balance/{id}', 'addSubBalance')->name('add.sub.balance');

        Route::get('kyc/detail/{id}', 'kycDetails')->name('kyc.details');
        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
        Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject');

        // Notification
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');
        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
        Route::get('list', 'list')->name('list');
        Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
        Route::get('login/{id}', 'login')->name('login');

        Route::get('shop-detail/{id}', 'shopDetails')->name('shop.details');
        Route::post('shop-update', 'shopUpdate')->name('shop.update');


        Route::post('feature-status/{id}', 'featureStatus')->name('feature');
        Route::get('sell-logs/{id}', 'sellLogs')->name('sell.logs');
        Route::get('products/{id}', 'sellerProducts')->name('products');

        Route::post('status/{id}', 'status')->name('status');
    });

    //Category Setting
    Route::controller('CategoryController')->name('category.')->prefix('categories')->group(function () {
        Route::get('/', 'index')->name('all');
        Route::get('trashed', 'trashed')->name('trashed');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::post('category-menu-update', 'updatePosition')->name('update.position');
        Route::get('category/{id}', 'categoryById')->name('get.single');
    });

    //Brand
    Route::controller('BrandController')->prefix('brands')->name('brand.')->group(function () {
        Route::get('', 'index')->name('all');
        Route::get('trashed', 'trashed')->name('trashed');
        Route::post('status/{id}', 'changeStatus')->name('status');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    //Product Attributes
    Route::controller('ProductAttributeController')->name('attributes.')->prefix('attribute')->group(function () {
        Route::get('/', 'index')->name('all');
        Route::get('create', 'create')->name('create');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('edit/{id}/', 'edit')->name('edit');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    // Manage Products
    Route::controller('ProductController')->name('products.')->prefix('product')->group(function () {
        Route::get('all', 'index')->name('all');
        Route::get('admin', 'adminProducts')->name('admin');
        Route::get('seller', 'sellerProducts')->name('seller');
        Route::get('pending', 'pending')->name('pending');
        Route::get('create', 'create')->name('create');
        Route::post('store/{id}', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::post('restore/{id}', 'restore')->name('restore');
        Route::get('search/', 'productSearch')->name('search');
        Route::get('trashed', 'trashed')->name('trashed');
        Route::post('featured/{id}', 'featured')->name('featured');
        Route::post('status/action/{id}', 'statusAction')->name('action');
        Route::post('approve/all', 'approveAll')->name('approve.all');

        Route::get('reviews', 'reviews')->name('reviews');
        Route::get('reviews/trashed', 'trashedReviews')->name('reviews.trashed');
        Route::post('review/status/{id}', 'changeReviewStatus')->name('review.status');

        Route::get('add-variant/{id}', 'addVariant')->name('variant.store');
        Route::post('add-variant/{id}', 'storeVariant')->name('variant.store');
        Route::get('edit-variant/{pid}/{aid}', 'editAttribute')->name('variant.edit');
        Route::post('edit-variant-update/{id}', 'updateVariant')->name('variant.update');
        Route::post('delete-variant/{id}', 'deleteVariant')->name('variant.delete');
        Route::get('add-variant-images/{id}', 'addVariantImages')->name('add-variant-images');
        Route::post('add-variant-images/{id}', 'saveVariantImages');

        Route::post('check-slug', 'checkSlug')->name('slug.check');
    });

    //Stock
    Route::controller('ProductStockController')->name('products.stock.')->prefix('product/stock')->group(function () {
        Route::any('create/{product_id}', 'stockCreate')->name('create');
        Route::post('add/{product_id}', 'stockAdd')->name('add');
        Route::get('log/{id}/', 'stockLog')->name('log');
        Route::get('stocks', 'stocks')->name('stocks');
        Route::get('stocks/low', 'stocksLow')->name('stocks.low');
        Route::get('stocks/empty', 'stocksEmpty')->name('stocks.empty');
    });

    //Order
    Route::controller('OrderController')->prefix('order')->name('order.')->group(function () {
        Route::post('status', 'changeStatus')->name('status');
        Route::get('all', 'allOrders')->name('all');
        Route::get('pending', 'pending')->name('pending');
        Route::get('processing', 'processing')->name('processing');
        Route::get('ready-to-pickup/{order?}', 'readyToPickup')->name('ready.to.pickup');
        Route::get('ready-to-deliver', 'readyToDeliver')->name('ready.to.deliver');
        Route::get('dispatched', 'dispatched')->name('dispatched');
        Route::get('delivered', 'deliveredOrders')->name('delivered');
        Route::get('canceled', 'canceledOrders')->name('canceled');
        Route::get('cod', 'codOrders')->name('cod');
        Route::post('send-cancellation-alert/{id}', 'sendCancellationAlert')->name('send.cancellation.alert');
        Route::get('order/details/{id}', 'orderDetails')->name('details');
        Route::get('sales-log/', 'adminSellsLog')->name('sells.log.admin');
        Route::get('sales-log/seller', 'sellerSellsLog')->name('sells.log.seller');

        Route::post('soborder-refund/{suborder}', 'refundSubOrder')->name('suborder.refund');

        Route::post('refund/{order}', 'refundOrder')->name('refund');

        Route::get('invoice/{id}', 'invoice')->name('invoice');
        Route::get('print-invoice/{id}', 'printInvoice')->name('invoice.print');
    });

    // admin's product suborder manage
    Route::controller('AdminOrderController')->prefix('suborder')->name('suborder.')->group(function(){
        Route::get('all', 'all')->name('all');
        Route::get('pending', 'pending')->name('pending');
        Route::get('processing', 'processing')->name('processing');
        Route::get('picked-up', 'pickedUp')->name('picked.up');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('detail/{id}', 'detail')->name('detail');

        Route::post('reject/{id}', 'reject')->name('reject');
        Route::post('process/{id}', 'markAsProcessing')->name('mark.as.processing');
        Route::post('pickup/{id}', 'markAsPickedUp')->name('mark.as.picked.up');
    });

    //Coupons

    Route::controller('ManageCouponController')->name('coupon.')->prefix('coupon')->group(function () {
        Route::get('all', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('save/{id}', 'save')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::post('coupon-status', 'changeStatus')->name('status');
        Route::get('products', 'products')->name('products');
    });

    //Offers
    Route::controller('ManageOfferController')->name('offer.')->prefix('offer')->group(function () {
        Route::get('all', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('save/{id}', 'save')->name('store');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::post('offer/status', 'changeStatus')->name('status');
        Route::get('products', 'products')->name('products');
    });

    // Subscriber
    Route::controller('SubscriberController')->prefix('subscriber')->name('subscriber.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('send-email', 'sendEmailForm')->name('send.email');
        Route::post('remove/{id}', 'remove')->name('remove');
        Route::post('send-email', 'sendEmail')->name('send.email');
    });

    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {
        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->prefix('automatic')->name('automatic.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });


        // Manual Methods
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'create')->name('create');
            Route::post('new', 'store')->name('store');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });


    // DEPOSIT SYSTEM
    Route::controller('DepositController')->prefix('payment')->name('deposit.')->group(function () {
        Route::get('all/{user_id?}', 'deposit')->name('list');
        Route::get('pending/{user_id?}', 'pending')->name('pending');
        Route::get('rejected/{user_id?}', 'rejected')->name('rejected');
        Route::get('approved/{user_id?}', 'approved')->name('approved');
        Route::get('successful/{user_id?}', 'successful')->name('successful');
        Route::get('initiated/{user_id?}', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');
        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });


    // WITHDRAW SYSTEM
    Route::name('withdraw.')->prefix('withdraw')->group(function () {

        Route::controller('WithdrawalController')->name('data.')->group(function () {
            Route::get('pending/{user_id?}', 'pending')->name('pending');
            Route::get('approved/{user_id?}', 'approved')->name('approved');
            Route::get('rejected/{user_id?}', 'rejected')->name('rejected');
            Route::get('all/{user_id?}', 'all')->name('all');
            Route::get('details/{id}', 'details')->name('details');
            Route::post('approve', 'approve')->name('approve');
            Route::post('reject', 'reject')->name('reject');
        });

        // Withdraw Method
        Route::controller('WithdrawMethodController')->prefix('method')->name('method.')->group(function () {
            Route::get('/', 'methods')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('create', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('edit/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    // Report
    Route::controller('ReportController')->prefix('report')->name('report.')->group(function () {
        Route::get('user/transactions/{user_id?}', 'userTransaction')->name('user.transaction');
        Route::get('seller/transactions/{seller_id?}', 'sellerTransaction')->name('seller.transaction');

        Route::get('user/login/history', 'userLoginHistory')->name('user.login.history');
        Route::get('user/login/ipHistory/{ip}', 'userLoginIpHistory')->name('user.login.ipHistory');
        Route::get('seller/login/history', 'sellerLoginHistory')->name('seller.login.history');
        Route::get('seller/login/ipHistory/{ip}', 'sellerLoginIpHistory')->name('seller.login.ipHistory');

        Route::get('user/notification/history', 'userNotificationHistory')->name('user.notification.history');
        Route::get('seller/notification/history', 'sellerNotificationHistory')->name('seller.notification.history');
        Route::get('email/detail/{id}', 'emailDetails')->name('email.details');

        Route::get('commission-log', 'commissionLogs')->name('commission.log');

        Route::get('order/{user_id}', 'orderLogs')->name('order');
    });


    // Admin Support
    Route::controller('SupportTicketController')->prefix('ticket')->name('ticket.')->group(function () {
        Route::get('/', 'tickets')->name('index');
        Route::get('pending', 'pendingTicket')->name('pending');
        Route::get('closed', 'closedTicket')->name('closed');
        Route::get('answered', 'answeredTicket')->name('answered');
        Route::get('view/{id}', 'ticketReply')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply');
        Route::post('close/{id}', 'closeTicket')->name('close');
        Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
        Route::post('delete/{id}', 'ticketDelete')->name('delete');
    });


    // Language Manager
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function () {
        Route::get('/', 'langManage')->name('manage');
        Route::post('/', 'langStore')->name('manage.store');
        Route::post('delete/{id}', 'langDelete')->name('manage.delete');
        Route::post('update/{id}', 'langUpdate')->name('manage.update');
        Route::get('edit/{id}', 'langEdit')->name('key');
        Route::post('import', 'langImport')->name('import.lang');
        Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
        Route::post('delete/key/{id}', 'deleteLanguageJson')->name('delete.key');
        Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
        Route::get('get-keys', 'getKeys')->name('get.key');
    });

    Route::controller('GeneralSettingController')->group(function () {

        Route::get('system-setting', 'systemSetting')->name('setting.system');

        // General Setting
        Route::get('general-setting', 'general')->name('setting.general');
        Route::post('general-setting', 'generalUpdate');

        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update');

        //configuration
        Route::get('setting/system-configuration', 'systemConfiguration')->name('setting.system.configuration');
        Route::post('setting/system-configuration', 'systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css');
        Route::post('custom-css', 'customCssSubmit');

        Route::get('sitemap', 'sitemap')->name('setting.sitemap');
        Route::post('sitemap', 'sitemapSubmit');

        Route::get('robot', 'robot')->name('setting.robot');
        Route::post('robot', 'robotSubmit');

        //Cookie
        Route::get('cookie', 'cookie')->name('setting.cookie');
        Route::post('cookie', 'cookieSubmit');

        //maintenance_mode
        Route::get('maintenance-mode', 'maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode', 'maintenanceModeSubmit');
    });

    //Shipping Methods
    Route::controller('ShippingMethodController')->prefix('shipping-method')->name('shipping.methods.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
    });

    //KYC setting
    Route::controller('KycController')->group(function () {
        Route::get('kyc-setting', 'setting')->name('kyc.setting');
        Route::post('kyc-setting', 'settingUpdate');
    });

    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function () {
        //Template Setting
        Route::get('global/email', 'globalEmail')->name('global.email');
        Route::post('global/email/update', 'globalEmailUpdate')->name('global.email.update');

        Route::get('global/sms', 'globalSms')->name('global.sms');
        Route::post('global/sms/update', 'globalSmsUpdate')->name('global.sms.update');

        Route::get('global/push', 'globalPush')->name('global.push');
        Route::post('global/push/update', 'globalPushUpdate')->name('global.push.update');

        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{type}/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{type}/{id}', 'templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting', 'emailSettingUpdate');
        Route::post('email/test', 'emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting', 'smsSetting')->name('sms');
        Route::post('sms/setting', 'smsSettingUpdate');
        Route::post('sms/test', 'smsTest')->name('sms.test');

        Route::get('notification/push/setting', 'pushSetting')->name('push');
        Route::post('notification/push/setting', 'pushSettingUpdate');
        Route::post('notification/push/setting/upload', 'pushSettingUpload')->name('push.upload');
        Route::get('notification/push/setting/download', 'pushSettingDownload')->name('push.download');
    });

    // Plugin
    Route::controller('ExtensionController')->prefix('extensions')->name('extensions.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });


    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function () {
        Route::get('info', 'systemInfo')->name('info');
        Route::get('server-info', 'systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
        Route::get('system-update', 'systemUpdate')->name('update');
        Route::post('system-update', 'systemUpdateProcess')->name('update.process');
        Route::get('system-update/log', 'systemUpdateLog')->name('update.log');
    });


    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');


    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key?}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::get('frontend-slug-check/{key}/{id?}', 'frontendElementSlugCheck')->name('sections.element.slug.check');
            Route::get('frontend-element-seo/{key}/{id}', 'frontendSeo')->name('sections.element.seo');
            Route::post('frontend-element-seo/{key}/{id}', 'frontendSeoUpdate');
            Route::post('remove/{id}', 'remove')->name('remove');
        });
    });
});
