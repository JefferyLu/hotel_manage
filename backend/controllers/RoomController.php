<?php
namespace backend\controllers;

use Yii;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\OperatorLog;
use backend\models\Room;
use backend\models\RoomCate;

/**
 * 客房控制器
 */
class RoomController extends BaseController
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
            if(isset($search['room_id'])&& !empty($search['room_id'])){
                $condition .= " AND r.room_id = '" . intval($search['room_id']) . "'";
            }
            if(isset($search['cate_id'])&& !empty($search['cate_id'])){
                $condition .= " AND r.cate_id = '" . intval($search['cate_id']) . "'";
            }
            if(isset($search['status'])&& !empty($search['status'])){
                $condition .= " AND r.status = '" . intval($search['status']) . "'";
            }
        }
        
        $model = Room::find();
        $query = $model->select(['r.*', 'rc.cate_name as room_cate_name'])
                    ->from("{{%room}} as r")
                    ->leftJoin("{{%room_cate}} as rc", 'r.cate_id = rc.id')
                    ->where($condition);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count('*')]);
        $pages->pageSize = 10;
        $list = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        
        //获取客房分类
        $cate_list = RoomCate::find()->all();
        
        return $this->render('index', ['list' => $list, 'pager' => $pages, 'cate_list' => $cate_list]);
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
            if ($post['room_id'] <= 0) {
                return MsgUtil::response(-1, '客房编号不能为0');
            }
            if ($post['cate_id'] <= 0) {
                return MsgUtil::response(-1, '请选择客房分类');
            }
            if ($post['floor'] <= 0) {
                return MsgUtil::response(-1, '请选择客房楼层');
            }
            if ($post['price'] <= 0) {
                return MsgUtil::response(-1, '标准价格不能为0');
            }
            if ($post['member_price'] <= 0) {
                return MsgUtil::response(-1, '普通会员价格不能为0');
            }
            if ($post['vip_price'] <= 0) {
                return MsgUtil::response(-1, 'VIP会员价格不能为0');
            }
            //判断客房编号是否和楼层一致
            if (substr($post['room_id'], 0, 1) != $post['floor']) {
                return MsgUtil::response(-1, '客房编号起始位置与楼层不一致！');
            }
            //每层只有20个客房，超出提示
            if (substr($post['room_id'], 1, 2) <=0 || substr($post['room_id'], 1, 2) > 20) {
                return MsgUtil::response(-1, '每层只有20个客房，编号不能等于0或大于20！');
            }
            
            //检查客房编号是否存在
            $model = Room::findOne(['room_id' => $post['room_id']]);
            if ($model) {
                return MsgUtil::result(-1, '该客房编号已存在，请修改后重试');
            }
            
            $info = new Room();
            $info->room_id = $post['room_id'];
            $info->cate_id = $post['cate_id'];
            $info->floor = $post['floor'];
            $info->price = $post['price'];
            $info->discount = $post['discount'];
            $info->member_price = $post['member_price'];
            $info->vip_price = $post['vip_price'];
            $info->remark = $post['remark'];
            $info->status = Room::STATUS_EMPTY; //默认空房
            $info->created_at = $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('添加客房信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '添加成功');
            }
            return MsgUtil::response(-1, '添加失败，请重试');
        }

        //获取客房分类
        $cate_list = RoomCate::find()->all();
        
        return $this->render('create', ['cate_list' => $cate_list]);
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
            $model = new Room();
            $post = Yii::$app->request->post();
            
            //数据校验
            if ($post['room_id'] <= 0) {
                return MsgUtil::response(-1, '客房编号不能为0');
            }
            if ($post['cate_id'] <= 0) {
                return MsgUtil::response(-1, '请选择客房分类');
            }
            if ($post['floor'] <= 0) {
                return MsgUtil::response(-1, '请选择客房楼层');
            }
            if ($post['price'] <= 0) {
                return MsgUtil::response(-1, '标准价格不能为0');
            }
            if ($post['member_price'] <= 0) {
                return MsgUtil::response(-1, '普通会员价格不能为0');
            }
            if ($post['vip_price'] <= 0) {
                return MsgUtil::response(-1, 'VIP会员价格不能为0');
            }
            if ($post['status'] <= 0) {
                return MsgUtil::response(-1, '请选择客房当前状态');
            }
            //判断客房编号是否和楼层一致
            if (substr($post['room_id'], 0, 1) != $post['floor']) {
                return MsgUtil::response(-1, '客房编号起始位置与楼层不一致！');
            }
            //每层只有20个客房，超出提示
            if (substr($post['room_id'], 1, 2) <=0 || substr($post['room_id'], 1, 2) > 20) {
                return MsgUtil::response(-1, '每层只有20个客房，编号不能等于0或大于20！');
            }
            
            //检查客房编号是否存在
            $nameExist = $model::find()->where(['room_id' => $post['room_id']])
                            ->andWhere(['<>', 'id', $post['id']])->one();
            if ($nameExist) {
                return MsgUtil::response(-1, '客房编号已存在，请重新输入');
            }
            
            $info = $model::findOne(['id' => $post['id']]);
            if (!$info) {
                return MsgUtil::response(-1, '数据不存在！');
            }
            $info->room_id = $post['room_id'];
            $info->cate_id = $post['cate_id'];
            $info->floor = $post['floor'];
            $info->price = $post['price'];
            $info->discount = $post['discount'];
            $info->member_price = $post['member_price'];
            $info->vip_price = $post['vip_price'];
            $info->remark = $post['remark'];
            $info->status = $post['status'];
            $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('编辑客房信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '修改成功');
            }
            return MsgUtil::response(-1, '修改失败');
        }

        $id = Yii::$app->request->get('id');
        // 检查参数
        if (!$id) {
            return $this->redirect(['base/error']);
        }

        $model = Room::find()->where(['id' => $id])->one();
        
        //获取客房分类
        $cate_list = RoomCate::find()->all();

        return $this->render('update', ['model' => $model, 'cate_list' => $cate_list]);
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
            
            $model = Room::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(200, '已删除');
            }
            
            if ($model->delete()) {
                //记录日志
                OperatorLog::log('删除客房信息', $this->id . '/' . $this->action->id, json_encode($model), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '删除成功');
            } else {
                return MsgUtil::response(-1, '删除失败，请重试');
            }
        }
    }
}