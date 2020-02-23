<?php
namespace backend\controllers;

use Yii;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\OperatorLog;
use backend\models\Member;

/**
 * 会员管理控制器
 */
class MemberController extends BaseController
{
    /**
     * 列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $condition = "1=1";
        if ($this->getRequest()->getIsGet()) {
            $search = $this->request->get();
            if(isset($search['phone'])&& !empty($search['phone'])){
                $condition .= " AND phone = '" . trim($search['phone']) . "'";
            }
            if(isset($search['name'])&& !empty($search['name'])){
                $condition .= " AND name like '%" . trim($search['name']) . "%'";
            }
            if(isset($search['level'])&& !empty($search['level'])){
                $condition .= " AND level = '" . trim($search['level']) . "'";
            }
        }
        
        $model = Member::find();
        $query = $model->select(['*'])
                    ->from("{{%member}}")
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
                return MsgUtil::response(-1, '姓名不能为空');
            }
            if (empty($post['phone'])) {
                return MsgUtil::response(-1, '手机号不能为空');
            }
            
            //检查手机号是否存在
            $model = Member::findOne(['phone' => $post['phone']]);
            if ($model) {
                return MsgUtil::result(-1, '该手机号已存在，请修改后重试');
            }
            
            $info = new Member();
            $info->name = $post['name'];
            $info->phone = $post['phone'];
            $info->gender = $post['gender'];
            $info->address = $post['address'];
            $info->email = $post['email'];
            $info->remark = $post['remark'];
            $info->created_at = $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('添加会员信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
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
            $model = new Member();
            $post = Yii::$app->request->post();
            
            $info = $model::findOne(['id' => $post['id']]);
            if (!$info) {
                return MsgUtil::response(-1, '数据不存在！');
            }
            $info->level = $post['level'];
            //超过指定积分自动设置为vip会员
            if ($post['score'] >= Member::VIP_LIMIT_SCORE) {
                $info->level = Member::LEVEL_VIP;
            }
            $info->gender = $post['gender'];
            $info->address = $post['address'];
            $info->email = $post['email'];
            $info->score = $post['score'];
            $info->remark = $post['remark'];
            $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('编辑会员信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '修改成功');
            }
            return MsgUtil::response(-1, '修改失败');
        }

        $id = Yii::$app->request->get('id');
        // 检查参数
        if (!$id) {
            return $this->redirect(['base/error']);
        }

        $model = Member::find()->where(['id' => $id])->one();
        
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
            
            $model = Member::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(200, '已删除');
            }
            
            if ($model->delete()) {
                //记录日志
                OperatorLog::log('删除会员信息', $this->id . '/' . $this->action->id, json_encode($model), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '删除成功');
            } else {
                return MsgUtil::response(-1, '删除失败，请重试');
            }
        }
    }
}