<?php

namespace app\common\model;

use think\Model;

class LeaveList extends Model
{
    //        自动写入时间
    protected $autoWriteTimestamp =true;
    //    请假
    public function leave (){
        $params = request()->param();
        $userId = $params['userInfo']['id'];
//        判断该学生是否还在请假中
        $leave = $this->where(['student_id'=>$userId,'is_pass'=>[1,2]])
            ->whereOr(['is_leave_cancel'=>0])->find();
        if ($leave){
            TApiException('请销假或等待审批通过');
        }
//        添加记录
        $this->save([
            'student_id'=>  $userId,
            'leave_type'=> $params['leaveType'],
            'leave_reason'=>$params['leaveReason'],
            'student_mobile'=>$params['mobile'],
            'parent_mobile'=>$params['parentMobile'],
            'startTime'=>$params['startTime'],
            'endTime'=>$params['endTime'],
            'to_place'=>$params['place'],
            'leave_time'=>$params['leaveHours']
        ]);
        return true;
    }
}
