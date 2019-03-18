<?php

use Fourn\AdminConfig\Http\Controllers\AdminConfigController;

Route::get('admin-config', AdminConfigController::class.'@index');
Route::post('admin-config', AdminConfigController::class.'@update');