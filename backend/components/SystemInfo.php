<?php
/**
 * Yii2-Admin
 *
 * PHP version 7
 *
 */

namespace backend\components;

use Yii;
use yii\base\Component;

/**
 * 系统基本信息组件
 *
 * PHP version 7
 *
 */
class SystemInfo extends Component
{
    /**
     * 获取系统基本信息
     *
     * @return array
     */
    public function getInfo()
    {
        return [
            // Yii版本
            'yii' => Yii::getVersion(),
            // 服务器操作系统
            'system' => php_uname('s'),
            // WEB运行环境
            'web' => php_sapi_name(),
            // MySQL数据库版本
            'mysql' => Yii::$app->db->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION),
            // 运行PHP版本
            'php' => phpversion(),
            // 上传大小限制
            'upload' => ini_get('upload_max_filesize'),
            // POST大小限制
            'post' => ini_get('post_max_size'),
            // 当前时间
            'time' => date('Y/m/d H:i:s'),
            // 服务器IP
            'ip' => $_SERVER['SERVER_ADDR'],
            // PHP执行时间限制
            'execute' => ini_get('max_execution_time'),
        ];
    }
}