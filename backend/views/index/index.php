<?php
    use yii\helpers\Url;
?>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="./index.html">酒店客房管理系统</a></div>
    <div class="left_open">
        <i title="展开左侧栏" class="iconfont">&#xe699;</i>
    </div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;"><?= Yii::$app->user->identity->name ?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="x_admin_show('修改信息','<?= Url::to(['admin/change-info']) ?>', 600, 400)">修改信息</a></dd>
                <dd><a onclick="x_admin_show('修改密码','<?= Url::to(['admin/change-pass']) ?>', 600, 400)">修改密码</a></dd>
                <dd><a href="javascript:;" onclick="logout()">退出</a></dd>
            </dl>
        </li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <?php foreach ($menu as $auth): ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe726;</i>
                    <cite><?= $auth['auth_name'] ?></cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <?php foreach ($auth['child'] as $childAuth): ?>
                    <li>
                        <a _href="<?= Url::to([$childAuth['auth_controller'] . '/' . $childAuth['auth_action']]) ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite><?= $childAuth['auth_name'] ?></cite>
                        </a>
                    </li >
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>我的桌面</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src="<?= Url::to(['index/welcome']); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2019 <a href="http://github.com/duiying/Yii2-Admin" class='x-a' target="_blank">Yii2-Admin</a> All Rights Reserved</div>
</div>
<!-- 底部结束 -->

<script type="text/javascript">
    function logout() {
        dialog.confirm('确定要退出吗?', "<?= Url::to(['login/logout']) ?>", "<?= Url::to(['login/login']) ?>", {'_csrf-backend' : '<?= Yii::$app->request->csrfToken ?>'});
    }
</script>