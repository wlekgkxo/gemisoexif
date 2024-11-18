<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\MediaMetaDataService;

class MediaMetaDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MediaMetaDataService::class, function($app) {
            return new MediaMetaDataService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
