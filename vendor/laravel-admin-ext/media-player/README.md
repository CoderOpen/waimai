laravel-admin Media player
======

This extension allows you to play video or audio on grid pages and show pages with the help of [mediaelement](https://github.com/mediaelement/mediaelement).

[中文介绍](https://laravel-admin.org/posts/21)

## Screenshots

![wx20181114-010912](https://user-images.githubusercontent.com/1479100/48430450-4ef5fa80-e7aa-11e8-8fcd-1f5717b0d3d6.png)

![wx20181114-011037](https://user-images.githubusercontent.com/1479100/48430451-4ef5fa80-e7aa-11e8-8394-38ed2c6c75ba.png)

## Installation

```bash
composer require laravel-admin-ext/media-player

php artisan vendor:publish --tag=laravel-admin-media-player
```

## Usage

If you have a field `foo` that stores the full url of the audio/video file, or the path under the disk configured by `admin.upload.disk`, you can use it in the following way.

In the grid page:
```php
// Add a play button to the current field column. After clicking it will open a modal to play the video file.
$grid->foo()->video();

// Add an audio player to the current field column
$grid->foo()->audio();
```
In show page:
```php
// This field will be displayed as a video player
$show->foo()->video();

// this field will be displayed as an audio player
$show->foo()->audio();
```

If the field `foo` is another path or a file path under another server, you can use the following settings.

```php
$grid->foo()->video(['server' => 'http:www.foo.com/']);
```

This player feature of this extension is based on [mediaelement](https://github.com/mediaelement/mediaelement) and can be referenced [API and Configuration](https://github.com/mediaelement/mediaelement/blob/master/docs/api.md) Add more settings to the player.

For example, set the size of the player:

```php
$grid->foo()->video(['videoWidth' => 720, 'videoHeight' => 480]);
```

## Donate

If you feel that this project has saved you time, you may wish to support it ;)

![-1](https://cloud.githubusercontent.com/assets/1479100/23287423/45c68202-fa78-11e6-8125-3e365101a313.jpg)

License
------------
Licensed under [The MIT License (MIT)](LICENSE).
