<?php
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 操作信息表
 * @author jeffery.lu
 *
 */
class OperatorLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%operator_log}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }
    
    /**
     * 记录日志
     * @param string $operator      操作动作 
     * @param string $action        控制器方法
     * @param string $content       请求参数
     * @param string $user_name     操作人
     * @param string $remark        备注
     */
    public static function log($operator, $action, $content, $user_name, $remark=''){
        $model = new self();
        $model->operator = $operator;
        $model->action = $action;
        $model->content = $content;
        $model->user_name = $user_name;
        $model->remark = $remark;
        $model->created_at = time();
        $model->save();
    }
}
