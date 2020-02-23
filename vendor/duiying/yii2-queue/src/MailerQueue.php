<?php
/**
 * duiying/yii2-queue
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    wangyaxian <1822581649@qq.com>
 * @link      https://github.com/duiying/yii2-queue
 */

namespace duiying\queue;

use Yii;
use yii\web\Controller;

/**
 * 异步发送邮件
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    wangyaxian <1822581649@qq.com>
 * @link      https://github.com/duiying/yii2-queue
 */
class MailerQueue extends Controller
{
    /**
     * redis key
     */
    const KEY = 'mails';

    /**
     * crontab定时任务执行一次所发送的邮件数量
     */
    const CRONTAB_NUM = 60;

    /**
     * 发送邮件
     *
     * @return bool
     */
    public static function send()
    {
        $redis = Yii::$app->redis;

        if ($mails = $redis->lrange(self::KEY, 0, self::CRONTAB_NUM)) {
            foreach ($mails as $mail) {
                $mail = json_decode($mail, true);

                $mailer = Yii::$app->mailer->compose($mail['template'], $mail['templateData']);
                $mailer->setTo($mail['mailData']['toUser']);
                $mailer->setSubject($mail['mailData']['subject']);
                if ($mailer->send()) {
                    $redis->lrem(self::KEY, 1, json_encode($mail));
                }
            }

            return true;
        }

        return false;
    }
}
