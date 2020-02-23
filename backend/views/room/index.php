<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Room;
?>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:;">首页</a>
        <a><cite>客房信息管理</cite></a>
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
               客房分类 ：
        <div class="layui-input-inline">
        	<select name="cate_id" class="valid">
        		<option value="0">全部</option>
                <?php foreach ($cate_list as $value) : ?>
                    <option value="<?= $value->id ?>" <?php if (Yii::$app->getRequest()->get('cate_id', 0) == $value->id) {echo 'selected';} ?>><?= $value->cate_name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
         客房状态 ：
        <div class="layui-input-inline">
        	<select name="status" class="valid">
        		<option value="0">全部</option>
                <?php foreach (Room::$status as $key => $value) : ?>
                    <option value="<?= $key ?>" <?php if (Yii::$app->getRequest()->get('status', 0) == $key) {echo 'selected';} ?>><?= $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        <?php ActiveForm::end(); ?>
        
        <button class="layui-btn" onclick="x_admin_show('新增客房信息','<?= Url::to(['room/create']) ?>')"><i class="layui-icon"></i>新增客房信息</button>
        <span class="x-right" style="line-height:40px">共有数据: <?= $pager->totalCount ?> 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th width="30">ID</th>
            <th>客房编号</th>
            <th>客房类别</th>
            <th>楼层</th>
            <th>标准价格</th>
            <th>打折比例</th>
            <th>普通会员价格</th>
            <th>VIP会员价格</th>
            <th>状态</th>
            <th>备注</th>
            <th>创建时间</th>
            <th>更新时间</th>
            <th>操作</th>
        </thead>
        <tbody>
        <?php foreach($list as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= $item['room_id'] ?></td>
                <td><?= $item['room_cate_name'] ?></td>
                <td><?= $item['floor'] ?></td>
                <td><?= $item['price'] ?>元</td>
                <td><?= $item['discount'] ?>%</td>
                <td><?= $item['member_price'] ?>元</td>
                <td><?= $item['vip_price'] ?>元</td>
                <td><?= Room::$status[$item['status']] ?></td>
                <td><?= $item['remark'] ?></td>
                <td><?= date('Y-m-d H:i:s', $item['created_at']) ?></td>
                <td><?= date('Y-m-d H:i:s', $item['updated_at']) ?></td>
                <td class="td-manage">
                    <button class="layui-btn layui-btn layui-btn-xs"  onclick="x_admin_show('编辑', '<?= Url::to(['room/update', 'id' => $item['id']]) ?>')" ><i class="layui-icon">&#xe642;</i>编辑</button>
                    <?php if ($item['status'] == Room::STATUS_EMPTY) : ?>
                    <button class="layui-btn layui-btn layui-btn-xs"  onclick="x_admin_show('预定', '<?= Url::to(['reserve-info/create', 'room_id' => $item['id']]) ?>')" ><i class="layui-icon">&#xe642;</i>预定</button>
                    <?php endif; ?>
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
        dialog.confirm('确定删除吗?', "<?= Url::to(['room/del']) ?>", '', {'id' : id, '_csrf-backend' : '<?= Yii::$app->request->csrfToken ?>'});
    }
</script>

</body>