<?php
    use yii\helpers\Url;
?>
<body>
<div class="x-body">
    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">
                父级权限
            </label>
            <div class="layui-input-inline">
                <select  name="auth_pid" class="valid">
                    <option value="0">一级权限</option>
                    <?= $pidList ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限名称</label>
            <div class="layui-input-inline">
                <input type="text" name="auth_name" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">控制器</label>
            <div class="layui-input-inline">
                <input type="text" name="auth_controller" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">方法</label>
            <div class="layui-input-inline">
                <input type="text" name="auth_action" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="text" name="auth_sort" value="10" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">数值越小, 排序越靠前, 默认值为10</div>
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
        dialog.presentForm('<?= Url::to(['auth/create']) ?>');
        return false;
    }
</script>
</body>

