<?php

namespace Fourn\AdminConfig;

use Encore\Admin\Extension;

class AdminConfig extends Extension
{
    public $name = 'admin-config';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Adminconfig',
        'path'  => 'admin-config',
        'icon'  => 'fa-gears',
    ];
}