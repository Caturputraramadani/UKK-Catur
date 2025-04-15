<?php

namespace App\Providers;
use Carbon\Carbon;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;


class AppServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    */
    public function register(): void
    {
        //
    }

    /**
    * Bootstrap any application services.
    */
    public function boot(): void
    {
        Route::aliasMiddleware('role', RoleMiddleware::class);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta'); // This will affect all date/time functions
    }
}
