<?php
namespace backend\controllers;

use Yii;
use backend\models\Admin;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\OperatorLog;
use backend\models\Role;

/**
 * 管理员控制器
 */
class AdminController extends BaseController
{
    /**
     * 修改密码
     *
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionChangePass()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $password = $post['password']; //原密码
            $newpassword = $post['newpassword']; //新密码
            $repassword = $post['repassword']; //重复密码
            //校验数据
            if (empty($password)) {
                return MsgUtil::response(-1, '原密码不能为空');
            }
            if (empty($newpassword)) {
                return MsgUtil::response(-1, '新密码不能为空');
            }
            if ($newpassword != $repassword) {
                return MsgUtil::response(-1, '重复密码与新密码不一致');
            }
            
            $model = Admin::findOne(['id' => Yii::$app->user->identity->getId()]);
            if (!$model) {
                return MsgUtil::response(-1, '数据不存在');
            }
            //校验原密码是否正确
            if (!\Yii::$app->getSecurity()->validatePassword($password, $model->password)) {
                return MsgUtil::response(-1, '原密码输入错误');
            }
            $model->password = \Yii::$app->getSecurity()->generatePasswordHash($newpassword);
            $model->updated_at = time();
            if ($model->save()) {
                //记录日志
                OperatorLog::log('修改密码', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '修改成功');
            }
            return MsgUtil::response(-1, '修改失败');
        }
        return $this->render('change-pass');
    }
    
    /**
     * 修改信息
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionChangeInfo()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Admin();
            $post = Yii::$app->request->post();
    
            //数据校验
            if (empty($post['email'])) {
                return MsgUtil::response(-1, 'email不能为空');
            }
    
            $info = $model::findOne(['id' => $post['id']]);
            if (!$info) {
                return MsgUtil::response(-1, '数据不存在！');
            }
            $info->email = $post['email'];
            $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('修改管理员信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '修改成功');
            }
            return MsgUtil::response(-1, '修改失败');
        }
    
        // 管理员ID
        $id = \Yii::$app->user->identity->getId();
        // 检查参数
        if (!$id) {
            return $this->redirect(['base/error']);
        }
    
        $model = Admin::find()->where(['id' => $id])->select(['id', 'name', 'email', 'role'])->one();
    
        return $this->render('change-info', ['model' => $model]);
    }

    /**
     * 列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $condition = "1=1";
        
        $model = Admin::find();
        $query = $model->select(['a.*', 'r.role_name'])
                    ->from("{{%admin}} as a")
                    ->leftJoin("{{%role}} as r", 'r.role_id = a.role')
                    ->where($condition);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count('*')]);
        $pages->pageSize = 10;
        $list = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        
        return $this->render('index', ['list' => $list, 'pager' => $pages]);
    }

    /**
     * 添加
     *
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            
            //数据校验
            if (empty($post['name'])) {
                return MsgUtil::response(-1, '用户名不能为空');
            }
            if (empty($post['email'])) {
                return MsgUtil::response(-1, 'email不能为空');
            }
            if (empty($post['password'])) {
                return MsgUtil::response(-1, '密码不能为空');
            }
            if ($post['password'] != $post['repassword']) {
                return MsgUtil::response(-1, '重复密码要与密码一致！');
            }
            
            //检查用户名是否存在
            $model = Admin::findOne(['name' => $post['name']]);
            if ($model) {
                return MsgUtil::result(-1, '该用户名已存在，请重新输入');
            }
            
            $info = new Admin();
            $info->name = $post['name'];
            $info->password = \Yii::$app->getSecurity()->generatePasswordHash($post['password']);
            $info->email = $post['email'];
            $info->role = $post['role'];
            $info->created_at = $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('添加管理员', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '添加成功');
            }
            return MsgUtil::response(-1, '添加失败，请重试');
        }

        //获取角色列表
        $role = Role::find()->all();
        return $this->render('create', ['role' => $role]);
    }

    /**
     * 编辑
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Admin();
            $post = Yii::$app->request->post();
            
            //数据校验
            if (empty($post['name'])) {
                return MsgUtil::response(-1, '用户名不能为空');
            }
            if (empty($post['email'])) {
                return MsgUtil::response(-1, 'email不能为空');
            }
            
            //检查用户名是否存在
            $nameExist = $model::find()->where(['name' => $post['name']])
                            ->andWhere(['<>', 'id', $post['id']])->one();
            if ($nameExist) {
                return MsgUtil::response(-1, '用户名已存在，请重新输入');
            }
            
            $info = $model::findOne(['id' => $post['id']]);
            if (!$info) {
                return MsgUtil::response(-1, '数据不存在！');
            }
            $info->name = $post['name'];
            $info->email = $post['email'];
            $info->role = $post['role'];
            $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('编辑管理员', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '修改成功');
            }
            return MsgUtil::response(-1, '修改失败');
        }

        // 管理员ID
        $id = Yii::$app->request->get('id');
        // 检查参数
        if (!$id) {
            return $this->redirect(['base/error']);
        }

        $model = Admin::find()->where(['id' => $id])->select(['id', 'name', 'email', 'role'])->one();

        //获取角色列表
        $role = Role::find()->all();
        return $this->render('update', ['model' => $model, 'role' => $role]);
    }

    /**
     * 删除
     *
     * @return string
     * @throws \Throwable
     */
    public function actionDel()
    {
        if (Yii::$app->request->isAjax) {
            $id = \Yii::$app->request->post('id', 0);
            
            $model = Admin::findIdentity($id);
            if (empty($model)) {
                return MsgUtil::response(200, '该用户已删除');
            }
            
            //校验数据
            if ($id == \Yii::$app->user->id) {
                return MsgUtil::response(-1, '不允许删除当前用户');
            }
            if ($model->name == 'admin') {
                return MsgUtil::response(-1, '不允许删除管理员用户');
            }
            
            if ($model->delete()) {
                //记录日志
                OperatorLog::log('删除管理员', $this->id . '/' . $this->action->id, json_encode($model), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '该用户已删除');
            } else {
                return MsgUtil::response(-1, '删除失败，请重试');
            }
        }
    }
}