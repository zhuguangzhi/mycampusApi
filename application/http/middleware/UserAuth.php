<?php

namespace app\http\middleware;

use Closure;
use think\Cache;

class UserAuth
{
    /**
     * @throws \app\lib\exception\BaseException
     */
    public function handle($request, \Closure $next)
    {
        //获取头部信息
        $header = $request->header();
//        若没有token相关信息
        if (!array_key_exists('token', $header))
            TApiException('验证失败，请重新登录', 200, 40003);
//        获取用户传进的userId
        $userId = $request->param()['userId'];
//        获取当前用户token
        $user = getCache($userId);
//        验证失败（未登录或已过期）
        //        验证token
        if (!$user || $header['token']!==$user['token']) TApiException('验证失败，请重新登录', 200, 40003);

//        将用户信息存储在request中
        $request->userInfo = $user;
        return $next($request);
    }
}
