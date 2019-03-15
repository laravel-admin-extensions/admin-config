<?php

namespace Fourn\AdminConfig;

use Illuminate\Support\ServiceProvider;

class AdminConfigServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(AdminConfig $extension)
    {
        if (! AdminConfig::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'admin-config');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/fourn/admin-config')],
                'admin-config'
            );
        }

        $this->app->booted(function () {
            AdminConfig::routes(__DIR__.'/../routes/web.php');
        });
    }
}