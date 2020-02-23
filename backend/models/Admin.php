<?php
/**
 * Admin
 */

namespace backend\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * 后台管理员信息表
 * @author jeffery.lu
 *
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }

    /**
     * 根据给到的ID查询身份
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 获取该认证实例表示的用户的ID
     *
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 基于cookie登录密钥的验证的逻辑的实现
     *
     * @param string $authKey 当前用户的(cookie)认证密钥
     * @return boolean
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }
    
    public function getAuthKey(){
        return true;
    }
    
    public static function findIdentityByAccessToken($token, $type = null){
        return '';
    }
}
