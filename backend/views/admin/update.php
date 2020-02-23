<?php
    use yii\helpers\Url;
use backend\models\Admin;
?>
<body>
<div class="x-body">
    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="hidden" name="id" value="<?= $model->id ?>">

        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" class="layui-input" value="<?= $model->name ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-inline">
                <input type="text" name="email" lay-verify="required|email" class="layui-input" value="<?= $model->email ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                角色
            </label>
            <div class="layui-input-inline">
                <select  name="role" class="valid">
                    <?php foreach ($role as $key => $value) : ?>
                        <option value="<?= $value['role_id'] ?>" <?php if ($model->role == $value['role_id']) {echo 'selected';} ?>><?= $value['role_name'] ?></option>
                    <?php endforeach; ?>
                </select>
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
        dialog.presentForm('<?= Url::to(['admin/update']) ?>');
        return false;
    }
</script>
</body>

