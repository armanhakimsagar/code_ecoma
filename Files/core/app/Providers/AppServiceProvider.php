<?php

namespace App\Providers;

use App\Constants\Status;
use App\Lib\Searchable;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Seller;
use App\Models\SubOrder;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::mixin(new Searchable);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!cache()->get('SystemInstalled')) {
            $envFilePath = base_path('.env');
            if (!file_exists($envFilePath)) {
                header('Location: install');
                exit;
            }
            $envContents = file_get_contents($envFilePath);
            if (empty($envContents)) {
                header('Location: install');
                exit;
            } else {
                cache()->put('SystemInstalled', true);
            }
        }


        $viewShare['emptyMessage'] = 'Data not found';

        $viewShare['allCategories'] = Category::with(['allSubcategories', 'products' => function ($q) {
            return $q->whereHas('categories')->whereHas('brand');
        }, 'products.reviews', 'products.offer.activeOffer'])
            ->where('parent_id', null)->get();

        view()->share($viewShare);


        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'             => User::banned()->count(),
                'emailUnverifiedUsersCount'    => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount'   => User::mobileUnverified()->count(),
                'bannedSellersCount'           => Seller::banned()->count(),
                'kycPendingSellersCount'       => Seller::kycPending()->count(),
                'kycUnverifiedSellersCount'    => Seller::kycUnverified()->count(),
                'emailUnverifiedSellersCount'  => Seller::emailUnverified()->count(),
                'mobileUnverifiedSellersCount' => Seller::mobileUnverified()->count(),
                'pendingTicketCount'           => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount'         => Deposit::pending()->count(),
                'pendingWithdrawCount'         => Withdrawal::pending()->count(),
                'pendingProductsCount'         => Product::pending()->count(),
                'pendingOrdersCount'           => Order::pending()->count(),
                'pendingProductsCount'         => Product::where('seller_id', '!=', 0)->pending()->count(),

                'processingOrdersCount'        => Order::processing()->count(),
                'dispatchedOrdersCount'        => Order::dispatched()->count(),
                'readyToPickupSubOrdersCount'  => SubOrder::orderNotCanceled()->readyToPickup()->count(),
                'readyToDeliverOrdersCount'    => Order::readyToDeliver()->count(),

                'pendingSubOrdersCount'        => SubOrder::admin()->orderNotCanceled()->pending()->count(),
                'processingSubOrdersCount'     => SubOrder::admin()->orderNotCanceled()->processing()->count(),

                'updateAvailable'              => version_compare(gs('available_version'), systemDetails()['version'], '>') ? 'v' . gs('available_version') : false,
            ]);
        });

        view()->composer('seller.partials.sidenav', function ($view) {
            $view->with([
                'pendingProductsCount'         => Product::belongsToSeller()->pending()->count(),
                'pendingOrdersCount'           => SubOrder::belongsToSeller()->valid()->orderNotCanceled()->pending()->count(),
                'processingOrdersCount'        => SubOrder::belongsToSeller()->valid()->orderNotCanceled()->processing()->count(),
                'readyToPickupOrdersCount'     => SubOrder::belongsToSeller()->valid()->orderNotCanceled()->readyToPickup()->count(),
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if (gs('force_ssl')) {
            \URL::forceScheme('https');
        }


        Paginator::useBootstrapFive();
    }
}
