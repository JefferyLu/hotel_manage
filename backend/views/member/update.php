<?php
    use yii\helpers\Url;
use backend\models\Goods;
use backend\models\Member;
?>
<body>
<div class="x-body">
    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">
		<input type="hidden" name="id" value="<?= $model->id ?>">
		
		<div class="layui-form-item">
            <label class="layui-form-label">会员姓名</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" class="layui-input layui-disabled" disabled value="<?= $model->name ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-inline">
                <input type="text" name="phone" lay-verify="required" class="layui-input layui-disabled" disabled value="<?= $model->phone ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	选择性别
            </label>
            <div class="layui-input-inline">
                <select name="gender" class="valid">
                    <?php foreach (Member::$gender as $key => $value) : ?>
                        <option value="<?= $key ?>" <?php if ($key == $model->gender){ echo 'selected'; }?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">地址</label>
            <div class="layui-input-inline">
                <input type="text" name="address" class="layui-input" value="<?= $model->address ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">email</label>
            <div class="layui-input-inline">
                <input type="text" name="email" class="layui-input" value="<?= $model->email ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	选择会员等级
            </label>
            <div class="layui-input-inline">
                <select name="level" class="valid">
                    <?php foreach (Member::$level as $key => $value) : ?>
                        <option value="<?= $key ?>" <?php if ($key == $model->level){ echo 'selected'; }?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">请慎重设置</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">积分</label>
            <div class="layui-input-inline">
                <input type="text" name="score" lay-verify="required" class="layui-input" value="<?= $model->score ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">必填， 如果设为0则积分值会清零，如果设置大于等于<?php echo Member::VIP_LIMIT_SCORE; ?>积分则自动置为VIP会员。</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-inline">
                <textarea name="remark" class="layui-textarea"><?= $model->remark ?></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-submit="">
                提交
            </button>
        </div>
    </form>
</div>
<script>
    /**
     * 数据提交
     *
     * @returns {boolean}
     */
    function present() {
        dialog.presentForm('<?= Url::to(['member/update']) ?>');
        return false;
    }

    layui.use('laydate', function(){
        var laydate = layui.laydate;
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });
</script>
</body>
