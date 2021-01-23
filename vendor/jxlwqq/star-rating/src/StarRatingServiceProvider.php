<?php

namespace Jxlwqq\StarRating;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\ServiceProvider;

class StarRatingServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(StarRatingExtension $extension)
    {
        if (! StarRatingExtension::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-star-rating');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/star-rating')],
                'laravel-admin-star-rating'
            );
        }

        Admin::booting(function () {
            Form::extend('starRating', StarRating::class);
        });

    }
}
