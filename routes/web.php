<?php

use Fourn\AdminConfig\Http\Controllers\AdminConfigController;

Route::get('admin-config', AdminConfigController::class.'@index');