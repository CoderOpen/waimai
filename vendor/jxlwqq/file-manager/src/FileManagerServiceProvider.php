<?php

namespace Jxlwqq\FileManager;

use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(FileManager $extension)
    {
        if (!FileManager::boot()) {
            return;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-file-manager');
        }

        $this->app->booted(function () {
            FileManager::routes(__DIR__ . '/../routes/web.php');
        });
    }
}
