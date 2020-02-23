<?php
use yii\helpers\Url;
use backend\models\Goods;
use yii\widgets\ActiveForm;
?>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:;">首页</a>
        <a><cite>商品信息管理</cite></a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i>
    </a>
</div>
<div class="x-body">
    <xblock>
    	<?php $form = ActiveForm::begin(['id' => 'searchform', 'method' => 'get', 'options' => ['class' => 'layui-form layui-col-md12 x-so']]); ?>
        <input class="layui-input" placeholder="商品名称" name="goods_name"
               value="<?php echo Yii::$app->getRequest()->get('goods_name', ''); ?>">
               商品分类 ：
        <div class="layui-input-inline">
        	<select name="goods_cate" class="valid">
        		<option value="0">全部</option>
                <?php foreach ($cate_list as $value) : ?>
                    <option value="<?= $value->id ?>" <?php if (Yii::$app->getRequest()->get('goods_cate', 0) == $value->id) {echo 'selected';} ?>><?= $value->goods_cate ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        <?php ActiveForm::end(); ?>
        
        <button class="layui-btn" onclick="x_admin_show('新增商品','<?= Url::to(['goods/create']) ?>')"><i class="layui-icon"></i>新增商品</button>
        <span class="x-right" style="line-height:40px">共有数据: <?= $pager->totalCount ?> 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th width="30">ID</th>
            <th>商品名称</th>
            <th>商品类别</th>
            <th>价格</th>
            <th>备注</th>
            <th>创建时间</th>
            <th>更新时间</th>
            <th>操作</th>
        </thead>
        <tbody>
        <?php foreach($list as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= $item['goods_name'] ?></td>
                <td><?= $item['goods_cate_name'] ?></td>
                <td><?= $item['goods_price'] . '元/' . Goods::$goods_unit[$item['goods_unit']] ?></td>
                <td><?= $item['remark'] ?></td>
                <td><?= date('Y-m-d H:i:s', $item['created_at']) ?></td>
                <td><?= date('Y-m-d H:i:s', $item['updated_at']) ?></td>
                <td class="td-manage">
                    <button class="layui-btn layui-btn layui-btn-xs"  onclick="x_admin_show('编辑', '<?= Url::to(['goods/update', 'id' => $item['id']]) ?>')" ><i class="layui-icon">&#xe642;</i>编辑</button>
                    <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="del(<?= $item['id'] ?>);" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="page">
        <div>
            <?php echo yii\widgets\LinkPager::widget([
                'pagination' => $pager,
                'prevPageLabel' => '&lt;&lt;',
                'nextPageLabel' => '&gt;&gt;',
            ]); ?>
        </div>
    </div>
</div>
<style type="text/css">

</style>
<script>
    function del(id) {
        dialog.confirm('确定删除吗?', "<?= Url::to(['goods/del']) ?>", '', {'id' : id, '_csrf-backend' : '<?= Yii::$app->request->csrfToken ?>'});
    }
</script>

</body>