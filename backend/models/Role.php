<?php
/**
 * Yii2-Admin
 *
 * PHP version 7
 */

namespace backend\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "role".
 *
 * @property string $role_id 角色ID
 * @property string $role_name 名称
 * @property string $role_desc 角色描述
 * @property string $role_sort 角色排序, 默认10, 升序排列
 */
class Role extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
}
