<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:;">首页</a>
        <a><cite>操作日志</cite></a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i>
    </a>
</div>
<div class="x-body">
    <xblock>
    	<?php $form = ActiveForm::begin(['id' => 'searchform', 'method' => 'get', 'options' => ['class' => 'layui-form layui-col-md12 x-so']]); ?>
        <input class="layui-input" placeholder="根据控制器方法搜索" name="action"
               value="<?php echo Yii::$app->getRequest()->get('action', ''); ?>">
        <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        <?php ActiveForm::end(); ?>
        <button style="height:38px;" onclick="javascript:;"></button>
        <span class="x-right" style="line-height:40px">共有数据: <?= $pager->totalCount ?> 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th width="30">ID</th>
            <th>操作动作</th>
            <th>操作控制器方法</th>
            <th>操作人</th>
            <th>提交post数据</th>
            <th>备注</th>
            <th>操作时间</th>
            <th>操作</th>
        </thead>
        <tbody>
        <?php foreach($list as $item): ?>
            <tr>
                <td><?= $item->id ?></td>
                <td><?= $item->operator ?></td>
                <td><?= $item->action ?></td>
                <td><?= $item->user_name ?></td>
                <td width="30%"><?= $item->content ?></td>
                <td><?= $item->remark ?></td>
                <td><?= date('Y-m-d H:i:s', $item->created_at) ?></td>
                <td class="td-manage">
                    <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="del(<?= $item->id ?>);" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
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
        dialog.confirm('确定删除吗?', "<?= Url::to(['operator-log/del']) ?>", '', {'id' : id, '_csrf-backend' : '<?= Yii::$app->request->csrfToken ?>'});
    }
</script>

</body>