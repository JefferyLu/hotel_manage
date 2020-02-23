<?php
/**
 * Yii2-Admin
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    wangyaxian <1822581649@qq.com>
 * @link      https://github.com/duiying/Yii2-Admin
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;
use duiying\queue\MailerQueue;

/**
 * 发送邮件
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    wangyaxian <1822581649@qq.com>
 * @link      https://github.com/duiying/Yii2-Admin
 */
class MailController extends Controller
{
    public function actionSend()
    {
        $res = MailerQueue::send();
        var_dump($res);
    }
}