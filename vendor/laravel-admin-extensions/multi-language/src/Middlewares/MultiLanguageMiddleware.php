<?php

namespace KevinSoft\MultiLanguage\Middlewares;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use KevinSoft\MultiLanguage\MultiLanguage;

class MultiLanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        $languages = MultiLanguage::config('languages');
        if (Cookie::has('locale') && array_key_exists(Cookie::get('locale'), $languages)) {
            App::setLocale(Cookie::get('locale'));
        } else {
            $default = MultiLanguage::config('default');
            App::setLocale($default);
        }
        return $next($request);
    }
}