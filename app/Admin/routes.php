<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::resource('admin/auth/users', \App\Admin\Controllers\CustomUserController::class)->middleware(config('admin.route.middleware'));

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('/alerts', AAlertController::class);
    $router->resource('/user-friends', AUserFriendController::class);
    $router->resource('/history-locations', AHistoryLocationController::class);
    $router->resource('/zones', AZoneController::class);

});
