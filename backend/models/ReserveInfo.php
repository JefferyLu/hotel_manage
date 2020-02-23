<?php
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 预定信息表
 * @author jeffery.lu
 *
 */
class ReserveInfo extends ActiveRecord
{
    //证件类型
    const ID_TYPE_SHENFEN = 1;
    const ID_TYPE_PASSPORT = 2;
    const ID_TYPE_DRIVER = 3;
    const ID_TYPE_PASSHK = 4;
    
    //证件类型
    public static $id_type = [
        self::ID_TYPE_SHENFEN => '身份证',
        self::ID_TYPE_PASSPORT => '护照',
        self::ID_TYPE_DRIVER => '驾照',
        self::ID_TYPE_PASSHK => '港澳通行证',
    ];
    
    //预定状态
    const STATUS_RESERVE = 1;
    const STATUS_CANCEL = 2;
    const STATUS_LIVE = 3;
    
    public static $status = [
        self::STATUS_RESERVE => '已预定',
        self::STATUS_CANCEL => '已取消',
        self::STATUS_LIVE => '已入住',
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reserve_info}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
}