<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthenticateSeller;
use App\Http\Middleware\CheckStatus;
use App\Http\Middleware\Demo;
use App\Http\Middleware\KycMiddleware;
use App\Http\Middleware\MaintenanceMode;
use App\Http\Middleware\RedirectIfAdmin;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RedirectIfNotAdmin;
use App\Http\Middleware\RedirectIfSeller;
use App\Http\Middleware\RegistrationStep;
use App\Http\Middleware\SellerCheckStatus;
use App\Http\Middleware\SellerRegistrationStep;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Laramin\Utility\VugiChugi;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            Route::namespace('App\Http\Controllers')->middleware([VugiChugi::mdNm()])->group(function () {
                Route::prefix('api')
                    ->middleware(['api', 'maintenance'])
                    ->group(base_path('routes/api.php'));
                Route::middleware(['web'])
                    ->namespace('Admin')
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));

                Route::prefix('seller')
                    ->middleware('web')
                    ->namespace('Seller')
                    ->group(base_path('routes/seller.php'));

                Route::middleware(['web', 'maintenance'])
                    ->namespace('Gateway')
                    ->prefix('ipn')
                    ->name('ipn.')
                    ->group(base_path('routes/ipn.php'));

                Route::middleware(['web', 'maintenance'])->prefix('customer')->group(base_path('routes/user.php'));
                Route::middleware(['web', 'maintenance'])->group(base_path('routes/web.php'));
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LanguageMiddleware::class,
            \App\Http\Middleware\ActiveTemplateMiddleware::class,
        ]);

        $middleware->alias([
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            'seller' => AuthenticateSeller::class,
            'seller.guest' => RedirectIfSeller::class,
            'sellerCheckStatus' => SellerCheckStatus::class,

            'admin' => RedirectIfNotAdmin::class,
            'admin.guest' => RedirectIfAdmin::class,

            'check.status' => CheckStatus::class,
            'registration.complete' => RegistrationStep::class,
            'seller.registration.complete' => SellerRegistrationStep::class,
            'demo' => Demo::class,
            'kyc' => KycMiddleware::class,
            'maintenance' => MaintenanceMode::class,
        ]);

        $middleware->validateCsrfTokens(
            except: ['user/deposit', 'ipn*']
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function () {
            if (request()->is('api/*')) {
                return true;
            }
        });
        $exceptions->respond(function (Response $response) {
            if ($response->getStatusCode() === 401) {
                if (request()->is('api/*')) {
                    $notify[] = 'Unauthorized request';
                    return response()->json([
                        'remark' => 'unauthenticated',
                        'status' => 'error',
                        'message' => ['error' => $notify]
                    ]);
                }
            }

            return $response;
        });
    })->create();
