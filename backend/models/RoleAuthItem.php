<?php
/**
 * Yii2-Admin
 *
 * PHP version 7
 */

namespace backend\models;

use Yii;

/**
 * This is the model class for table "role_auth_item".
 *
 * @property string $item_id ID
 * @property string $role_id 角色ID
 * @property string $auth_id 权限ID
 */
class RoleAuthItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'auth_id'], 'required'],
            [['role_id', 'auth_id'], 'integer'],
        ];
    }
}
