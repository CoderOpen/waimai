# Star Rating extension for laravel-admin

This is a `laravel-admin` extension that integrates [bootstrap-star-rating](https://github.com/kartik-v/bootstrap-star-rating) into `laravel-admin`.

## Screenshot

<img src="https://camo.githubusercontent.com/8ce0822c6ba8b770ddfee452392bf61e1c3bd0f2/68747470733a2f2f6c68332e676f6f676c6575736572636f6e74656e742e636f6d2f707543624e4c394c6c424d747938446d615a78417130794d38746575684d5f6845766f782d4e754a327837785765644e6873386e77536b315a6f384649534641737974383d77313336362d683736382d72772d6e6f" width=300>

## Installation

```bash
composer require jxlwqq/star-rating
php artisan vendor:publish --tag=laravel-admin-star-rating
```

## Update

```bash
composer require jxlwqq/star-rating
php artisan vendor:publish --tag=laravel-admin-star-rating --force
```

## Configuration

In the `extensions` section of the `config/admin.php` file, add some configuration that belongs to this extension.
```php

'extensions' => [
 
     'star-rating' => [
     
         // set to false if you want to disable this extension
         'enable' => true,
         
         // configuration
         'config' => [
             'min' => 1, 'max' => 5, 'step' => 1, 'size' => 'xs', 'language' => 'zh',
         ]
     ]
 ]

```

More configuration can be found in the [Bootstrap Star Rating Document](http://plugins.krajee.com/star-rating).

## Usage

Use it in the form form:
```php
$form->starRating('rate');
```

## More resources

[Awesome Laravel-admin](https://github.com/jxlwqq/awesome-laravel-admin)


## License

Licensed under [The MIT License (MIT)](LICENSE).
