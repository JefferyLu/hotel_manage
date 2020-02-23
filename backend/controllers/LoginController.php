<?php
/**
 * Yii2-Admin
 *
 */

namespace backend\controllers;

use backend\models\Admin;
use common\models\MsgUtil;
use Yii;
use yii\web\Controller;
use backend\models\OperatorLog;

/**
 * LoginController 登录控制器
 */
class LoginController extends Controller
{
    // 默认的请求方法
    public $defaultAction = 'login';

    /**
     * 登录
     *
     * @return string
     */
    public function actionLogin()
    {
        if (Yii::$app->request->isAjax) {
            $post = \Yii::$app->request->post();
            $name = \Yii::$app->request->post('name', '');
            $password = \Yii::$app->request->post('password', '');

            //参数校验
            if (empty($name)) {
                return MsgUtil::response(-1, '用户名不能为空');
            }
            if (empty($password)) {
                return MsgUtil::response(-1, '密码不能为空');
            }

            //进行登录操作
            $where = ['name' => $name];
            
            $model = Admin::findOne($where);
            if (!$model) {
                return MsgUtil::response(-1, '管理员用户不存在');
            }
            if (\Yii::$app->getSecurity()->validatePassword($password, $model->password)) {
                //登录成功，保存user Model
                \Yii::$app->user->login($model);
                $model->ip = \Yii::$app->request->userIP;
                $model->login_time = time();
                $model->save();
                
                //记录日志
                OperatorLog::log('管理员登录', $this->id . '/' . $this->action->id, json_encode($post), '');
                
                return MsgUtil::response(200, '登录成功');
            } else {
                return MsgUtil::response(-1, '登录密码错误');
            }
        }
        return $this->render('login');
    }

    /**
     * 退出
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout(false);
        return MsgUtil::response(200, '退出成功');
    }
}