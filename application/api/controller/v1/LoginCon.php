<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\model\BindId;
use app\common\model\BindId as BindIdModel;
use app\common\model\StudentList as StudentModel;
use app\common\model\TeacherList as TeacherModel;
use app\common\validate\UserValidate;
use think\Model;
use think\Request;

class LoginCon extends BaseController
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
        $info = (new BindIdModel())->checkOpenId();
        $data['openId']=$info;
//        校验OpenId
        return self::showResCode('登录成功',$data);

    }
//    token登录
    public function tokenLogin(Request $request){
//        中间件中已经验证token是否正确
        $userId = $request->param()['userId'];
        $isTeacher = getCache($userId)['isTeacher'];
        if ($isTeacher){
            $res=\model('TeacherList')->getTeacher($userId);
        }else{
            $res =\model('StudentList')->getStudent($userId);
        }
        setCache($userId,$res,0);
        return self::showResCode("登录成功",$res);
    }
//    绑定openId
    public function bindOpenId(){
        (new BindIdModel())->bindId();
        return self::showResCodeWithOutData('绑定成功',200);
    }
//校验密码
    public function checkPassword(Request $request){
        $params = $request->param();
        if ($params['userId'][0] === "t"){
//            教师验证
            (new TeacherModel)->login(true);
        }else{
//            学生验证
            (new StudentModel)->login(true);
        }
        return self::showResCode('校验成功',true);
    }
//    修改密码
    public function revisePassword(Request $request){
        $params = $request->param();

        if ($params['userId'][0] === "t"){
//            教师验证
            (new TeacherModel)->revisePassword();
        }else{
//            学生验证
            (new StudentModel)->revisePassword();
        }
        return self::showResCode('修改成功',true);
    }
}
