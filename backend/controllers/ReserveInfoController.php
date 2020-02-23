<?php
namespace backend\controllers;

use Yii;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\OperatorLog;
use backend\models\ReserveInfo;
use backend\models\Room;
use backend\models\Member;
use backend\models\LivedInfo;

/**
 * 客房预定控制器
 */
class ReserveInfoController extends BaseController
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
            if(isset($search['status'])&& !empty($search['status'])){
                $condition .= " AND r.status = '" . intval($search['status']) . "'";
            }
        }
        
        $model = ReserveInfo::find();
        $query = $model->select(['r.*', 'rm.price', 'rm.discount', 'rmc.cate_name'])
                    ->from("{{%reserve_info}} as r")
                    ->leftJoin("{{%room}} as rm", 'rm.id = r.room_id')
                    ->leftJoin("{{%room_cate}} as rmc", 'rmc.id = r.room_cate_id')
                    ->where($condition);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count('*')]);
        $pages->pageSize = 10;
        $list = $query->offset($pages->offset)->limit($pages->limit)->orderBy("r.created_at DESC")->asArray()->all();
        
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
        $room_id = \Yii::$app->request->get('room_id', 0);
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $room_id = \Yii::$app->request->post('room_id', 0);
            
            //获取客房信息
            $room_info = Room::findOne(['room_id' => $room_id]);
            if (empty($room_info)) {
                return MsgUtil::response(-1, '客房信息不存在');
            }
            
            //客房状态为空房状态才可以进行预定 
            if ($room_info->status != Room::STATUS_EMPTY) {
                return MsgUtil::response(-1, '客房状态为空房状态才可以进行预定');
            }
            
            //校验参数
            if (empty($post['reserve_name'])) {
                return MsgUtil::response(-1, '预订人不能为空');
            }
            if (empty($post['id_no'])) {
                return MsgUtil::response(-1, '证件号码不能为空');
            }
            if (empty($post['phone'])) {
                return MsgUtil::response(-1, '联系电话不能为空');
            }
            if (empty($post['arrive_time'])) {
                return MsgUtil::response(-1, '到店时间不能为空');
            }
            if (empty($post['leave_time'])) {
                return MsgUtil::response(-1, '离店时间不能为空');
            }
            if (empty($post['live_num'])) {
                return MsgUtil::response(-1, '入住人数不能为空');
            }
            
            //判断会员是否存在
            $member_info = [];
            if (!empty($post['member_phone'])) {
                $member_info = Member::findOne(['phone' => $post['phone']]);
                if (empty($member_info)) {
                    return MsgUtil::response(-1, '会员不存在！');
                }
            }
            
            $arrive_time = strtotime($post['arrive_time']);
            $leave_time = strtotime($post['leave_time']);
            //判断同一间房在相同时间内是否有预定或者入住
            $where = "(arrive_time >= {$arrive_time} AND arrive_time <= {$leave_time})
                        OR (arrive_time <= {$arrive_time} AND leave_time >= {$leave_time})
                        OR (arrive_time >= {$arrive_time} AND leave_time <= {$leave_time})";
            $isReserve = ReserveInfo::find()->where(['room_id' => $room_id])
                            ->andWhere(['NOT IN', 'status', [ReserveInfo::STATUS_CANCEL, ReserveInfo::STATUS_LIVE]]) //排除已取消已入住的预定
                            ->andWhere($where)->all();
            if ($isReserve) {
                return MsgUtil::response(-1, '该时间段内已有预定，不可再次预定。');
            }
            $where = "(arrive_time >= {$arrive_time} AND arrive_time <= {$leave_time})
                    OR (arrive_time <= {$arrive_time} AND leave_time >= {$leave_time})
                    OR (arrive_time >= {$arrive_time} AND leave_time <= {$leave_time})";
            $isLived = LivedInfo::find()->where(['room_id' => $room_id])
                            ->andWhere(['<>', 'status', LivedInfo::STATUS_SETTLE]) //排除已结算的房间
                            ->andWhere($where)->all();
            if ($isLived) {
                return MsgUtil::response(-1, '该时间段内已有入住，不可进行预定。');
            }
            
            $info = new ReserveInfo();
            $info->room_id = $room_id;
            $info->room_cate_id = $room_info->cate_id;
            $info->deposit_price = $post['deposit_price'];
            $info->member_phone = $post['member_phone'];
            $info->reserve_name = $post['reserve_name'];
            $info->id_type = $post['id_type'];
            $info->id_no = $post['id_no'];
            $info->phone = $post['phone'];
            $info->arrive_time = strtotime($post['arrive_time']);
            $info->leave_time = strtotime($post['leave_time']);
            $info->live_num = intval($post['live_num']);
            $info->remark = $post['remark'];
            $info->status = ReserveInfo::STATUS_RESERVE;
            $info->created_at = $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('添加预定信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '添加成功');
            }
            return MsgUtil::response(-1, '添加失败，请重试');
        }

        return $this->render('create', ['room_id' => $room_id]);
    }

    /**
     * 取消
     *
     * @return string
     * @throws \Throwable
     */
    public function actionCancel()
    {
        if (Yii::$app->request->isAjax) {
            $id = \Yii::$app->request->post('id', 0);
            
            $model = ReserveInfo::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(-1, '预定不存在');
            }
            
            //判断如果已入住则不能取消
            if ($model->status == ReserveInfo::STATUS_LIVE) {
                return MsgUtil::response(-1, '已入住，不能取消操作');
            }
            
            $model->status = ReserveInfo::STATUS_CANCEL;
            if ($model->save()) {
                //记录日志
                OperatorLog::log('取消预定信息', $this->id . '/' . $this->action->id, json_encode($model), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '取消成功');
            } else {
                return MsgUtil::response(-1, '取消失败，请重试');
            }
        }
    }
    
    /**
     * 查看详情
     * @return \yii\web\Response|string
     */
    public function actionShow(){
        $id = Yii::$app->request->get('id');
        // 检查参数
        if (!$id) {
            return $this->redirect(['base/error']);
        }
        
        $model = ReserveInfo::find()->where(['id' => $id])->one();
        
        return $this->render('show', ['model' => $model]);
    }
    
    /**
     * 预定转入住
     */
    public function actionReserveToLived(){
        if (Yii::$app->request->isAjax) {
            $id = \Yii::$app->request->post('id', 0);
            $post = \Yii::$app->request->post();
            
            $model = ReserveInfo::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(-1, '预定不存在');
            }
            
            //非预定状态不能转入住
            if ($model->status != ReserveInfo::STATUS_RESERVE) {
                return MsgUtil::response(-1, '非预定状态，不能转入住');
            }
            
            //进行入库操作，这里牵扯多表修改，需要增加事务处理，保证数据一致性
            try{
                $db = \Yii::$app->get('db');
                $transaction = $db->beginTransaction();
                
                $info = new LivedInfo();
                $info->reserve_id = $id;
                $info->room_id = $model->room_id;
                $info->room_cate_id = $model->room_cate_id;
                $info->deposit_price = $model->deposit_price;
                $info->member_phone = $model->member_phone;
                $info->lived_name = $model->reserve_name;
                $info->id_type = $model->id_type;
                $info->id_no = $model->id_no;
                $info->phone = $model->phone;
                $info->arrive_time = $model->arrive_time;
                $info->leave_time = $model->leave_time;
                $info->live_num = $model->live_num;
                $info->remark = $model->remark;
                $info->status = LivedInfo::STATUS_LIVED;
                $info->created_at = $info->updated_at = time();
                if (!$info->save()) {
                    throw new \Exception('保存入住表失败', -10);
                }
                
                //更新预定表
                $model->status = ReserveInfo::STATUS_LIVE;
                if (!$model->save()) {
                    throw new \Exception('更新入住表状态失败', -11);
                }
                
                $transaction->commit();
                
                //记录日志
                OperatorLog::log('预定转入住成功', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '预定转入住成功');
            } catch (\Exception $e){
                $transaction->rollBack();
                 
                return MsgUtil::response($e->getCode(), $e->getMessage());
            }
        }
    }
}