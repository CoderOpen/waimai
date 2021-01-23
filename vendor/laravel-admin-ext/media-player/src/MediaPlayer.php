<?php

namespace Encore\Admin\MediaPlayer;

use Encore\Admin\Extension;
use Illuminate\Support\Facades\Storage;

class MediaPlayer extends Extension
{
    public $name = 'media-player';

    public $assets = __DIR__.'/../resources/assets';

    public static function getValidUrl($path, $server = '')
    {
        $url = '';

        $storage = Storage::disk(config('admin.upload.disk'));

        if (url()->isValidUrl($path)) {
            $url = $path;
        } elseif ($server) {
            $url = $server.$path;
        } elseif ($storage->exists($path)) {
            $url = $storage->url($path);
        }

        return $url;
    }
}