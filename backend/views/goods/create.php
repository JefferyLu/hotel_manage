<?php
    use yii\helpers\Url;
use backend\models\Goods;
?>
<body>
<div class="x-body">
    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">

		<div class="layui-form-item">
            <label class="layui-form-label">商品名称</label>
            <div class="layui-input-inline">
                <input type="text" name="goods_name" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	选择类别
            </label>
            <div class="layui-input-inline">
                <select name="goods_cate_id" class="valid">
                    <?php foreach ($cate_list as $value) : ?>
                        <option value="<?= $value->id ?>"><?= $value->goods_cate ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品价格</label>
            <div class="layui-input-inline">
                <input type="text" name="goods_price" lay-verify="required" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">必填 单位元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	选择单位
            </label>
            <div class="layui-input-inline">
                <select name="goods_unit" class="valid">
                    <?php foreach (Goods::$goods_unit as $key => $value) : ?>
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
        dialog.presentForm('<?= Url::to(['goods/create']) ?>');
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

