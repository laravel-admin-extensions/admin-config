<?php

namespace Fourn\AdminConfig;

use Illuminate\Support\ServiceProvider;

class AdminConfigServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (! AdminConfig::boot()) {
            return ;
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/admin-config.php' => config_path('admin-config.php')
            ], 'admin-config');
        }

        $this->app->booted(function () {
            AdminConfig::routes(__DIR__.'/../routes/web.php');
        });
    }
}