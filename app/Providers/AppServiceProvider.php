<?php

namespace App\Providers;

use App\Services\SystemStatusService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale(config('app.locale', 'id'));
        \Carbon\CarbonImmutable::setLocale(config('app.locale', 'id'));

        View::composer(['layouts.partials.navbar', 'layouts.app'], function ($view) {
            $view->with('headerStatuses', SystemStatusService::getHeaderStatuses());
        });
    }
}
