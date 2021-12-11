<?php

namespace app\lib\exception;

use Exception;
use think\exception\Handle;

class ExceptionHandler extends Handle
{
    public $code;
    public $msg;
    public $errorCode;

    public function render(Exception $e)
    {
        if ($e instanceof BaseException) {
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            //debug开启时显示全部错误信息
            if (config('app.app_debug')) return parent::render($e);
            $this->code = 500;
            $this->msg = '服务器异常';
            $this->errorCode = '999';
        }
        $res = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode
        ];
        return json($res, $this->code);
    }
}
