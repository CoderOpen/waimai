cropper extension for laravel-admin
======

这是一个`laravel-admin`扩展，用来将`cropper`集成进`laravel-admin`的表单中

## 截图

![](./demo.jpg)

## 安装

```bash
composer require laravel-admin-ext/cropper
```

然后
```bash
php artisan vendor:publish --tag=laravel-admin-cropper
```

## 配置

在`config/admin.php`文件的`extensions`，加上属于这个扩展的一些配置
```php

    'extensions' => [

        'cropper' => [
        
            // 如果要关掉这个扩展，设置为false
            'enable' => true,
        ]
    ]

```


## 使用

在form表单中使用它：
```php
$form->cropper('content','label');
```
默认模式是自由剪裁模式，如果需要强制剪裁尺寸，请使用（注意该尺寸就是最后得到的图片尺寸 非“比例”）
```php
$form->cropper('content','label')->cRatio($width,$height);
```

自定义文件名称（使用 basename 方法，原 name 方法在此插件无效）
```php
$form->cropper('content','label')
    ->basename(function () {
        return time() . '_' . str_random(10);
    });
```

> 使用 basename 方法请返回一个不包含拓展名的自定义文件名称

自定义存储路径
```php
$form->cropper('content','label')->move('images/users/avatars');
```

## PS （特性预读）
1、图片并不是预上传的，而是前端转base64之后填入input，服务端再转回图片保存的

2、图片格式是默认原格式保存的。就是说，如果原图是透明底色的png图片，保存之后仍旧是透明底色的png图片，并不会损失（前端logo神器）

3、该扩展是可多次调用的。在同一个表单内能调动多次，不会相互干扰。

4、扩展继承了laravel-admin 的ImageField类 和File类。 
所以你不必去纠结图片的修改 和删除问题。他们都是自动操作的。 
当然，因为继承了ImageField类，所以也能使用 “intervention/image” 的各种(crop,fit,insert)方法
（前提是你已经composer require intervention/image）

License
------------
Licensed under [The MIT License (MIT)](LICENSE).