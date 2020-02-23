<?php
use backend\models\ReserveInfo;
use backend\models\LivedInfo;
?>
<body>
<div class="x-body">
    <form class="layui-form" id="dataSet" onsubmit="return present();">
        <input name="_csrf-backend" type="hidden" value="<?= Yii::$app->request->csrfToken ?>">

        <?php if ($model->is_change) : ?>
        <div class="layui-form-item">
            <label class="layui-form-label">客房编号</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= $model->new_room_id ?></p>
            </div>
            <div class="layui-form-mid layui-word-aux">必填， 和楼层一致，例如，2层1号房是201</div>
        </div>
        <?php else : ?>
        <div class="layui-form-item">
            <label class="layui-form-label">客房编号</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= $model->room_id ?></p>
            </div>
            <div class="layui-form-mid layui-word-aux">必填， 和楼层一致，例如，2层1号房是201</div>
        </div>
        <?php endif; ?>
        <div class="layui-form-item">
            <label class="layui-form-label">押金</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= $model->deposit_price ?></p>
            </div>
            <div class="layui-form-mid layui-word-aux">单位元</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">会员手机号</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= $model->member_phone ?></p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">入住人</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= $model->lived_name ?></p>
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	证件类型
            </label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= ReserveInfo::$id_type[$model->id_type] ?></p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">证件号码</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= $model->id_no ?></p>
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系电话</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= $model->phone ?></p>
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">到店时间</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= date('Y-m-d', $model->arrive_time) ?></p>
            </div>
            <div class="layui-form-mid layui-word-aux">必填  格式 2018-09-01</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">离店时间</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= date('Y-m-d', $model->leave_time) ?></p>
            </div>
            <div class="layui-form-mid layui-word-aux">必填 格式 2018-09-01</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">入住人数</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= $model->live_num ?></p>
            </div>
            <div class="layui-form-mid layui-word-aux">必填</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	是否提供早餐
            </label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= LivedInfo::$is_breakfast[$model->is_breakfast] ?></p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	是否提供早叫服务
            </label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= LivedInfo::$is_alarm[$model->is_alarm] ?></p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                	状态
            </label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= LivedInfo::$status[$model->status] ?></p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= $model->remark ?></p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">创建时间</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= date('Y-m-d H:i:s', $model->created_at) ?></p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">更新时间</label>
            <div class="layui-input-inline">
                <p style="line-height:35px"><?= date('Y-m-d H:i:s', $model->updated_at) ?></p>
            </div>
        </div>
    </form>
</div>
</body>

