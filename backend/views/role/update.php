<?php
    use yii\helpers\Url;
?>
<body>
<div class="x-body">
    <form action="" method="post" class="layui-form layui-form-pane" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="hidden" name="role_id" value="<?= $model->role_id ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">
                角色名称
            </label>
            <div class="layui-input-inline">
                <input type="text" name="role_name" required="" lay-verify="required" class="layui-input" value="<?= $model->role_name ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                分配权限
            </label>
            <table  class="layui-table layui-input-block">
                <tbody>
                <?php foreach($authList as $key => $value): ?>
                <tr>
                    <td>
                        <b><?= $key ?></b>
                    </td>
                    <td>
                        <?php foreach($value as $con_name => $action):?>
                        <div class="layui-input-block">
                            <input type="checkbox" name="auth[]" <?php if (in_array($action, json_decode($model->role_auth,true))) {echo 'checked';} ?> lay-skin="primary" title="<b><?= $con_name ?></b>" value="<?=$action?>" />
                        </div>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                描述
            </label>
            <div class="layui-input-block">
                <textarea placeholder="请输入内容" name="role_desc" class="layui-textarea"><?= $model->role_desc ?></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="">提交</button>
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
        dialog.presentForm('<?= Url::to(['role/update']) ?>');
        return false;
    }
</script>
</body>
