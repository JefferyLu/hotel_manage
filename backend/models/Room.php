<?php
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 房间信息
 * @author jeffery.lu
 *
 */
class Room extends ActiveRecord
{
    const STATUS_UNCLEAN = 1;
    const STATUS_CLEANING = 2;
    const STATUS_EMPTY = 3;
    
    /**
     * @var 客房状态
     */
    public static $status = [
        self::STATUS_UNCLEAN => '未打扫',
        self::STATUS_CLEANING => '打扫中',
        self::STATUS_EMPTY => '空房',
    ];
    
    /**
     * @var 客房所属楼层
     */
    public static $floor = [
        2 => '2层',
        3 => '3层',
        5 => '5层',
        6 => '6层',
        7 => '7层',
        8 => '8层',
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%room}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
    
}