<?php

namespace Encore\ChinaDistpicker;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid\Filter;
use Illuminate\Support\ServiceProvider;

class ChinaDistpickerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(ChinaDistpicker $extension)
    {
        if (! ChinaDistpicker::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-china-distpicker');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/china-distpicker')],
                'laravel-admin-china-distpicker'
            );
        }

        Admin::booting(function () {
            Form::extend('distpicker', Distpicker::class);
            Filter::extend('distpicker', DistpickerFilter::class);
        });
    }
}