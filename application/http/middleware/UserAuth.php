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
        $params = $request->header();
//        若没有token相关信息
        if (!array_key_exists('token', $params))
            TApiException('验证失败，请重新登录', 200, 20003);

//        当前用户是否登录
        $user = getCache('userInfo');

//        验证失败（未登录或已过期）
        if (!$user) TApiException('验证失败，请重新登录', 200, 20003);
//        验证token
        if($params['token']!==$user['token'])
            TApiException('验证失败，请重新登录', 200, 20003);
//        将用户信息存储在request中
        $request->userInfo = $user;
        return $next($request);
    }
}
