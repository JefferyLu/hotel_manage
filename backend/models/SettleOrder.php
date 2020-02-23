<?php
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 结算订单表
 * @author jeffery.lu
 *
 */
class SettleOrder extends ActiveRecord
{
    //支付方式
    public static $payment = [
        1 => '现金',
        2 => '支付宝',
        3 => '微信',
        4 => '信用卡',
        5 => '储蓄卡',
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%settle_order}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
}