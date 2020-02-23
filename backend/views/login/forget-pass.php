<?php
use yii\helpers\Url;
?>
<body class="login-bg">
<div class="login layui-anim layui-anim-up">
    <div class="message">学生信息管理系统 忘记密码</div>
    <div id="darkbannerwrap"></div>

    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">

        <input name="admin_name" placeholder="用户名"  type="text" class="layui-input" >
        <hr class="hr15">
        <input name="admin_email" placeholder="邮箱"  type="text" class="layui-input">
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
        dialog.presentForm('<?= Url::to(['login/forget-pass']) ?>', '<?= Url::to(['login/login']) ?>');
        return false;
    }
</script>
<!-- 底部结束 -->
</body>