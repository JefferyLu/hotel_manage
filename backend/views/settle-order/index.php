<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Room;
use backend\models\SettleOrder;
?>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:;">首页</a>
        <a><cite>客房结算列表</cite></a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i>
    </a>
</div>
<div class="x-body">
    <xblock>
    	<?php $form = ActiveForm::begin(['id' => 'searchform', 'method' => 'get', 'options' => ['class' => 'layui-form layui-col-md12 x-so']]); ?>
        <input class="layui-input" placeholder="客房编号" name="room_id"
               value="<?php echo Yii::$app->getRequest()->get('room_id', ''); ?>">
         支付类型 ：
        <div class="layui-input-inline">
        	<select name="status" class="valid">
        		<option value="0">全部</option>
                <?php foreach (SettleOrder::$payment as $key => $value) : ?>
                    <option value="<?= $key ?>" <?php if (Yii::$app->getRequest()->get('payment', 0) == $key) {echo 'selected';} ?>><?= $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        <?php ActiveForm::end(); ?>
        
        <span class="x-right" style="line-height:40px">共有数据: <?= $pager->totalCount ?> 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th width="30">ID</th>
            <th>客房编号</th>
            <th>入住人</th>
            <th>入住天数</th>
            <th>住宿费</th>
            <th>实际应收</th>
            <th>应退押金</th>
            <th>实收金额</th>
            <th>找零</th>
            <th>支付方式</th>
            <th>操作人</th>
            <th>备注</th>
            <th>创建时间</th>
            <th>更新时间</th>
        </thead>
        <tbody>
        <?php foreach($list as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= $item['room_id'] ?></td>
                <td><?= $item['lived_name'] ?></td>
                <td><?= $item['live_days'] ?>天</td>
                <td><?= $item['live_price'] ?>元</td>
                <td><?= $item['receivable'] ?>元</td>
                <td><?= $item['return_deposit'] ?>元</td>
                <td><?= $item['real_recipt'] ?>元</td>
                <td><?= $item['change_price'] ?>元</td>
                <td><?= SettleOrder::$payment[$item['payment']] ?></td>
                <td><?= $item['name'] ?></td>
                <td><?= $item['remark'] ?></td>
                <td><?= date('Y-m-d H:i:s', $item['created_at']) ?></td>
                <td><?= date('Y-m-d H:i:s', $item['updated_at']) ?></td>
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
        dialog.confirm('确定删除吗?', "<?= Url::to(['room/del']) ?>", '', {'id' : id, '_csrf-backend' : '<?= Yii::$app->request->csrfToken ?>'});
    }
</script>

</body>