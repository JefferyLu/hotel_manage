<?php
/**
 * Yii2-Admin
 *
 * PHP version 7
 *
 */

namespace backend\controllers;

use backend\models\Admin;
use backend\models\Role;
use common\models\MsgUtil;
use Yii;
use yii\web\Controller;

/**
 * 基类控制器
 */
class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->isGuest) {
            $this->redirect(['login/login']);
            return false;
        }

        // 控制器
        $controller = $action->controller->id;
        // 方法
        $method = $action->id;

        // 默认允许访问的控制器/方法
        $allow = ['index/index', 'index/welcome', 'admin/change-pass', 'admin/change-info'];

        $adminModel = Admin::findOne(['id' => Yii::$app->user->getId()]);
        $roleModel = Role::findOne(['role_id' => $adminModel->role]);

        // 超级管理员不用检查权限
        if ($roleModel->role_id != 1 && !in_array($controller . '/' . $method, $allow)) {
            
            //勾选可允许访问的权限
            $authList = json_decode($roleModel->role_auth, true);
            if (!in_array($controller . '/' . $method, $authList)) {
                // Ajax请求
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->data = MsgUtil::dataFormat([MsgUtil::FAIL_CODE, MsgUtil::HAVE_NO_AUTH]);
                    return false;
                }
                // 非Ajax请求
                else {
                    echo '<center><div style="color:#FF5722;margin-top:100px;">没有权限</div></center>';
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 错误页面
     *
     * @param string $message
     * @return string
     */
    public function actionError($message = '')
    {
        return $this->render('error', ['message' => $message]);
    }
    
    /**
     * 获得请求对象
     */
    public function getRequest()
    {
        return \Yii::$app->getRequest();
    }
    
    /**
     * 获得返回对象
     */
    public function getResponse()
    {
        return \Yii::$app->getResponse();
    }
}