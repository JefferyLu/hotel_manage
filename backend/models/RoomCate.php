<?php
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 房间分类
 * @author jeffery.lu
 *
 */
class RoomCate extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%room_cate}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
}
