<?php

namespace Encore\Admin\MediaPlayer;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Column;
use Encore\Admin\Show\Field;
use Illuminate\Support\ServiceProvider;

class MediaPlayerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(MediaPlayer $extension)
    {
        if (! MediaPlayer::boot()) {
            return ;
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/media-player')],
                'laravel-admin-media-player'
            );
        }

        Admin::booting(function () {

            Admin::js('vendor/laravel-admin-ext/media-player/build/mediaelement-and-player.min.js');
            Admin::css('vendor/laravel-admin-ext/media-player/build/mediaelementplayer.min.css');

            Field::macro('video', PlayerField::video());
            Field::macro('audio', PlayerField::audio());

            Column::extend('video', PlayerColumn::video());
            Column::extend('audio', PlayerColumn::audio());
        });
    }
}