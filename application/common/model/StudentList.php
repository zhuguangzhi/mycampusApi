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
    public function login($check=null){
        $params = request()->param();
        $userId = $params['userId'];
        $password = toMd5($params['password']);
        $res = $this->where(['s_id'=>$userId , 'password'=>$password])->find();
        if (!$res) TApiException('账号密码错误');
        if ($check!=null){
            return true;
        }

        $studentInfo=$this->getStudent($userId);
        //        去设备表查询是否绑定过id
        $openInfo = model('BindId')->getBindNum();
        if(empty($openInfo)){
            $studentInfo['bindInfo']=false;
        }else {
            $studentInfo['bindInfo']=$openInfo;
        }
        //        缓存token
        setCache('userInfo',$studentInfo,0);

        return $studentInfo;
    }
    // 获取学生信息
    public function getStudent($studentId){
        $res=$this->where('s_id',$studentId)->with([
            'ClassList'=>function($query){
                return $query->field('id,name,coures_id,d_id,teacher_id');
            },
        ])->field('id,class_id,name,sex,mobile')->find();
        //        查找所在系
        $DepartId = $res['class_list']['d_id'];
        $DepartInfo = Model('DepartList')->getDepartInfo($DepartId);
//        查找班主任信息
        $headmasterId = $res['class_list']['teacher_id'];
        $headmasterInfo =Model('TeacherList')->getTeacherInfo($headmasterId);

        $res['class_list']['headmaster']=$headmasterInfo;
        $res['class_list']['depart'] = $DepartInfo;
//        删除多余字段
        unset($res['class_id'], $res['class_list']['d_id'],$res['class_list']['teacher_id']);
        $res['userId']=$studentId;
//        生成token
        $token = createToken();
        $res['token'] = $token;
        $res['isTeacher'] = false;
        return $res;
    }

//    修改密码
    public function revisePassword(){
        $params = request()->param();
        $password = toMd5($params['password']);
        $this->where('s_id',$params['userId'])->update([
            'password'=>$password
        ]);
        return true;
    }

}
