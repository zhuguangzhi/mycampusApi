<?php

namespace app\common\model;

use think\Container;
use think\Loader;
use think\Model;
use Carbon\Carbon;
use app\api\controller\v1\Subscribe;

class LeaveList extends Model
{
    //        自动写入时间
    protected $autoWriteTimestamp =true;
//    关联学生表
    public function StudentList() {
        return $this->hasOne('StudentList','id','student_id');
    }
//    关联班级表
    public function ClassList ()
    {
        return $this->belongsTo('ClassList','class_id','id');
    }
//    关联教师表
    public function TeacherList() {
        return $this->hasOne('TeacherList','id','next_approval_id');
    }
//  学生

    //    请假
    public function leave (){
        $params = request()->param();
        $userId = $params['userInfo']['id'];
        $leave_time = (int)$params['leave_time'];
//        判断该学生是否还在请假中
        $leave = $this->where(['student_id'=>$userId,'is_pass'=>[1,2]])
            ->whereOr(['is_leave_cancel'=>0])->find();
        if ($leave){
            TApiException('请销假或等待审批通过');
        };
//        获取该学生的班主任
        $params['headmasterId'] = $params['userInfo']['class_list']['headmaster']['id'];
//        添加记录
        $this->save([
            'student_id'        =>  $userId,
            'leave_type'        =>  $params['leave_type'],
            'leave_reason'      =>  $params['leave_reason'],
            'student_mobile'    =>  $params['student_mobile'],
            'parent_mobile'     =>  $params['parent_mobile'],
            'startTime'         =>  $params['startTime'],
            'endTime'           =>  $params['endTime'],
            'to_place'          =>  $params['to_place'],
            'leave_time'        =>  $leave_time,
            'next_approval_id'  =>  $params['headmasterId']
        ]);
//        向教师发送请假审批提醒
        $Subscribe = new Subscribe();
        $Subscribe->subscribeLeaveApply($params);
        return true;
    }
//    获取请假列表
    public function getLeaveList()
    {
        $userId = request()->userInfo['id'];
        $res = $this->where('student_id',$userId)
            ->order('id desc')
            ->paginate(10);
        //        格式化时间
        foreach ($res as $item){
            $item['startTime'] = date('y/m/d/H:i', $item['startTime']/1000);
            $item['endTime'] = date('y/m/d/H:i', $item['endTime']/1000);
        };
        return $res;
    }
//    获取某个请假详情
    public function getLeaveInfo(){
        $leaveId = request()->param()['leaveId'];
        $res = $this->where('id',$leaveId)
            ->with([
                'TeacherList'=>function($query){
                    $query->field('id,name')->find();
                }
            ])
            ->find();
        $res['startTime'] = date('y/m/d/H:i', $res['startTime']/1000);
        $res['endTime'] = date('y/m/d/H:i', $res['endTime']/1000);
        return $res;
    }

//    教师

//    获取学生请假详情
    public function getStudentLeaveApply(){
        $page = request()->param()['page'];
        $params = request()->param()['userInfo'];
//        TApiException($params);
        if ((int)$params['rank'] === 1){
            TApiException('对不起,您暂无权限查询');
        }
//        获取学生请假列表
        $list = $this
            ->where(['next_approval_id'=>$params['id'],'is_pass'=>2])
            ->with([
                'StudentList'=>function($query){
                    $query
                        ->with([
                            'ClassList'=>function($class){
                                $class->field('id,teacher_id,name')->find();
                            }
                        ])
                        ->field('id,s_id,name,class_id')
                        ->find();
                },
            ])
            ->order('id desc')
            ->paginate(10);
        //        格式化时间
        foreach ($list as $item){
            $item['startTime'] = date('y/m/d/H:i', $item['startTime']/1000);
            $item['endTime'] = date('y/m/d/H:i', $item['endTime']/1000);
        };
        return $list;
    }
//    批假
    public function approveLeave(){
        $params = request()->param();
        $userInfo = $params['userInfo'];
//        获取该请假信息
        $leaveInfo = $this->where('id',$params['leaveId'])->find();
//        判断请假所需等级
        $needRank = $this->getNeedRank($leaveInfo['leave_time']);
        $rank = (int)$userInfo['rank'] - 1;
        $isPass = 2;
        $nextRankId = -1;
//            判断是否需要上级权限
//        if ($rank===2 && $leaveInfo['approval_id_one']!==1)
//            TApiException("下级暂未同意批假");
//        elseif ($rank===3 && $leaveInfo['approval_id_one']!==1 && $leaveInfo['approval_id_two']!==1)
//            TApiException("下级暂未同意批假");
        $params['approveResult']=(int)$params['approveResult'];
//        同意请假
        if ($params['approveResult']===1){
            if($needRank === $rank){
//                达到所需最高权限
                $isPass = 1;
            }else {
//                获取下级权限
                if ($rank === 2){
//                    需要管理员授权
//                    获取管理员id
                    $id  = $this->model('TeacherList')->where('rank',4)->field('id')->find()['id'];
                    $nextRankId = $id;
                }else if($rank === 1) {
//                    需要系主任授权
                    $nextRankId = $userInfo['depart_list']['secretary']['id'];
                }
            }
        }else{
            $isPass = 0;
        }
        $refuse_reason = $leaveInfo['refuse_reason'];
//        审批备注
        if ($refuse_reason)
            $refuse_reason .= $userInfo['name'].":".$params['marks'];
        $arr=['approval_id_one','approval_id_two','approval_id_three'];
//        提交
        $this->where('id',$params['leaveId'])->update([
            'is_pass' =>    $isPass,
            $arr[$rank-1] => $params['approveResult'],
            'next_approval_id' => $nextRankId,
            'refuse_reason' => $refuse_reason
        ]);
//        请假审批完成，发送提醒
        if($nextRankId === -1) {
           $subscribe = new Subscribe();
           $query = [
               'result' => $isPass===1?'同意':'拒绝',
               'applyStudentId' =>$leaveInfo['student_id'],
               'leaveType' => $leaveInfo['leave_type'],
               'refuse_reason' => $refuse_reason
           ];
            $subscribe->subscribeLeaveResult($query);
        }
        return true;

    }

//    方法
//获取所需批假等级
    protected function getNeedRank($leave_time){
        //        1天以内班主任批假 一周以内系主任批假 1周以上管理员批假
        $need_power = 1;
        $leave_time=(int)$leave_time;
        if ($leave_time > 24 && $leave_time<=24*7){
            $need_power = 2;
        }elseif ($leave_time > 24*7){
            $need_power = 3;
        }
        return $need_power;
    }
}
