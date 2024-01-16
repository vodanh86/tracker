<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use Carbon\Carbon;
use Encore\Admin\Grid\Column;
use Illuminate\Support\Facades\Config;
use \Encore\Admin\Show;

Encore\Admin\Form::forget(['map', 'editor']);

Column::extend('number', function ($value) {
    return number_format($value);
});

Column::extend('percentage', function ($value) {
    return $value . ' %';
});

Column::extend('vndate', function ($value) {
    $carbonDate = Carbon::parse($value)->timezone(Config::get('app.timezone'));
    return $carbonDate->format('d/m/Y - H:i:s');
});

Show::extend('vndate', function ($value) {
    $carbonDate = Carbon::parse($value)->timezone(Config::get('app.timezone'));
    return $carbonDate->format('d/m/Y - H:i:s');
});