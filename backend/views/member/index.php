<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Member;
?>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="javascript:;">首页</a>
        <a><cite>会员信息管理</cite></a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i>
    </a>
</div>
<div class="x-body">
    <xblock>
    	<?php $form = ActiveForm::begin(['id' => 'searchform', 'method' => 'get', 'options' => ['class' => 'layui-form layui-col-md12 x-so']]); ?>
        <input class="layui-input" placeholder="会员手机号" name="phone"
               value="<?php echo Yii::$app->getRequest()->get('phone', ''); ?>">
        <input class="layui-input" placeholder="会员姓名" name="name"
               value="<?php echo Yii::$app->getRequest()->get('name', ''); ?>">
               会员等级 ：
        <div class="layui-input-inline">
        	<select name="level" class="valid">
        		<option value="0">全部</option>
                <?php foreach (Member::$level as $key => $value) : ?>
                    <option value="<?= $key ?>" <?php if (Yii::$app->getRequest()->get('level', 0) == $key) {echo 'selected';} ?>><?= $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        <?php ActiveForm::end(); ?>
        
        <button class="layui-btn" onclick="x_admin_show('新增会员','<?= Url::to(['member/create']) ?>')"><i class="layui-icon"></i>新增会员</button>
        <span class="x-right" style="line-height:40px">共有数据: <?= $pager->totalCount ?> 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th width="30">ID</th>
            <th>会员姓名</th>
            <th>性别</th>
            <th>手机号</th>
            <th>地址</th>
            <th>email</th>
            <th>等级</th>
            <th>积分</th>
            <th>备注</th>
            <th>最后一次入住时间</th>
            <th>最后一次预定时间</th>
            <th>创建时间</th>
            <th>操作</th>
        </thead>
        <tbody>
        <?php foreach($list as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= $item['name'] ?></td>
                <td><?= Member::$gender[$item['gender']] ?></td>
                <td><?= $item['phone'] ?></td>
                <td><?= !empty($item['address']) ? $item['address'] : '无'; ?></td>
                <td><?= !empty($item['email']) ? $item['email'] : '无' ?></td>
                <td><?= Member::$level[$item['level']] ?></td>
                <td><?= $item['score'] ?></td>
                <td><?= $item['remark'] ?></td>
                <td><?= empty($item['last_live_time']) ? '无' : date('Y-m-d H:i:s', $item['last_live_time']); ?></td>
                <td><?= empty($item['last_reserve_time']) ? '无' : date('Y-m-d H:i:s', $item['last_reserve_time']); ?></td>
                <td><?= date('Y-m-d H:i:s', $item['created_at']) ?></td>
                <td class="td-manage">
                    <button class="layui-btn layui-btn layui-btn-xs"  onclick="x_admin_show('编辑', '<?= Url::to(['member/update', 'id' => $item['id']]) ?>')" ><i class="layui-icon">&#xe642;</i>编辑</button>
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
        dialog.confirm('确定删除吗?', "<?= Url::to(['member/del']) ?>", '', {'id' : id, '_csrf-backend' : '<?= Yii::$app->request->csrfToken ?>'});
    }
</script>

</body>