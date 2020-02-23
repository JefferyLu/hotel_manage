<?php
namespace backend\controllers;

use Yii;
use common\models\MsgUtil;
use backend\controllers\BaseController;
use yii\data\Pagination;
use backend\models\Goods;
use backend\models\GoodsCate;
use backend\models\OperatorLog;

/**
 * 商品控制器
 */
class GoodsController extends BaseController
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
            if(isset($search['goods_cate'])&& !empty($search['goods_cate'])){
                $condition .= " AND g.goods_cate_id = '" . intval($search['goods_cate']) . "'";
            }
            if(isset($search['goods_name'])&& !empty($search['goods_name'])){
                $condition .= " AND g.goods_name like '%" . trim($search['goods_name']) . "%'";
            }
        }
        
        $model = Goods::find();
        $query = $model->select(['g.*', 'gc.goods_cate as goods_cate_name'])
                    ->from("{{%goods}} as g")
                    ->leftJoin("{{%goods_cate}} as gc", 'g.goods_cate_id = gc.id')
                    ->where($condition);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count('*')]);
        $pages->pageSize = 10;
        $list = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        
        //获取商品分类
        $cate_list = GoodsCate::find()->all();
        
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
            if (empty($post['goods_name'])) {
                return MsgUtil::response(-1, '商品名称不能为空');
            }
            if ($post['goods_cate_id'] <= 0) {
                return MsgUtil::response(-1, '请选择商品分类');
            }
            if ($post['goods_price'] <= 0) {
                return MsgUtil::response(-1, '商品价格不能为0');
            }
            
            //检查商品名称是否存在
            $model = Goods::findOne(['goods_name' => $post['goods_name']]);
            if ($model) {
                return MsgUtil::result(-1, '该商品名称已存在，请修改后重试');
            }
            
            $info = new Goods();
            $info->goods_name = $post['goods_name'];
            $info->goods_cate_id = $post['goods_cate_id'];
            $info->goods_price = $post['goods_price'];
            $info->goods_unit = $post['goods_unit'];
            $info->remark = $post['remark'];
            $info->created_at = $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('添加商品信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '添加成功');
            }
            return MsgUtil::response(-1, '添加失败，请重试');
        }

        //获取商品分类
        $cate_list = GoodsCate::find()->all();
        
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
            $model = new Goods();
            $post = Yii::$app->request->post();
            
            //数据校验
            if (empty($post['goods_name'])) {
                return MsgUtil::response(-1, '商品名称不能为空');
            }
            if ($post['goods_cate_id'] <= 0) {
                return MsgUtil::response(-1, '请选择商品分类');
            }
            if ($post['goods_price'] <= 0) {
                return MsgUtil::response(-1, '商品价格不能为0');
            }
            
            //检查商品名称是否存在
            $nameExist = $model::find()->where(['goods_name' => $post['goods_name']])
                            ->andWhere(['<>', 'id', $post['id']])->one();
            if ($nameExist) {
                return MsgUtil::response(-1, '商品名称已存在，请重新输入');
            }
            
            $info = $model::findOne(['id' => $post['id']]);
            if (!$info) {
                return MsgUtil::response(-1, '数据不存在！');
            }
            $info->goods_name = $post['goods_name'];
            $info->goods_cate_id = $post['goods_cate_id'];
            $info->goods_price = $post['goods_price'];
            $info->goods_unit = $post['goods_unit'];
            $info->remark = $post['remark'];
            $info->updated_at = time();
            if ($info->save()) {
                //记录日志
                OperatorLog::log('编辑商品信息', $this->id . '/' . $this->action->id, json_encode($post), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '修改成功');
            }
            return MsgUtil::response(-1, '修改失败');
        }

        $id = Yii::$app->request->get('id');
        // 检查参数
        if (!$id) {
            return $this->redirect(['base/error']);
        }

        $model = Goods::find()->where(['id' => $id])->one();
        
        //获取商品分类
        $cate_list = GoodsCate::find()->all();

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
            
            $model = Goods::findOne(['id' => $id]);
            if (empty($model)) {
                return MsgUtil::response(200, '已删除');
            }
            
            if ($model->delete()) {
                //记录日志
                OperatorLog::log('删除商品信息', $this->id . '/' . $this->action->id, json_encode($model), \Yii::$app->user->identity->name);
                return MsgUtil::response(200, '删除成功');
            } else {
                return MsgUtil::response(-1, '删除失败，请重试');
            }
        }
    }
}