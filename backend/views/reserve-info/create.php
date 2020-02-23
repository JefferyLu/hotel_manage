<?php
    use yii\helpers\Url;
use backend\models\ReserveInfo;
?>
<body>
<div class="x-body">
    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">

		<?php if ($room_id > 0) : ?>
		<div class="layui-form-item">
            <label class="layui-form-label">客房编号</label>
            <div class="layui-input-inline">
                <input type="text" name="room_id" lay-verify="required" class="layui-input layui-disbaled" value="<?= $room_id ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">必填， 和楼层一致，例如，2层1号房是201</div>
        </div>
		<?php else: ?>
		<div class="layui-form-item">
            <label class="layui-form-label">客房编号</label>
            <div class="layui-input-inline">
                <input type="text" name="room_id" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填， 和楼层一致，例如，2层1号房是201</div>
        </div>
        <?php endif; ?>
        <div class="layui-form-item">
            <label class="layui-form-label">押金</label>
            <div class="layui-input-inline">
                <input type="text" name="deposit_price" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">单位元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">会员手机号</label>
            <div class="layui-input-inline">
                <input type="text" name="member_phone" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">非必填， 有会员会享受会员价优惠</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">预定人</label>
            <div class="layui-input-inline">
                <input type="text" name="reserve_name" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	证件类型
            </label>
            <div class="layui-input-inline">
                <select name="id_type" class="valid">
                    <?php foreach (ReserveInfo::$id_type as $key => $value) : ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">证件号码</label>
            <div class="layui-input-inline">
                <input type="text" name="id_no" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系电话</label>
            <div class="layui-input-inline">
                <input type="text" name="phone" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">到店时间</label>
            <div class="layui-input-inline">
                <input type="text" id="arrive_time" name="arrive_time" class="layui-input" readonly>
            </div>
            <div class="layui-form-mid layui-word-aux">必填  格式 2018-09-01</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">离店时间</label>
            <div class="layui-input-inline">
                <input type="text" id="leave_time" name="leave_time" class="layui-input" readonly>
            </div>
            <div class="layui-form-mid layui-word-aux">必填 格式 2018-09-01</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">入住人数</label>
            <div class="layui-input-inline">
                <input type="text" name="live_num" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	选择状态
            </label>
            <div class="layui-input-inline">
                <select name="status" class="valid">
                    <?php foreach (ReserveInfo::$status as $key => $value) : ?>
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
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
    </form>
</div>
<script>
    /**
     * 数据提交
     *
     * @returns {boolean}
     */
    function present() {
        dialog.presentForm('<?= Url::to(['reserve-info/create']) ?>');
        return false;
    }

    layui.use('laydate', function(){
        var laydate = layui.laydate;
        
        //执行一个laydate实例
        laydate.render({
          elem: '#arrive_time' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#leave_time' //指定元素
        });
      });
</script>
</body>

