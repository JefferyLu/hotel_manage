<?php
namespace backend\controllers;

use Yii;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\GoodsCate;
use backend\models\Goods;
use backend\models\OperatorLog;

/**
 * 商品类别控制器
 */
class GoodsCateController extends BaseController
{
    /**
     * 列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = GoodsCate::find();
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
            if (empty($post['goods_cate'])) {
                return MsgUtil::response(-1, '分类名称不能为空');
            }
            
            //检查分类名称是否存在
            $model = GoodsCate::findOne(['goods_cate' => $post['goods_cate']]);
            if ($model) {
                return MsgUtil::result(-1, '该分类名称已存在，请修改后重试');
            }
            
            $info = new GoodsCate();
            $info->goods_cate = $post['goods_cate'];
            $info->remark = $post['remark'];
            $info->created_at = $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('添加商品类别', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
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
            $model = new GoodsCate();
            $post = Yii::$app->request->post();
            
            //数据校验
            if (empty($post['goods_cate'])) {
                return MsgUtil::response(-1, '分类名称不能为空');
            }
            
            //检查用户名是否存在
            $nameExist = $model::find()->where(['goods_cate' => $post['goods_cate']])
                            ->andWhere(['<>', 'id', $post['id']])->one();
            if ($nameExist) {
                return MsgUtil::response(-1, '分类名称已存在，请重新输入');
            }
            
            $info = $model::findOne(['id' => $post['id']]);
            if (!$info) {
                return MsgUtil::response(-1, '数据不存在！');
            }
            $info->goods_cate = $post['goods_cate'];
            $info->remark = $post['remark'];
            $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('编辑商品类别', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '修改成功');
            }
            return MsgUtil::response(-1, '修改失败');
        }

        $id = Yii::$app->request->get('id');
        // 检查参数
        if (!$id) {
            return $this->redirect(['base/error']);
        }

        $model = GoodsCate::find()->where(['id' => $id])->one();

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
            
            //判断商品分类下是否有商品，如果有则需要先将分类下的商品全部删除后方可删除分类
            $goods = Goods::findOne(['goods_cate_id' => $id]);
            if ($goods) {
                return MsgUtil::response(-1, '不能删除，请先删除该分类下所有商品');
            }
            
            $model = GoodsCate::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(200, '已删除');
            }
            
            if ($model->delete()) {
                //记录日志
                OperatorLog::log('删除商品类别', $this->id . '/' . $this->action->id, json_encode($model), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '删除成功');
            } else {
                return MsgUtil::response(-1, '删除失败，请重试');
            }
        }
    }
}