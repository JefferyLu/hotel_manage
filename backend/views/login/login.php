<?php
    use yii\helpers\Url;
?>
<body class="login-bg">
<div class="login layui-anim layui-anim-up">
    <div class="message">酒店客房管理系统 管理后台</div>
    <div id="darkbannerwrap"></div>

    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">

        <input name="name" placeholder="用户名"  type="text" class="layui-input" >
        <hr class="hr15">
        <input name="password" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input value="登录" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>

    <!-- <a href="<?= Url::to(['login/forget-pass']) ?>" class='x-a'>忘记密码</a>-->
</div>

<script>
    /**
     * 数据提交
     *
     * @returns {boolean}
     */
    function present() {
        dialog.presentForm('<?= Url::to(['login/login']) ?>', '<?= Url::to(['index/index']) ?>');
        return false;
    }
</script>
<!-- 底部结束 -->
</body>