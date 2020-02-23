<?php
/**
 * Yii2-Admin
 *
 * PHP version 7
 */

namespace backend\controllers;

use backend\models\Role;
use Yii;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\OperatorLog;

/**
 * 角色控制器
 *
 * PHP version 7
 *
 */
class RoleController extends BaseController
{
    /**
     * 列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = Role::find();
        $totalCount = $model->count();
        $pageSize = Yii::$app->params['pageSize']['admin'];
        $pager = new Pagination(['totalCount' => $totalCount, 'pageSize' => $pageSize]);
        
        $list = $model->offset($pager->offset)->limit($pager->limit)->all();
        
        return $this->render('index', ['list' => $list, 'pager' => $pager]);
    }

    /**
     * 添加
     *
     * @return string
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            
            $info = new Role();
            $info->role_name = $post['role_name'];
            $info->role_auth = json_encode($post['auth']);
            $info->role_desc = $post['role_desc'];
            $info->created_at = $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('添加角色信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '添加成功');
            }
            return MsgUtil::response(-1, '添加失败，请重试');
        }
        $authList = \Yii::$app->params['auth'];
        return $this->render('create', ['authList' => $authList]);
    }

    /**
     * 编辑
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Role();
            $post = Yii::$app->request->post();
            
            // 超级管理员不允许编辑
            if ($post['role_id'] == 1) {
                return MsgUtil::response(-1, '超级管理员不允许编辑！');
            }
            
            $info = $model::findOne(['role_id' => $post['role_id']]);
            if (!$info) {
                return MsgUtil::response(-1, '数据不存在！');
            }
            $info->role_auth = json_encode($post['auth']);
            $info->role_desc = $post['role_desc'];
            $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('编辑角色', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '修改成功');
            }
            return MsgUtil::response(-1, '修改失败');
        }
        // 角色ID
        $role_id = Yii::$app->request->get('role_id');
        // 检查参数
        if (!$role_id) {
            return $this->redirect(['base/error']);
        }
        $model = Role::findOne(['role_id' => $role_id]);
        if (!$model) {
            return $this->redirect(['base/error']);
        }
        //获取所有的权限
        $authList = \Yii::$app->params['auth'];
        //获取角色用用的权限
        $auth = json_decode($model->role_auth);
        return $this->render('update', ['authList' => $authList, 'auth' => $auth, 'model' => $model]);
    }

    /**
     * 删除
     *
     * @return string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDel()
    {
        if (Yii::$app->request->isAjax) {
            $role_id = \Yii::$app->request->post('role_id', 0);
            
            // 超级管理员不允许删除
            if ($role_id == 1) {
                return MsgUtil::response(-1, '超级管理员不允许删除！');
            }
            
            $model = Role::findOne(['role_id' => $role_id]);
            if (empty($model)) {
                return MsgUtil::response(200, '已删除');
            }
            
            if ($model->delete()) {
                //记录日志
                OperatorLog::log('删除角色', $this->id . '/' . $this->action->id, json_encode($model), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '删除成功');
            } else {
                return MsgUtil::response(-1, '删除失败，请重试');
            }
        }
    }
}