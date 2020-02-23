<?php
namespace backend\controllers;

use Yii;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\OperatorLog;
use backend\models\ReserveInfo;
use backend\models\RoomCate;
use backend\models\Room;
use backend\models\Member;
use backend\models\LivedInfo;
use backend\models\SettleOrder;

/**
 * 客房入住控制器
 */
class LivedInfoController extends BaseController
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
            if(isset($search['reserve_no'])&& !empty($search['reserve_no'])){
                $condition .= " AND r.reserve_no = '" . trim($search['reserve_no']) . "'";
            }
            if(isset($search['status'])&& !empty($search['status'])){
                $condition .= " AND r.status = '" . intval($search['status']) . "'";
            }
        }
        
        $model = LivedInfo::find();
        $query = $model->select(['r.*', 'rm.price', 'rm.discount', 'rmc.cate_name'])
                    ->from("{{%lived_info}} as r")
                    ->leftJoin("{{%room}} as rm", 'rm.id = r.room_id')
                    ->leftJoin("{{%room_cate}} as rmc", 'rmc.id = r.room_cate_id')
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
        $room_id = \Yii::$app->request->get('room_id', 0);
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if ($room_id <= 0) {
                $room_id = $post['room_id'];
            }
            
            //获取客房信息
            $room_info = Room::findOne(['room_id' => $room_id]);
            if (empty($room_info)) {
                return MsgUtil::response(-1, '客房信息不存在');
            }
            
            //客房状态为空房状态才可以进行入住操作
            if ($room_info->status != Room::STATUS_EMPTY) {
                return MsgUtil::response(-1, '客房状态为空房状态才可以进行入住操作');
            }
            
            //校验参数
            if (empty($post['lived_name'])) {
                return MsgUtil::response(-1, '入住人不能为空');
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
            $query = ReserveInfo::find();
            $where = "(arrive_time >= {$arrive_time} AND arrive_time <= {$leave_time})
                        OR (arrive_time <= {$arrive_time} AND leave_time >= {$leave_time})
                        OR (arrive_time >= {$arrive_time} AND leave_time <= {$leave_time})";
            $isReserve = $query->where(['room_id' => $room_id])
                            ->andWhere(['NOT IN', 'status', [ReserveInfo::STATUS_CANCEL, ReserveInfo::STATUS_LIVE]]) //排除已取消已入住的预定
                            ->andWhere($where)->all();
            if ($isReserve) {
                return MsgUtil::response(-1, '该时间段内已有预定，不可进行入住操作。');
            }
            $where = "(arrive_time >= {$arrive_time} AND arrive_time <= {$leave_time})
                    OR (arrive_time <= {$arrive_time} AND leave_time >= {$leave_time})
                    OR (arrive_time >= {$arrive_time} AND leave_time <= {$leave_time})";
            $isLived = LivedInfo::find()->where(['room_id' => $room_id])
                            ->andWhere(['<>', 'status', LivedInfo::STATUS_SETTLE]) //排除已结算的房间
                            ->andWhere($where)->all();
            if ($isLived) {
                return MsgUtil::response(-1, '该时间段内已有入住，不可进行入住操作。');
            }
            
            $info = new LivedInfo();
            $info->room_id = $room_id;
            $info->room_cate_id = $room_info->cate_id;
            $info->deposit_price = $post['deposit_price'];
            $info->member_phone = $post['member_phone'];
            $info->lived_name = $post['lived_name'];
            $info->id_type = $post['id_type'];
            $info->id_no = $post['id_no'];
            $info->phone = $post['phone'];
            $info->arrive_time = strtotime($post['arrive_time']);
            $info->leave_time = strtotime($post['leave_time']);
            $info->live_num = intval($post['live_num']);
            $info->is_breakfast = intval($post['is_breakfast']);
            $info->is_alarm = intval($post['is_alarm']);
            $info->remark = $post['remark'];
            $info->status = LivedInfo::STATUS_LIVED;
            $info->created_at = $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('添加入住信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '添加成功');
            }
            return MsgUtil::response(-1, '添加失败，请重试');
        }

        return $this->render('create', ['room_id' => $room_id]);
    }

    /**
     * 换房操作
     *
     * @return string
     * @throws \Throwable
     */
    public function actionChange()
    {
        $id = \Yii::$app->request->get('id', 0);
        if (Yii::$app->request->isAjax) {
            $post = \Yii::$app->request->post();
            $id = \Yii::$app->request->post('id', 0);
            $room_id = \Yii::$app->request->post('room_id', 0);
            $new_room_id = \Yii::$app->request->post('new_room_id', 0);
            
            //获取房间信息
            $room = Room::findOne(['room_id' => $room_id]);
            if (empty($room)) {
                return MsgUtil::response(-1, '原房间信息不存在');
            }
            
            //获取入住信息
            $model = LivedInfo::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(-1, '入住信息不存在');
            }
            
            //判断如果已结算则不能换房
            if ($model->status == LivedInfo::STATUS_SETTLE) {
                return MsgUtil::response(-1, '已结算，不能进行换房操作');
            }
            
            //获取房间信息
            $new_room = Room::findOne(['room_id' => $new_room_id]);
            if (empty($new_room)) {
                return MsgUtil::response(-1, '新房间信息不存在');
            }
            //非空房状态不能换房
            if ($new_room->status != Room::STATUS_EMPTY) {
                return MsgUtil::response(-1, '新房间不是空房，当前状态为：' . Room::$status[$new_room->status]);
            }
            
            $model->new_room_id = $new_room->room_id;
            $model->new_room_cate_id = $new_room->cate_id;
            $model->is_change = 1;
            if ($model->save()) {
                //记录日志
                OperatorLog::log('换房操作', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '换房成功');
            } else {
                return MsgUtil::response(-1, '换房失败，请重试');
            }
        }
        
        //获取入住信息
        $model = LivedInfo::findOne(['id' => $id]);
        if (empty($model)) {
            return MsgUtil::response(-1, '入住信息不存在');
        }
        
        return $this->render('change', ['model' => $model]);
    }
    
    /**
     * 结算操作
     */
    public function actionSettle(){
        $id = \Yii::$app->request->get('id', 0);
        if ($id <= 0) {
            $id = \Yii::$app->request->post('id', 0);
        }

        //获取入住信息
        $model = LivedInfo::findOne(['id' => $id]);
        if (empty($model)) {
            return MsgUtil::response(-1, '入住信息不存在');
        }
        
        //计算入住天数
        $days = $model->leave_time - $model->arrive_time;
        $days = floor($days / 86400);
        
        //获取房间信息
        $room_id = $model->room_id;
        if ($model->is_change) { //换房使用新房间id
            $room_id = $model->new_room_id;
        }
        $room = Room::findOne(['room_id' => $room_id]);
        if (empty($room)) {
            return MsgUtil::response(-1, '房间信息不存在');
        }
        
        //获取客房单价
        $fee_type = 1; //费用计算类型 默认标准价
        $price = $room->price;
        if ($model->is_discount) { //走折扣价结算
            $price = $price * ($room->discount / 100);
            $fee_type = 2; //折扣价
        } else { //判断是否有会员价
            $member = Member::findOne(['phone' => $model->member_phone]);
            if ($member && $member->level == Member::LEVEL_VIP) {
                $price = $room->vip_price;
                $fee_type = 3; //VIP会员价
            } else if ($member && $member->level == Member::LEVEL_COMMON) {
                $price = $room->member_price;
                $fee_type = 4; //普通会员价
            }
        }
        //计算住宿费
        $live_price = $price * $days - $model->deposit_price;
        
        if (Yii::$app->request->isAjax) {
            $id = \Yii::$app->request->post('id', 0);
            $leave_time = strtotime(\Yii::$app->request->post('leave_time', 0));
            $real_recipt = \Yii::$app->request->post('real_recipt', 0);
            $change_price = \Yii::$app->request->post('change_price', 0);
            $payment = \Yii::$app->request->post('payment', 0);
            $remark = \Yii::$app->request->post('remark', '');
            $post = \Yii::$app->request->post();

            //获取入住信息
            $model = LivedInfo::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(-1, '入住信息不存在');
            }
            
            //实际计算入住天数
            $days = $leave_time > 0 ? $leave_time - $model->arrive_time : $model->leave_time - $model->arrive_time;
            $days = floor($days / 86400);
            
            //获取客房单价
            $fee_type = 1; //费用计算类型 默认标准价
            $price = $room->price;
            if ($model->is_discount) { //走折扣价结算
                $price = $price * ($room->discount / 100);
                $fee_type = 2; //折扣价
            } else { //判断是否有会员价
                $member = Member::findOne(['phone' => $model->member_phone]);
                if ($member && $member->level == Member::LEVEL_VIP) {
                    $price = $room->vip_price;
                    $fee_type = 3; //VIP会员价
                } else if ($member && $member->level == Member::LEVEL_COMMON) {
                    $price = $room->member_price;
                    $fee_type = 4; //普通会员价
                }
            }
            //计算住宿费
            $live_price = $price * $days;
            
            //进行入库操作，这里牵扯多表修改，需要增加事务处理，保证数据一致性
            try{
                $db = \Yii::$app->get('db');
                $transaction = $db->beginTransaction();
                
                $settle = new SettleOrder();
                $settle->lived_id = $model->id;
                $settle->room_id = $room_id;
                $settle->live_days = $days;
                $settle->live_price = $live_price;
                $settle->receivable = $live_price;
                $settle->return_deposit = $model->deposit_price;
                $settle->real_recipt = $real_recipt - $model->deposit_price;
                $settle->change_price = $change_price;
                $settle->payment = $payment;
                $settle->operator_id = \Yii::$app->user->identity->id;
                $settle->remark = $remark;
                $settle->created_at = $settle->updated_at = time();
                if (!$settle->save()) {
                    throw new \Exception('结算订单失败！', -10);
                }
                
                //房间状态变为未打扫
                $room->status = Room::STATUS_UNCLEAN;
                if (!$room->save()) {
                    throw new \Exception('房间状态修改失败!', -11);
                }
                
                //入住状态修改
                $model->status = LivedInfo::STATUS_SETTLE;
                if (!$model->save()) {
                    throw new \Exception('入住状态修改失败!', -12);
                }
                
                //增加会员积分  更新会员等级
                if ($member) {
                    $score = $member->score + $live_price;
                    if ($member->level == Member::LEVEL_COMMON && $score >= Member::VIP_LIMIT_SCORE) {
                        $member->level = Member::LEVEL_VIP;
                    }
                    if (!$member->save()) {
                        throw new \Exception('更新会员信息失败!', -11);
                    }
                }
                
                $transaction->commit();
                
                //记录日志
                OperatorLog::log('结算成功', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '结算成功');
    		} catch (\Exception $e){
    			$transaction->rollBack();
    			
    			return MsgUtil::response($e->getCode(), $e->getMessage());
    		}
        }

        return $this->render('settle', ['model' => $model, 'room' => $room, 'days' => $days, 'price' => $price, 'live_price' => $live_price, 'fee_type' => $fee_type]);
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
    
        $model = LivedInfo::find()->where(['id' => $id])->one();
    
        return $this->render('show', ['model' => $model]);
    }
}