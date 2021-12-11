<?php

namespace app\common\model;

use think\Db;
use think\Model;

class TeacherList extends Model
{
    //        自动写入时间
    protected $autoWriteTimestamp = true;
    //    关联系别表
    public function DepartList ()
    {
        return $this->belongsTo('DepartList','d_id','id');
    }
//    登录
    public function login(){
        $params = request()->param();
        $userId = $params['userId'];
        $password = toMd5($params['password']);
        $res = $this->where(['t_id'=>$userId , 'password'=>$password])->find();
        if (!$res) TApiException('账号密码错误');
        $teacherInfo=getTeacher($userId);
        if ((int)$teacherInfo['is_login']===0){
            $this->where(['t_id'=>$userId ])->update(['is_login'=>1]);
        }
        return $teacherInfo;


    }
//    查找教师信息
    public function getTeacherInfo($t_id){
        return $this->where('id',$t_id)->field('id,name,mobile')->find();
    }
}
