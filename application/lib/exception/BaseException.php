<?php

namespace app\lib\exception;

use exception;

class BaseException extends exception
{
    public $code = 400;
    public $msg = '错误异常';
    public $errorCode = 10000;//错误代码


    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return;
        }
//        调用时若没传值，则用默认值
        array_key_exists('code', $params) ? $this->code = $params['code'] : null;
        array_key_exists('msg', $params) ? $this->msg = $params['msg'] : null;
        array_key_exists('errorCode', $params) ? $this->errorCode = $params['errorCode'] : null;
        parent::__construct();

    }
}
