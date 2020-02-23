<?php
namespace backend\controllers;

use Yii;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\RoomCate;
use backend\models\OperatorLog;

/**
 * 客房类型控制器
 */
class RoomCateController extends BaseController
{
    /**
     * 列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = RoomCate::find();
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
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            
            //数据校验
            if (empty($post['cate_name'])) {
                return MsgUtil::response(-1, '分类名称不能为空');
            }
            if ($post['cate_limit'] <= 0) {
                return MsgUtil::response(-1, '限定人数不能为0');
            }
            
            //检查分类名称是否存在
            $model = RoomCate::findOne(['cate_name' => $post['cate_name']]);
            if ($model) {
                return MsgUtil::result(-1, '该分类名称已存在，请修改后重试');
            }
            
            $info = new RoomCate();
            $info->cate_name = $post['cate_name'];
            $info->cate_limit = $post['cate_limit'];
            $info->remark = $post['remark'];
            $info->created_at = $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('添加客房分类', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '添加成功');
            }
            return MsgUtil::response(-1, '添加失败，请重试');
        }

        return $this->render('create');
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
            $model = new RoomCate();
            $post = Yii::$app->request->post();
            
            //数据校验
            if ($post['cate_limit'] <= 0) {
                return MsgUtil::response(-1, '限定人数不能为0');
            }
            
            $info = $model::findOne(['id' => $post['id']]);
            if (!$info) {
                return MsgUtil::response(-1, '数据不存在！');
            }
            $info->cate_limit = $post['cate_limit'];
            $info->remark = $post['remark'];
            $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('编辑客房分类', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '修改成功');
            }
            return MsgUtil::response(-1, '修改失败');
        }

        $id = Yii::$app->request->get('id');
        // 检查参数
        if (!$id) {
            return $this->redirect(['base/error']);
        }

        $model = RoomCate::find()->where(['id' => $id])->one();

        return $this->render('update', ['model' => $model]);
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
            
            $model = RoomCate::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(200, '已删除');
            }
            
            if ($model->delete()) {
                //记录日志
                OperatorLog::log('删除客房分类', $this->id . '/' . $this->action->id, json_encode($model), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '删除成功');
            } else {
                return MsgUtil::response(-1, '删除失败，请重试');
            }
        }
    }
}