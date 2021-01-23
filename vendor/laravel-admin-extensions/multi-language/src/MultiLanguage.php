<?php

namespace KevinSoft\MultiLanguage;

use Encore\Admin\Extension;

class MultiLanguage extends Extension
{
    public $name = 'multi-language';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';
}