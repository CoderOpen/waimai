#### 概述

之前开源的是前端版本，主要是因为后端搞起来比较麻烦，一但开源可能会很多人联系咨询。
即便没有开源的时候，都有不少加好友问问题，加上自己有在上班，忙的时候会忘记回复信息，很抱歉。
所以采取付费优先的原则。当然如果有时间的话，我正好看到了也是会回的。

#### 安装部署

假设环境部署域名为 chihe.cn
* 必须PHP7.2以上版本
* 导入数据库 ，选择已有的数据库导入项目目录下的chihe.sql。 修改.env 中的数据库配置信息

  ```
  DB_DATABASE=                 //数据库
  DB_USERNAME=			     //数据库用户
  DB_PASSWORD=				 //数据库密码
  ```

* 配置订阅消息通知。首先在小程序后台获取应用ID和应用密匙。修改配置文件.env

  ```
  CHI_HE_MINI_PROGRAM_APP_ID=    //应用ID
  CHI_HE_MINI_PROGRAM_SECRET=    //应用密匙
  ```

  然后在小程序后台选择配置订阅消息，然后对应消息模板修改 Console/Commands/ChiHe.php

  第54行，修改订阅消息的文案

  ```
  'thing2' => [
  'value' => '又到了吃饭时刻，快来领吃喝炒鸡优惠券啦！',
  ],
  'thing4' => [
  'value' => '红包天天领，天天能提醒，叫外卖省省省',
  ],
  ```

* 根据服务器环境安装代码，参考laravel安装文档仅仅调整好nginx或者appache配置。能访问到laravel特有的404页面即配置成功
然后到项目目录chiche-api下依次执行

  ```
  php artisan key:generate
  php artisan jwt:secret  
  php artisan storage:link
  php artisan config:clear
  php artisan cache:clear
  ```
 
 可能遇到的问题： 安装phpinfo扩展， 把禁用的安全函数putenv    syslink函数放开。
 安装完毕后，访问域名 chihe.cn/api/chiheyouhui/project 能正常访问说明安装成功！

* 启动订阅消息定时发送脚本。 每分钟执行一次，执行的命令是：注意要在项目目录下执行

  ```
  php artisan chihe:send-msg
  ```

  

* 访问管理后台，chihe.cn/admin

  账号密码是admin/admin

  管理友联和赚钱项目中要跳转的第三方小程序ID和小程序活动页面的路径

  可参考   https://mp.weixin.qq.com/s/JHDWqBvntD0-p-dXEntSQQ
  
#### 改进计划

欢迎各位大佬加入项目，让这个项目更加完善

#### 打赏与联系作者

微信phpcoder666

<image src='./temp/reward.jpeg' style="margin:0 20px;width:300px;height:auto" > <image src='./temp/lajun-wechat.jpeg' style="margin:0 20px;width:300px;height:auto" >
