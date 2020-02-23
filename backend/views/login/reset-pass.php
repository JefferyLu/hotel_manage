<?php
use yii\helpers\Url;
?>
<body class="login-bg">
<div class="login layui-anim layui-anim-up">
    <div class="message">学生信息管理系统 重置密码</div>
    <div id="darkbannerwrap"></div>

    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="hidden" name="time" value="<?= Yii::$app->request->get('time') ?>">
        <input type="hidden" name="token" value="<?= Yii::$app->request->get('token') ?>">
        <input type="hidden" name="admin_name" value="<?= Yii::$app->request->get('admin_name') ?>">

        <input name="newPass" placeholder="新密码"  type="password" class="layui-input" >
        <hr class="hr15">
        <input name="rePass" placeholder="确认密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input value="提交" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>

    <a href="<?= Url::to(['login/login']) ?>" class='x-a'>登录</a>
</div>

<script>
    /**
     * 数据提交
     *
     * @returns {boolean}
     */
    function present() {
        dialog.presentForm('<?= Url::to(['login/reset-pass']) ?>', '<?= Url::to(['login/login']) ?>');
        return false;
    }
</script>
<!-- 底部结束 -->
</body>