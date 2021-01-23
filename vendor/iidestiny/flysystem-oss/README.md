<h1 align="center">flysystem-oss </h1>

<p align="center">:floppy_disk:  Flysystem adapter for the oss storage.</p>

<p align="center">
<a href="https://github.com/iiDestiny/flysystem-oss"><img src="https://travis-ci.org/iiDestiny/flysystem-oss.svg?branch=master"></a>
<a href="https://github.com/iiDestiny/flysystem-oss"><img src="https://github.styleci.io/repos/163501119/shield"></a>
<a href="https://github.com/iiDestiny/flysystem-oss"><img src="https://poser.pugx.org/iidestiny/flysystem-oss/v/stable.svg"></a>
<a href="https://github.com/iiDestiny/flysystem-oss"><img src="https://poser.pugx.org/iidestiny/flysystem-oss/v/unstable.svg"></a>
<a href="https://github.com/iiDestiny/flysystem-oss"><img src="https://poser.pugx.org/iidestiny/flysystem-oss/downloads"></a>
<a href="https://scrutinizer-ci.com/g/iiDestiny/flysystem-oss/?branch=master"><img src="https://scrutinizer-ci.com/g/iiDestiny/flysystem-oss/badges/quality-score.png?b=master"></a>
<a href="https://github.com/iiDestiny/dependency-injection"><img src="https://badges.frapsoft.com/os/v1/open-source.svg?v=103"></a>
<a href="https://github.com/iiDestiny/flysystem-oss"><img src="https://poser.pugx.org/iidestiny/flysystem-oss/license"></a>
<a href="https://996.icu"><img src="https://img.shields.io/badge/license-Anti%20996-blue.svg" alt="996.icu" /></a>
</p>

## 扩展包要求

-   PHP >= 7.0

## 安装命令

```shell
$ composer require "iidestiny/flysystem-oss" -vvv
```

## 使用

```php
use League\Flysystem\Filesystem;
use Iidestiny\Flysystem\Oss\OssAdapter;
use Iidestiny\Flysystem\Oss\Plugins\FileUrl;

$prefix = ''; // 前缀，非必填
$accessKeyId = 'xxxxxx';
$accessKeySecret = 'xxxxxx';
$endpoint= 'oss.iidestiny.com'; // ssl：https://iidestiny.com
$bucket = 'bucket';
$isCName = true; // 如果 isCname 为 false，endpoint 应配置 oss 提供的域名如：`oss-cn-beijing.aliyuncs.com`，cname 或 cdn 请自行到阿里 oss 后台配置并绑定 bucket

$adapter = new OssAdapter($accessKeyId, $accessKeySecret, $endpoint, $bucket, $isCName, $prefix);

$flysystem = new Filesystem($adapter);

```

## 常用方法

```php
bool $flysystem->write('file.md', 'contents');

bool $flysystem->write('file.md', 'http://httpbin.org/robots.txt', ['options' => ['xxxxx' => 'application/redirect302']]);

bool $flysystem->writeStream('file.md', fopen('path/to/your/local/file.jpg', 'r'));

bool $flysystem->update('file.md', 'new contents');

bool $flysystem->updateStream('file.md', fopen('path/to/your/local/file.jpg', 'r'));

bool $flysystem->rename('foo.md', 'bar.md');

bool $flysystem->copy('foo.md', 'foo2.md');

bool $flysystem->delete('file.md');

bool $flysystem->has('file.md');

string|false $flysystem->read('file.md');

array $flysystem->listContents();

array $flysystem->getMetadata('file.md');

int $flysystem->getSize('file.md');

string $flysystem->getAdapter()->getUrl('file.md');

string $flysystem->getMimetype('file.md');

int $flysystem->getTimestamp('file.md');
```

## 插件扩展

```php
use Iidestiny\Flysystem\Oss\Plugins\FileUrl;
use Iidestiny\Flysystem\Oss\Plugins\SignUrl;
use Iidestiny\Flysystem\Oss\Plugins\TemporaryUrl;
use Iidestiny\Flysystem\Oss\Plugins\SetBucket;

// 获取 oss 资源访问链接
$flysystem->addPlugin(new FileUrl());

string $flysystem->getUrl('file.md');

// url 访问有效期 & 图片处理「$timeout 为多少秒过期」
$flysystem->addPlugin(new SignUrl());

 string $flysystem->signUrl('file.md', $timeout, ['x-oss-process' => 'image/circle,r_100']);

 // url 访问有效期「$expiration 为未来时间 2019-05-05 17:50:32」
$flysystem->addPlugin(new TemporaryUrl());

string $flysystem->getTemporaryUrl('file.md', $expiration);

// 多个bucket切换
$flysystem->addPlugin(new SetBucket());
$flysystem->bucket('test')->has('file.md');
```

## 获取官方完整 OSS 处理能力

阿里官方 SDK 可能处理了更多的事情，如果你想获取完整的功能可通过此插件获取，
然后你将拥有完整的 oss 处理能力

```php
use Iidestiny\Flysystem\Oss\Plugins\Kernel;

$flysystem->addPlugin(new Kernel());
$kernel = $flysystem->kernel();

// 例如：防盗链功能
$refererConfig = new RefererConfig();
// 设置允许空Referer。
$refererConfig->setAllowEmptyReferer(true);
// 添加Referer白名单。Referer参数支持通配符星号（*）和问号（？）。
$refererConfig->addReferer("www.aliiyun.com");
$refererConfig->addReferer("www.aliiyuncs.com");

$kernel->putBucketReferer($bucket, $refererConfig);
```

> 更多功能请查看官方 SDK 手册：https://help.aliyun.com/document_detail/32100.html?spm=a2c4g.11186623.6.1055.66b64a49hkcTHv

## 前端 web 直传配置

oss 直传有三种方式，当前扩展包使用的是最完整的 [服务端签名直传并设置上传回调](https://help.aliyun.com/document_detail/31927.html?spm=a2c4g.11186623.2.10.5602668eApjlz3#concept-qp2-g4y-5db) 方式，**扩展包只生成前端页面上传所需的签名参数**，前端上传实现可参考 [官方文档中的实例](https://help.aliyun.com/document_detail/31927.html?spm=a2c4g.11186623.2.10.5602668eApjlz3#concept-qp2-g4y-5db) 或自行搜索

```php
use Iidestiny\Flysystem\Oss\Plugins\SignatureConfig;

$flysystem->addPlugin(new SignatureConfig());

/**
 * 1. 前缀如：'images/'
 * 2. 回调服务器 url
 * 3. 回调自定义参数
 * 4. 回调链接过期时间，防止第三方拿到回调请求恶意发送
 */
object $flysystem->signatureConfig($prefix = '/', $callBackUrl = '', $customData = [], $expire = 30);
```

## 直传回调验签

当设置了直传回调后，可以通过验签插件，验证并获取 oss 传回的数据 [文档](https://help.aliyun.com/document_detail/91771.html?spm=a2c4g.11186623.2.15.7ee07eaeexR7Y1#title-9t0-sge-pfr)

注意事项：
- 如果没有 Authorization 头信息导致验签失败需要先在 apache 或者 nginx 中设置 rewrite
- 以 apache 为例，修改 httpd.conf 在 DirectoryIndex index.php 这行下面增加「RewriteEngine On」「RewriteRule .* - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization},last]」

```php
use Iidestiny\Flysystem\Oss\Plugins\Verify;

$flysystem->addPlugin(new Verify());

list($verify, $data) = $flysystem->verify();
// [$verify, $data] = $flysystem->verify(); // php 7.1 +

if (!$verify) {
    // 验证失败处理，此时 $data 为验签失败提示信息
}

// 注意一定要返回 json 格式的字符串，因为 oss 服务器只接收 json 格式，否则给前端报 CallbackFailed
header("Content-Type: application/json");
echo  json_encode($data);
```

直传回调验收后返回给前端的数据，例如

```json
{
    "filename": "user/15854050909488182.png",
    "size": "56039",
    "mimeType": "image/png",
    "height": "473",
    "width": "470",
    "name": "zhangsan", // 下面这两条是请求直传配置时自定义参数
    "age": "24"
}
```

> 这其实要看你回调通知方法具体怎么返回，如果直接按照文档给的方法返回是这个样子

## 前端直传组件分享「vue + element」

```html
<template>
  <div>
    <el-upload
      class="avatar-uploader"
      :action="uploadUrl"
      :on-success="handleSucess"
      :on-change="handleChange"
      :before-upload="handleBeforeUpload"
      :show-file-list="false"
      :data="data"
      :on-error="handleError"
      :file-list="files"
    >
      <img v-if="dialogImageUrl" :src="dialogImageUrl" class="avatar">
      <i v-else class="el-icon-plus avatar-uploader-icon" />
    </el-upload>
  </div>
</template>

<script>
import { getOssPolicy } from '@/api/oss' // 这里就是获取直传配置接口

export default {
  name: 'Upload',
  props: {
    url: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      uploadUrl: '', // 上传提交地址
      data: {}, // 上传提交额外数据
      dialogImageUrl: '', // 预览图片
      files: [] // 上传的文件
    }
  },
  computed: {},
  created() {
    this.dialogImageUrl = this.url
  },
  methods: {
    handleChange(file, fileList) {
      console.log(file, fileList)
    },
    // 上传之前处理动作
    async handleBeforeUpload(file) {
      const fileName = this.makeRandomName(file.name)
      try {
        const response = await getOssPolicy()

        this.uploadUrl = response.host

        // 组装自定义参数
        if (Object.keys(response['callback-var']).length) {
          for (const [key, value] of Object.entries(response['callback-var'])) {
            this.data[key] = value
          }
        }

        this.data.policy = response.policy
        this.data.OSSAccessKeyId = response.accessid
        this.data.signature = response.signature
        this.data.host = response.host
        this.data.callback = response.callback
        this.data.key = response.dir + fileName
      } catch (error) {
        this.$message.error('获取上传配置失败')
        console.log(error)
      }
    },
    // 文件上传成功处理
    handleSucess(response, file, fileList) {
      const fileUrl = this.uploadUrl + this.data.key
      this.dialogImageUrl = fileUrl
      this.$emit('update:url', fileUrl)
      this.files.push({
        name: this.data.key,
        url: fileUrl
      })
    },
    // 上传失败处理
    handleError() {
      this.$message.error('上传失败')
    },
    // 随机名称
    makeRandomName(name) {
      const randomStr = Math.random().toString().substr(2, 4)
      const suffix = name.substr(name.lastIndexOf('.'))
      return Date.now() + randomStr + suffix
    }
  }

}
</script>

<style>
.avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 150px;
    height: 150px;
    line-height: 150px;
    text-align: center;
  }
  .avatar {
    width: 150px;
    height: 150px;
    display: block;
  }
</style>

```

## Laravel 适配包

-   Laravel 5：[iidestiny/laravel-filesystem-oss](https://github.com/iiDestiny/laravel-filesystem-oss)

## 参考

-   [overtrue/flysystem-qiniu](https://github.com/overtrue/flysystem-qiniu)

## License

[![LICENSE](https://img.shields.io/badge/license-Anti%20996-blue.svg)](https://github.com/996icu/996.ICU/blob/master/LICENSE)
