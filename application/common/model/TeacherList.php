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
    public function login($check=null){
        $params = request()->param();
        $userId = $params['userId'];
        $password = toMd5($params['password']);
        $res = $this->where(['t_id'=>$userId , 'password'=>$password])->find();
        if (!$res) TApiException('账号密码错误');
        if ($check!=null){
            return true;
        }
        $teacherInfo=$this->getTeacher($userId);
//        去设备表查询openId绑定的相关信息
        $openInfo = model('BindId')->getBindNum();
        if(empty($openInfo)){
            $teacherInfo['bindInfo']=false;
        }else {
            $teacherInfo['bindInfo']=$openInfo;
        }
        setCache($userId,$teacherInfo,0);
        return $teacherInfo;
    }
//    查找教师基本信息
    public function getTeacherInfo($t_id){
        return $this->where('id',$t_id)->field('id,name,mobile')->find();
    }
    // 获取教师详细信息
    public function getTeacher($teacherId){
        $res = $this->where(['t_id'=>$teacherId ])->with([
            'DepartList'=>function($query){
                return $query->field('id,d_name,t_id');
            }
        ])->field('id,d_id,name,sex,mobile,rank')->find();

//        查找系主任信息
        $headmasterId = $res['depart_list']['t_id'];
        $headmasterInfo = $this->getTeacherInfo($headmasterId);
        $res['depart_list']['secretary']=$headmasterInfo;
        unset($res['d_id'], $res['depart_list']['t_id']);
        $res['userId']=$teacherId;
//        生成token
        $token = createToken();
        $res['token'] = $token;
        $res['isTeacher'] = true;

        return $res;
    }

//    修改密码
    public function revisePassword(){
        $params = request()->param();
        $password = toMd5($params['password']);
        $this->where('t_id',$params['userId'])->update([
            'password'=>$password
        ]);
    }
}
