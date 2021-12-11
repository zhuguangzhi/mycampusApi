<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//无须验证token


use think\facade\Route;

Route::group('api/:version/', function () {
//    登录
    Route::post('login','api/:version.Login/login');
//    token登录
    Route::post('tokenLogin','api/:version.Login/tokenLogin');
    //发送验证码
//    Route::post('user/sendcode','api/:version.StudentList/sendCode');

    //    getopenId
    Route::post('getOpenId','api/:version.Login/getOpenId');
    //    绑定openId
    Route::post('bindOpenId','api/:version.Login/bindOpenId');

    //    人脸录入
    Route::post('face/createFace','api/:version.Face/createFace');
//    人脸核验
    Route::post('face/checkFace','api/:version.Face/checkFace');
// 获取openid相关信息
    Route::post('getBindNum','api/:version.Login/getBindNum');
});

Route::group('api/:version/', function () {
//    学生请假
    Route::post('leave','api/:version.Leave/leave');


})->middleware(['UserAuth']);
