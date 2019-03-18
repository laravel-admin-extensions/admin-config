admin-config
======

基于[laravel-admin](https://github.com/z-song/laravel-admin)的数据库配置管理工具，仅通过配置文件就可生成整个表单，支持使用tab页对配置项进行分组。



## 安装：

步骤一：使用composer：

```
composer require fourn/admin-config
```

如果你已经依赖并配置好了官方提供的 [config](https://github.com/laravel-admin-extensions/config) 可以跳过下面的步骤

步骤二：全局引入后台配置项：

```php
use Encore\Admin\Config\Config;
class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (\Schema::hasTable('admin_config') && class_exists(Config::class)) {
            Config::load();
        }
    }
}
```

步骤三：执行数据库迁移：

```php
php artisan migrate
```

## 使用方法：

第一步：发布配置文件：

```
artisan vendor:publish --tag=admin-config
```

第二步：在配置文件中加入配置组及配置项

第三步：使用laravel中的config函数

```
// 获取一组
config('sample')
// 获取一项
config('sample.value')
```



## 其他：

你可以生成后台菜单：

```php
php artisan admin:import admin-config
```

或者直接访问：

http://your-host/admin/admin-config

扩展配置：

```php
'extensions' => [
    'admin-config' => [
        'title'=>'AdminConfig',
        'description'=>'Manage your profiles as profiles',
        'action'=>' ',
    ],
],
```



## 效果示例：

配置文件：

![Snipaste_2019-03-18_16-15-06](https://ws2.sinaimg.cn/large/006tKfTcgy1g171pq3t4oj31730pk0vl.jpg)

生成的表单：

![Snipaste_2019-03-18_16-14-05](https://ws2.sinaimg.cn/large/006tKfTcgy1g171q2oy8vj31b70qjwgd.jpg)

数据库：

![Snipaste_2019-03-18_16-21-44](https://ws1.sinaimg.cn/large/006tKfTcgy1g171q8ri68j30uk0fa411.jpg)





