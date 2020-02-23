<?php
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 商品信息表
 * @author jeffery.lu
 *
 */
class Goods extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
    
    /**
     * @var 售价单位
     */
    public static $goods_unit = [
        1 => '个',
        2 => '瓶',
        3 => '包',
        4 => '只',
        5 => '袋',
        6 => '杯',
        7 => '扎',
        8 => '箱',
        9 => '打',
    ];
}