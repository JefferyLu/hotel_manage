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

namespace common\components;

use Yii;
use yii\base\Component;

/**
 * Token组件
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    wangyaxian <1822581649@qq.com>
 * @link      https://github.com/duiying/Yii2-Admin
 */
class Token extends Component
{
    /**
     * 生成token
     *
     * @param $admin_name 用户名
     * @param $time 事件戳
     * @return string
     */
    public function generateToken($admin_name, $time)
    {
        //return md5(md5($admin_name) . base64_encode(Yii::$app->request->userIP) . md5($time));
        return md5(md5($admin_name) . base64_encode('127.0.0.1') . md5($time));
    }
}