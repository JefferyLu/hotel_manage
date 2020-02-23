# yii2-queue
基于Redis异步发送邮件

### 安装
```bash
php composer.phar require --prefer-dist duiying/yii2-queue "^1.0.0"
```

### 基本使用
在控制器或模型中将邮件信息存入redis
```php
$mail = [
    // 邮件模板文件
    'template' => 'forget-pass',
    // 邮件模板文件中需要渲染的数据
    'templateData' => [
        'admin_name' => $this->admin_name,
        'url' => 'http://admin.yii2.com/index.php?r=login/reset-pass&time='. $time .'&admin_name='. $this->admin_name .'&token='. $token
    ],
    // 邮件主题和收件人
    'mailData' => [
        'subject' => $subject,
        'toUser' => $toUser
    ]
];

// 向List尾部添加元素
Yii::$app->redis->rpush(MailerQueue::KEY, json_encode($mail));
```
邮件模板文件(forget-pass.php)
```php
<p>尊敬的<?php echo $admin_name; ?>，您好：</p>

<p>您的找回密码链接如下：</p>

<p><a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>

<p>该链接10分钟内有效，请勿传递给别人！</p>

<p>该邮件为系统自动发送，请勿回复！</p>
```
console\controllers\MailController.php
```php
<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use duiying\queue\MailerQueue;

class MailController extends Controller
{
    public function actionSend()
    {
        $res = MailerQueue::send();
        var_dump($res);
    }
}
```
设置crontab定时任务
```bash
# 每隔1分钟执行一次
* * * * * /usr/bin/php /data/www/Yii2-Admin/yii mail/send > /dev/null
```