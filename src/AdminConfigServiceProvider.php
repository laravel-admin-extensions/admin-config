<?php

namespace Fourn\AdminConfig;

use Illuminate\Support\ServiceProvider;
use Fourn\AdminConfig\Config;

class AdminConfigServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // 数据库迁移
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            // 发布配置文件
            $this->publishes([
                __DIR__.'/../config/admin-config.php' => config_path('admin-config.php')
            ], 'admin-config');
        }

        // 全局引入数据库配置
        if (\Schema::hasTable('admin_config')) {
            foreach (AdminConfigModel::all(['name', 'value']) as $config) {
                config([$config['name'] => $config['value']]);
            }
        }

        // 注册路由
        $this->app->booted(function () {
            AdminConfig::routes(__DIR__.'/../routes/web.php');
            AdminConfig::boot();
        });
    }
}