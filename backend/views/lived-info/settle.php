<?php
    use yii\helpers\Url;
use backend\models\SettleOrder;
?>
<body>
<div class="x-body">
    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">
		<input type="hidden" name="id" value="<?= $model->id ?>">
		
		<div class="layui-form-item">
            <label class="layui-form-label">参考费用</label>
            <div class="layui-input-inline">
                	入住天数：<?= $days ?></br>
                	费用单价：<?= $price ?></br>
                	应退押金：<?= $model->deposit_price ?></br>
                	总费用：<?= $live_price ?></br>
                	使用：<?php 
                	           if ($fee_type == 1){ echo "标准价格:{$room->price}"; } 
                	           elseif ($fee_type == 2){ echo "折扣费用,标准价格:{$room->price},折扣:{$room->discount}%"; } 
                	           elseif ($fee_type == 3){ echo "VIP会员费用:{$room->vip_price}"; }
                	           elseif ($fee_type == 4){ echo "普通会员费用:{$room->member_price}"; }
                	      ?>
            </div>
        </div>
		<div class="layui-form-item">
            <label class="layui-form-label">实收金额</label>
            <div class="layui-input-inline">
                <input type="text" name="real_recipt" lay-verify="required" class="layui-input" value="<?= $live_price ?>">
            </div>
            <div class="layui-form-mid layui-word-aux">必填，单位元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">找零</label>
            <div class="layui-input-inline">
                <input type="text" name="change_price" lay-verify="required" class="layui-input" value="0">
            </div>
            <div class="layui-form-mid layui-word-aux">必填，单位元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">离店时间</label>
            <div class="layui-input-inline">
                <input type="text" id="leave_time" name="leave_time" lay-verify="required" class="layui-input" readonly value="<?php echo date('Y-m-d', $model->leave_time);?>">
            </div>
            <div class="layui-form-mid layui-word-aux">必填 格式 2018-09-01</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	支付方式
            </label>
            <div class="layui-input-inline">
                <select name="payment" class="valid">
                    <?php foreach (SettleOrder::$payment as $key => $value) : ?>
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
        dialog.presentForm('<?= Url::to(['lived-info/settle']) ?>');
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

