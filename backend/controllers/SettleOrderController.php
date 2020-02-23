<?php
namespace backend\controllers;

use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\SettleOrder;

/**
 * 结算订单控制器
 */
class SettleOrderController extends BaseController
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
                $condition .= " AND so.room_id = '" . intval($search['room_id']) . "'";
            }
            if(isset($search['payment'])&& !empty($search['payment'])){
                $condition .= " AND so.payment = '" . intval($search['payment']) . "'";
            }
        }
        
        $model = SettleOrder::find();
        $query = $model->select(['so.*', 'li.lived_name', 'a.name'])
                    ->from("{{%settle_order}} as so")
                    ->leftJoin("{{%lived_info}} as li", 'so.lived_id = li.id')
                    ->leftJoin("{{%room}} as r", 'so.room_id = r.room_id')
                    ->leftJoin("{{%admin}} as a", 'so.operator_id = a.id')
                    ->where($condition);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count('*')]);
        $pages->pageSize = 10;
        $list = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        
        return $this->render('index', ['list' => $list, 'pager' => $pages]);
    }
}