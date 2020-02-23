<div class="x-body layui-anim layui-anim-up">
    <blockquote class="layui-elem-quote">欢迎管理员：
        <span class="x-red"><?= Yii::$app->user->identity->name ?></span>！当前时间:<?= $info['time'] ?></blockquote>
    <fieldset class="layui-elem-field">
        <legend>数据统计</legend>
        <div class="layui-field-box">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                            <div carousel-item="">
                                <ul class="layui-row layui-col-space10 layui-this">
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>管理员数</h3>
                                            <p>
                                                <cite>2</cite></p>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs2">
                                        <a href="javascript:;" class="x-admin-backlog-body">
                                            <h3>访问IP数</h3>
                                            <p>
                                                <cite>12</cite></p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>系统信息</legend>
        <div class="layui-field-box">
            <table class="layui-table">
                <tbody>
                <tr>
                    <th>Yii版本</th>
                    <td><?= $info['yii'] ?></td></tr>
                <tr>
                    <th>服务器地址</th>
                    <td><?= $info['ip'] ?></td></tr>
                <tr>
                    <th>操作系统</th>
                    <td><?= $info['system'] ?></td></tr>
                <tr>
                    <th>WEB运行环境</th>
                    <td><?= $info['web'] ?></td></tr>
                <tr>
                    <th>PHP版本</th>
                    <td><?= $info['php'] ?></td></tr>
                <tr>
                    <th>MYSQL版本</th>
                    <td><?= $info['mysql'] ?></td></tr>
                <tr>
                    <th>上传大小限制</th>
                    <td><?= $info['upload'] ?></td></tr>
                <tr>
                    <th>POST大小限制</th>
                    <td><?= $info['post'] ?></td></tr>
                <tr>
                    <th>PHP执行时间限制</th>
                    <td><?= $info['execute'] ?>s</td></tr>
                </tbody>
            </table>
        </div>
    </fieldset>
</div>