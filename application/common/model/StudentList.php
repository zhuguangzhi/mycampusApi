<?php
namespace app\common\model;

use think\Db;
use think\Model;
class StudentList extends Model{
    //        自动写入时间
    protected $autoWriteTimestamp =true;
//    关联教室表
    public function ClassList ()
    {
        return $this->belongsTo('ClassList','class_id','id');
    }
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
        $res = $this->where(['s_id'=>$userId , 'password'=>$password])->find();
        if (!$res) TApiException('账号密码错误');
        $studentInfo=getStudent($userId);
        if ((int)$studentInfo['is_login']===0){
            $this->where(['s_id'=>$userId ])->update(['is_login'=>1]);
        }
        return $studentInfo;
    }
}
