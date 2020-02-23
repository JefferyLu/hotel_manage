<?php
    use yii\helpers\Url;
use backend\models\Room;
?>
<body>
<div class="x-body">
    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">

		<div class="layui-form-item">
            <label class="layui-form-label">客房编号</label>
            <div class="layui-input-inline">
                <input type="text" name="room_id" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填， 和楼层一致，例如，2层1号房是201</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	选择类别
            </label>
            <div class="layui-input-inline">
                <select name="cate_id" class="valid">
                    <?php foreach ($cate_list as $value) : ?>
                        <option value="<?= $value->id ?>"><?= $value->cate_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	选择楼层
            </label>
            <div class="layui-input-inline">
                <select name="floor" class="valid">
                    <?php foreach (Room::$floor as $key => $value) : ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标准价格</label>
            <div class="layui-input-inline">
                <input type="text" name="price" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填 单位元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">打折比例</label>
            <div class="layui-input-inline">
                <input type="text" name="discount" class="layui-input" value="100">
            </div>
            <div class="layui-form-mid layui-word-aux">0-100%</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">会员价格</label>
            <div class="layui-input-inline">
                <input type="text" name="member_price" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填 单位元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">VIP会员价格</label>
            <div class="layui-input-inline">
                <input type="text" name="vip_price" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填 单位元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	选择状态
            </label>
            <div class="layui-input-inline">
                <select name="status" class="valid">
                    <?php foreach (Room::$status as $key => $value) : ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-inline">
                <textarea name="remark" class="layui-textarea"></textarea>
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
        dialog.presentForm('<?= Url::to(['room/create']) ?>');
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

