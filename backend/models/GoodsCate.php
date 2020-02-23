<?php
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 商品分类信息表
 * @author jeffery.lu
 *
 */
class GoodsCate extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_cate}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
}
