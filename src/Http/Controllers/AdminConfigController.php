<?php

namespace Fourn\AdminConfig\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class AdminConfigController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('Title')
            ->description('Description')
            ->body(view('admin-config::index'));
    }
}