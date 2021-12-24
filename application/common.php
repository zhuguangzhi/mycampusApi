<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 异常类输出函数
use app\lib\exception\BaseException;
use think\facade\Cache;

/**
 * @throws BaseException
 */
function TApiException($msg = '', $code = 400, $errorCode = '999')
{
    throw new BaseException(['code' => $code, 'msg' => $msg, 'errorCode' => $errorCode]);
}
//获取文件完整url
function getFileUrl($url=''){
    if (!$url)return;
//    thinkPhp提供获取路由
    return url($url,'',false,true);
}
//md5加密
function toMd5($value){
    $slot = config('user.slot');
    return sha1(md5(md5($slot.$value,true),true));
}
function setCache($key,$value,$time=null){
    if (!$key and !$value) return false;
    Cache::set($key,$value,$time?$time:config('cache.expire'));
    return true;
}
function getCache($key){
    if (!$key) return false;
    return Cache::get($key);
}
//发起网络请求
function http_request($url,$data = null,$headers=array()){
    $curl = curl_init();
    if( count($headers) >= 1 ){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
function createToken(){
    return toMd5(uniqid(microtime(true)));
}



