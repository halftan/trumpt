<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RssGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('rssGenerator', function () {
            return new RssGenerator();
        });
    }
}
