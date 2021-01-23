<?php

namespace Encore\Admin\Media;

use Encore\Admin\Admin;

trait BootExtension
{
    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('media-manager', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->get('media', 'Encore\Admin\Media\MediaController@index')->name('media-index');
            $router->get('media/download', 'Encore\Admin\Media\MediaController@download')->name('media-download');
            $router->delete('media/delete', 'Encore\Admin\Media\MediaController@delete')->name('media-delete');
            $router->put('media/move', 'Encore\Admin\Media\MediaController@move')->name('media-move');
            $router->post('media/upload', 'Encore\Admin\Media\MediaController@upload')->name('media-upload');
            $router->post('media/folder', 'Encore\Admin\Media\MediaController@newFolder')->name('media-new-folder');
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Media manager', 'media', 'fa-file');

        parent::createPermission('Media manager', 'ext.media-manager', 'media*');
    }
}
