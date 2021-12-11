<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\model\BindId as BindIdModel;
use app\common\model\StudentList as StudentModel;
use app\common\model\TeacherList as TeacherModel;
use app\common\validate\UserValidate;
use think\Request;

class Login extends BaseController
{
    public function login(Request $request)
    {
        (new UserValidate())->goCheck('UserLogin');
        $params = $request->param();

        if ($params['userId'][0] === "t"){
//            教师登录
            $data=(new TeacherModel)->login();
        }else{
//            学生登录
            $data = (new StudentModel)->login();
        }
        return self::showResCode('登录成功',$data);

    }
    public function checkToken(Request $request){

        (new UserValidate())->goCheck('checkToken');
        $CacheToken = getCache('userInfo');
        if ((string)$CacheToken['token']===(string)$request->param()['token']) {
            return self::showResCodeWithOutData('token验证成功');
        }
        TApiException('口令验证失败，请重新登录');
    }
//    token登录
    public function tokenLogin(Request $request){
//        中间件中已经验证token是否正确
        $userInfo = getCache('userInfo');
        $isTeacher = $userInfo['isTeacher'];
        if ($isTeacher){
            $res=getTeacher($userInfo['userId']);
        }else{
            $res = getStudent($userInfo['userId']);
        }
        return self::showResCode("登录成功",$res);
    }
//    绑定openId
    public function bindOpenId(){
        (new BindIdModel())->bindId();
        return self::showResCodeWithOutData('绑定成功',200);
    }
//    检查openId
    public function checkOpenId(){
        (new UserValidate())->goCheck('openId');
        $data=(new BindIdModel())->checkOpenId();
        return self::showResCode('校验',['checkResult'=>$data]);
    }
//    获取openId
    public function getOpenId(){
        $data = (new BindIdModel())->getOpenId();
        return self::showResCode('openId',['checkResult'=>$data]);
    }
//    获取OpenID相关信息
    public function getBindNum(){
        $data = (new BindIdModel())->getBindNum();
        return self::showResCode('openIdInfo',$data);
    }
}
