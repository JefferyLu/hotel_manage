<?php
/**
 * Yii2-Admin
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    wangyaxian <1822581649@qq.com>
 * @link      https://github.com/duiying/Yii2-Admin
 */

namespace common\models;

/**
 * 格式化AJAX返回的数据
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 */
class MsgUtil
{
    /**
     * 成功状态码
     */
    const SUCCESS_CODE = '200';

    /**
     * 失败状态码
     */
    const FAIL_CODE = '201';

    const SUCCESS_MSG = '操作成功';
    const FAIL_MSG = '操作失败';
    const FAIL_VALIDATE = '数据格式校验失败';
    const NAME_OR_PASS_ERROR = '用户名或者密码错误';
    const PASS_ERROR = '原密码输入错误';
    const SAVE_FAIL = '数据保存失败';
    const UPDATE_FAIL = '数据更新失败';
    const CHILD_ERROR = '请先删除子级权限';
    const DEL_FAIL = '删除失败';
    const PARAM_ERROR = '参数错误';
    const ADMIN_NAME_EXIST = '用户名已存在';
    const SELF_DEL = '不允许删除当前登录用户';
    const ADMIN_DEL = '不允许删除admin用户';
    const ROLE_NAME_EXIST = '角色名已存在';
    const ADMIN_EDIT = '超级管理员不允许编辑';
    const ADMIN_ROLE_DEL = '超级管理员角色不允许删除';
    const ROLE_RELATE_ADMIN = '该角色下已绑定管理员';
    const HAVE_NO_AUTH = '没有权限';
    const NAME_OR_EMAIL_ERROR = '用户名或邮箱输入错误';
    const MAIL_SEND_SUCCESS = '邮件发送成功, 请去邮箱进行验证';
    const TOKEN_ERROR = 'Token验证失败';
    const LINK_EXPIRE = '链接已经过期';


    /**
     * 格式化AJAX返回的数据
     *
     * @param array $arr['状态码', '提示信息']
     * @return string
     */
    public static function dataFormat($arr = [])
    {
        return json_encode(['code' => $arr[0], 'msg' => $arr[1]]);
    }
    
    /**
     * 返回json数据
     * @param string $code 状态码
     * @param string $msg  信息
     */
    public static function response($code, $msg){
        return json_encode(['code' => $code, 'msg' => $msg]);
    }
    
    /**
     * 返回array格式数据  一般内部调用使用
     * @param string $code 状态码
     * @param string $msg  信息
     */
    public static function result($code, $msg){
        return ['code' => $code, 'msg' => $msg];
    }
}