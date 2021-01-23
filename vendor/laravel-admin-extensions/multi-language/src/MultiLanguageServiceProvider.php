<?php

namespace KevinSoft\MultiLanguage;

use Encore\Admin\Facades\Admin;
use Illuminate\Support\ServiceProvider;
use KevinSoft\MultiLanguage\Widgets\LanguageMenu;

class MultiLanguageServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(MultiLanguage $extension)
    {
        if (! MultiLanguage::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'multi-language');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/kevinsoft/multi-language')],
                'multi-language'
            );
        }

        $this->app->booted(function () {
            MultiLanguage::routes(__DIR__.'/../routes/web.php');
        });

        # $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware(Middlewares\MultiLanguageMiddleware::class);
        $this->app['router']->pushMiddlewareToGroup('web', Middlewares\MultiLanguageMiddleware::class);
        Admin::navbar()->add(new LanguageMenu());
    }
}