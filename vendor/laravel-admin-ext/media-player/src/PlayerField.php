<?php

namespace Encore\Admin\MediaPlayer;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;

class PlayerField
{
    public function setupScript($options = [])
    {
        $options = array_merge([
            'pluginPath'       => '/vendor/laravel-admin-ext/media-player/build',
            'shimScriptAccess' => 'always',
            'videoWidth'       => 960,
            'videoHeight'      => 540,
        ], $options);

        $config = json_encode($options);

        $locale = config('app.locale');

        $script = <<<SCRIPT
            
            mejs.i18n.language('$locale');
            
            var config = $config;
            config.success = function (player, node) {
                $(player).closest('.mejs__container').attr('lang', mejs.i18n.language());
            };
            
$('video, audio').mediaelementplayer(config);
SCRIPT;

        Admin::script($script);
    }

    public static function video()
    {
        $macro = new static();

        return function ($options = []) use ($macro) {

            $field = $this;

            $macro->setupScript($options);

            return $this->unescape()->as(function ($value) use ($field, $options) {

                $field->wrapped = false;

                $url = MediaPlayer::getValidUrl($value, Arr::get($options, 'server'));

                return <<<HTML
<video src="$url" width="960px" height="540px"></video>
HTML;
            });
        };
    }

    public static function audio()
    {
        $macro = new static();

        return function ($options = []) use ($macro) {

            $field = $this;

            $macro->setupScript($options);

            return $this->unescape()->as(function ($value) use ($field, $options) {

                $field->wrapped = false;

                $url = MediaPlayer::getValidUrl($value, Arr::get($options, 'server'));

                return <<<HTML
<audio src="$url"></audio>
HTML;
            });
        };
    }
}