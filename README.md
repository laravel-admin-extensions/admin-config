admin-config
======

基于[laravel-admin](https://github.com/z-song/laravel-admin)的数据库配置管理工具，仅通过配置文件就可生成整个表单，支持使用tab页对配置项进行分组。

![Untitled](https://ws1.sinaimg.cn/large/006tKfTcgy1g172wkrublg313z0n31kx.gif)

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

第二步：在配置文件中加入配置组及配置项，详细参考下面的"配置文件语法"

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



## 配置文件语法：

配置文件发布后路径为：config/admin-config.php

定义配置组：

```php
'admin_config_groups' => [
  
	// 配置组值 => tab选项卡显示文字
	'sample' => 'sample-name',
  
	// 省略写法，等同于 'sample2' => 'sample2'
	'sample2'
  
],
```

定义配置项：

```php
// 配置组名作为键，可以使用config('sample')访问一组值
'sample' => [
  
	// 默认情况写法，以下等同于 'value' => ['label'=>'value', 'type'=>'test']
	// 可以使用config('sample.value')访问其值
	'value',
  
	// 支持配置链式调用，以下将执行$form->text('value1')->help('help content')->default('default value')
	'value1'=>['help'=>'help content', 'default'=>'default value'],
  
	// 支持几乎所有Encore\Admin\Form\Field对象的链式调用方法，非链式调用的值将在Field实例化时作为参数传入
	// 以下将执行$form->test('value2', 'label text')->placeholder('typing...')->rules('required')
	'value2'=>['label text', 'placeholder'=>'typing...', 'rules'=>'required'],
  
	// 需要定义字段类型，type键值不可省略
	'value3'=>['type'=>'select', 'select label text', 'options'=>['option1'=>'option1', 			'option2'=>'option2']],
	'value5'=>['type'=>'checkbox', 'options'=>['foo'=>'foo', 'bar'=>'bar']],
	'value6'=>['type'=>'ip'],
	'value7'=>['type'=>'mobile'],
	'value8'=>['type'=>'color'],
	'value9'=>['type'=>'time', 'format'=>'HH:mm'],
  
	// 范围类型的字段会分别存储为两个配置项，'sample.value10.start' 及 'sample.value10.end'
	'value10'=>['type'=>'dateRange', 'dateRange label text'],
	'value11'=>['type'=>'number', 'min'=>100, 'default'=>100],
	'value12'=>['type'=>'rate'],
  
	// 支持没有参数的链式调用，以下将执行$form->image('value13')->uniqueName()
	'value13'=>['type'=>'image', 'uniqueName'],
	'value14'=>['type'=>'file', 'uniqueName'],
	'value17'=>['type'=>'editor'],
	'value18'=>['type'=>'switch'],
	'value19'=>['type'=>'tags'],
  
	// 以下一对多关系将被自动转化为逗号隔开的数据存入数据库
	'value4'=>['type'=>'listbox', 'options'=>['foo'=>'foo', 'bar'=>'bar']],
	'value15'=>['type'=>'multipleImage', 'removable', 'uniqueName'],
	'value16'=>['type'=>'multipleFile', 'removable', 'uniqueName'],
  
],
// 对应配置组值
'sample2' => [
	'value'
]
```



## 效果示例：

配置文件自动转化为表单：

![Snipaste_2019-03-18_16-14-05](https://ws2.sinaimg.cn/large/006tKfTcgy1g171q2oy8vj31b70qjwgd.jpg)

数据库：

![Snipaste_2019-03-18_16-21-44](https://ws1.sinaimg.cn/large/006tKfTcgy1g171q8ri68j30uk0fa411.jpg)





