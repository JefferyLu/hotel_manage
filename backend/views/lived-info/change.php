<?php
    use yii\helpers\Url;
?>
<body>
<div class="x-body">
    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">
		<input type="hidden" name="id" value="<?= $model->id ?>">
		
		<div class="layui-form-item">
            <label class="layui-form-label">原客房编号</label>
            <div class="layui-input-inline">
                <input type="text" name="room_id" lay-verify="required" class="layui-input layui-disbaled" value="<?= $model->room_id ?>" readonly>
            </div>
            <div class="layui-form-mid layui-word-aux">必填， 和楼层一致，例如，2层1号房是201</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">新客房编号</label>
            <div class="layui-input-inline">
                <input type="text" name="new_room_id" lay-verify="required" class="layui-input" value="">
            </div>
            <div class="layui-form-mid layui-word-aux">必填， 和楼层一致，例如，2层1号房是201</div>
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
        dialog.presentForm('<?= Url::to(['lived-info/change']) ?>');
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

