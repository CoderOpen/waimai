laravel-admin Multi Language
======

## Install

```
composer require kevinsoft/multi-language
```

## Config


First, add extension config

In `config/admin.php`

```
    'extensions' => [
        'multi-language' => [
            'enable' => true,
            // the key should be same as var locale in config/app.php
            // the value is used to show
            'languages' => [
                'en' => 'English',
                'zh-CN' => '简体中文',
            ],
            // default locale
            'default' => 'zh-CN',
        ],
    ],
```

Then, add except route to auth

In `config/admin.php`, add `locale` to `auth.excepts`

```
    'auth' => [
        ...
        // The URIs that should be excluded from authorization.
        'excepts' => [
            'auth/login',
            'auth/logout',
            // add this line !
            'locale',
        ],
    ],

```

## ScreenShots

![login](https://user-images.githubusercontent.com/20313390/60640921-ff109480-9e5b-11e9-8ec8-aee897a8bdcb.jpg)
![login1](https://user-images.githubusercontent.com/20313390/60640924-0041c180-9e5c-11e9-8a2d-539d6214d069.jpg)
![language](https://user-images.githubusercontent.com/20313390/60640919-fc15a400-9e5b-11e9-962d-175fb2f24da1.jpg)
