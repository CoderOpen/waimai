Media manager for laravel-admin
===============================

[![StyleCI](https://styleci.io/repos/98843192/shield?branch=master)](https://styleci.io/repos/98843192)
[![Packagist](https://img.shields.io/packagist/l/laravel-admin-ext/media-manager.svg?maxAge=2592000)](https://packagist.org/packages/laravel-admin-ext/media-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-admin-ext/media-manager.svg?style=flat-square)](https://packagist.org/packages/laravel-admin-ext/media-manager)
[![Pull request welcome](https://img.shields.io/badge/pr-welcome-green.svg?style=flat-square)]()


Media manager for `local` disk.

[Documentation](http://laravel-admin.org/docs/#/en/extension-media-manager) | [中文文档](http://laravel-admin.org/docs/#/zh/extension-media-manager)

## Screenshot

![wx20170809-170104](https://user-images.githubusercontent.com/1479100/29113762-99886c32-7d24-11e7-922d-5981a5849c7a.png)

## Installation

```
$ composer require laravel-admin-ext/media-manager -vvv

$ php artisan admin:import media-manager
```

Add a disk config in `config/admin.php`:

```php

    'extensions' => [

        'media-manager' => [
        
            // Select a local disk that you configured in `config/filesystem.php`
            'disk' => 'public'
        ],
    ],

```


Open `http://localhost/admin/media`.

License
------------
Licensed under [The MIT License (MIT)](LICENSE).
