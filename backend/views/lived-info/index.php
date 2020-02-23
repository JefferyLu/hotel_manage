<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\ReserveInfo;
use backend\models\LivedInfo;
?>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:;">首页</a>
        <a><cite>客房入住信息管理</cite></a>
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
         客房入住状态 ：
        <div class="layui-input-inline">
        	<select name="status" class="valid">
        		<option value="0">全部</option>
                <?php foreach (LivedInfo::$status as $key => $value) : ?>
                    <option value="<?= $key ?>" <?php if (Yii::$app->getRequest()->get('status', 0) == $key) {echo 'selected';} ?>><?= $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        <?php ActiveForm::end(); ?>
        
        <button class="layui-btn" onclick="x_admin_show('手工登记入住','<?= Url::to(['lived-info/create']) ?>', 1000, 540)"><i class="layui-icon"></i>手工登记入住</button>
        <span class="x-right" style="line-height:40px">共有数据: <?= $pager->totalCount ?> 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
        	<th>ID</th>
            <th>客房编号</th>
            <th>押金</th>
            <th>入住人</th>
            <th>证件类型</th>
            <th>证件号码</th>
            <th>联系电话</th>
            <th>入住时间</th>
            <th>入住人数</th>
            <th>入住状态</th>
            <th>操作</th>
        </thead>
        <tbody>
        <?php foreach($list as $item): ?>
            <tr>
            	<td><?= $item['id'] ?></td>
                <td><?php echo $item['new_room_id'] > 0 ? $item['new_room_id'] : $item['room_id'] ?></td>
                <td><?= $item['deposit_price'] ?></td>
                <td><?= $item['lived_name'] ?></td>
                <td><?= ReserveInfo::$id_type[$item['id_type']] ?></td>
                <td><?= $item['id_no'] ?></td>
                <td><?= $item['phone'] ?></td>
                <td><?= date('Y-m-d', $item['arrive_time']) . '至' . date('Y-m-d', $item['leave_time']) ?></td>
                <td><?= $item['live_num'] ?></td>
                <td><?= LivedInfo::$status[$item['status']] ?></td>
                <td class="td-manage">
                    <button class="layui-btn layui-btn layui-btn-xs"  onclick="x_admin_show('查看详情', '<?= Url::to(['lived-info/show', 'id' => $item['id']]) ?>')" >查看详情</button>
                    <?php if ($item['status'] == LivedInfo::STATUS_LIVED) : ?>
                    <button class="layui-btn layui-btn layui-btn-xs"  onclick="x_admin_show('换房', '<?= Url::to(['lived-info/change', 'id' => $item['id']]) ?>')" >换房</button>
                    <button class="layui-btn layui-btn layui-btn-xs"  onclick="x_admin_show('结算', '<?= Url::to(['lived-info/settle', 'id' => $item['id']]) ?>')" >结算</button>
                    <?php endif; ?>
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
</body>