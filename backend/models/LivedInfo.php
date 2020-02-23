<?php
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 入住信息表
 * @author jeffery.lu
 *
 */
class LivedInfo extends ActiveRecord
{
    //入住状态
    const STATUS_LIVED = 1;
    const STATUS_SETTLE = 2;
    
    public static $status = [
        self::STATUS_LIVED => '已入住',
        self::STATUS_SETTLE => '已结单',
    ];
    
    //是否提供早餐
    public static $is_breakfast = [
        0 => '不提供早餐',
        1 => '提供早餐',
    ];
    
    //是否提供早叫提醒服务
    public static $is_alarm = [
        0 => '不需要早叫提醒',
        1 => '需要早叫提醒',
    ];
    
    //是否换房
    public static $is_change = [
        0 => '否',
        1 => '是'
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lived_info}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
}