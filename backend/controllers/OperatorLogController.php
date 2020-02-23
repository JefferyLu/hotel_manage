<?php
namespace backend\controllers;

use Yii;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\OperatorLog;

/**
 * 操作日志控制器
 */
class OperatorLogController extends BaseController
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
            if(isset($search['action'])&& !empty($search['action'])){
                $condition .= " AND action = '" . trim($search['action']) . "'";
            }
        }
        
        $model = OperatorLog::find();
        $totalCount = $model->where($condition)->count();
        $pageSize = Yii::$app->params['pageSize']['admin'];
        $pager = new Pagination(['totalCount' => $totalCount, 'pageSize' => $pageSize]);
        
        $list = $model->where($condition)->offset($pager->offset)->limit($pager->limit)->orderBy('id DESC')->all();
        
        return $this->render('index', ['list' => $list, 'pager' => $pager]);
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
            
            $model = OperatorLog::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(200, '已删除');
            }
            
            if ($model->delete()) {
                return MsgUtil::response(200, '删除成功');
            } else {
                return MsgUtil::response(-1, '删除失败，请重试');
            }
        }
    }
}