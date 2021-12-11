<?php

namespace app\common\controller;

use think\Controller;
use think\response\Json;

class BaseController extends Controller
{
    /**
     * api返回无数据的结果
     *
     * @param string $msg
     * @param integer $code
     * @return Json
     */
    static public function showResCodeWithOutData($msg = '未定义消息', $code = 200)
    {
        return self::showResCode($msg, [], $code);
    }

    /**
     * api的返回结果
     * @param string $msg 提示信息
     * @param array $data 数据
     * @param integer $code 状态码
     * @return Json
     */
    static public function showResCode($msg = "未定义消息", $data = [], $code = 200)
    {
        $res = [
            "msg" => $msg,
            "data" => $data
        ];
        return json($res, $code);
    }
}
