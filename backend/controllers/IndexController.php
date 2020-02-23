<?php
namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use backend\models\Admin;
use backend\models\Role;

/**
 * IndexController 首页控制器
 *
 * PHP version 7
 */
class IndexController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $menu = \Yii::$app->params['menu'];
        //获取角色id
        $role_id = Admin::findIdentity(\Yii::$app->user->id)['role'];
        //获取已授权的权限列表
        $roleModel = Role::findOne(['role_id' => $role_id]);
        $authList = json_decode($roleModel->role_auth, true);
        
        //生成有权限可显示的菜单
        if ($role_id != 1) { //非管理员进行处理
            foreach ($menu as $key => $value) {
                $role_auth_list = [];
                $has_one = false; //是否有任一个子菜单  如果没有则删除当前菜单
                foreach ($value['child'] as $key2 => $value2) {
                    if (!in_array($value2['auth_controller'] . '/' . $value2['auth_action'], $authList)) {
                        unset($menu[$key]['child'][$key2]);
                    } else {
                        $has_one = true;
                    }
                }
                if (!$has_one) {
                    unset($menu[$key]);
                }
            }
        }
        
        return $this->render('index', ['menu' => $menu]);
    }

    /**
     * 欢迎页
     *
     * @return string
     */
    public function actionWelcome()
    {
        return $this->render('welcome', [
            'info' => Yii::$app->system->getInfo()
        ]);
    }

    /**
     * 图标对应字体
     *
     * @return string
     */
    public function actionIcon()
    {
        return $this->render('icon');
    }
}