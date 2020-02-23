<?php
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 会员信息表
 * @author jeffery.lu
 *
 */
class Member extends ActiveRecord
{
    //积分到达触发阈值
    const VIP_LIMIT_SCORE = 5000;
    
    //会员等级
    const LEVEL_COMMON = 1;
    const LEVEL_VIP = 2;
    
    //会员等级
    public static $level = [
        self::LEVEL_COMMON => '普通会员',
        self::LEVEL_VIP => 'VIP会员',
    ];
    
    //性别
    public static $gender = [
        1 => '男',
        2 => '女',
    ]; 
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
}