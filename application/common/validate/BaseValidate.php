<?php

namespace app\common\validate;

use app\lib\exception\BaseException;
use think\Cache;
use think\Validate;

//基本验证方法
class BaseValidate extends Validate
{
//    场景的默认值为空
    /**
     * @throws BaseException
     */
    public function goCheck($scene = '')
    {
        //获取请求头中的参数
        $params = request()->param();
//        判断是否使用场景场景
//        使用check对参数进行验证
        $check = empty($scene)
            ? $this->check($params)
            : $this->scene($scene)->check($params);
        if (!$check) {
//            抛出异常
            TApiException($this->getError(),400,1000);
        }
        return true;
    }
//    验证验证码
//  $value获取用户输入的验证码
//  $rule 规则名
//  $data 获取用户在该场景下所有的参数
//  $field 当前引用该规则的字段名
    public function isPerfectCode($value, $data = '')
    {
        $smsCode = Cache($data['mobile']);
//        判断验证码是否失效
        if (!$smsCode) return "请重新获取验证码";
//        验证验证码
        if ($value != $smsCode) return '验证码错误';
        return true;
    }

}
