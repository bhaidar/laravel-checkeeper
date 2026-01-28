<?php

namespace Bhaidar\Checkeeper;

use Bhaidar\Checkeeper\Client\CheckkeeperClient;
use Illuminate\Support\ServiceProvider;

class CheckkeeperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/checkeeper.php',
            'checkeeper'
        );

        $this->app->singleton('checkeeper', function ($app) {
            return new CheckkeeperClient(
                apiKey: config('checkeeper.api_key'),
                baseUrl: config('checkeeper.base_url'),
                timeout: config('checkeeper.timeout'),
                retry: config('checkeeper.retry')
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/checkeeper.php' => config_path('checkeeper.php'),
            ], 'checkeeper-config');
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/webhooks.php');
    }
}
