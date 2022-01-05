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

//LOGIN
Route::group('api/:version/', function () {
//    登录
    Route::post('login', 'api/:version.LoginCon/login');
    //    校验密码
    Route::post('checkPassword', 'api/:version.LoginCon/checkPassword');
    //    修改密码
    Route::post('revisePassword', 'api/:version.LoginCon/revisePassword');
    //    绑定openId
    Route::post('bindOpenId', 'api/:version.LoginCon/bindOpenId');
});
//SUBSCRIBE
Route::group('api/:version/', function () {
    Route::post('subscribeLeaveApply', 'api/:version.Subscribe/subscribeLeaveResult');
});
// FACE
Route::group('api/:version/', function () {
    //    人脸录入
    Route::post('face/createFace', 'api/:version.Face/createFace');
//    人脸核验
    Route::post('face/checkFace', 'api/:version.Face/checkFace');
});
//LEAVE
Route::group('api/:version/', function () {
    //    学生请假
    Route::post('leave', 'api/:version.Leave/leave');
    //    学生获取请假列表
    Route::get('getLeaveList', 'api/:version.Leave/getLeaveList');
    //    学生获取请假信息
    Route::get('getLeaveInfo', 'api/:version.Leave/getLeaveInfo');
    //    教师获取学生请假列表
    Route::get('getStudentLeaveList', 'api/:version.Leave/getStudentLeaveList');
    //    批假
    Route::post('approveLeave', 'api/:version.Leave/approveLeave');
})->middleware(['UserAuth']);
//COURSE
Route::group('api/:version/',function (){
    //    获取课程列表
    Route::get('getCourseList', 'api/:version.Course/getCourseList');
//    发布签到
    Route::post('createSign', 'api/:version.Course/createSign');
})->middleware(['UserAuth']);
//其他
Route::group('api/:version/', function () {
    //    token登录
    Route::post('tokenLogin', 'api/:version.LoginCon/tokenLogin');
})->middleware(['UserAuth']);
