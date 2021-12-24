<?php

namespace app\common\model;

use think\Container;
use think\Model;
use Carbon\Carbon;

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
            'leave_type'=> $params['leave_type'],
            'leave_reason'=>$params['leave_reason'],
            'student_mobile'=>$params['student_mobile'],
            'parent_mobile'=>$params['parent_mobile'],
            'startTime'=>$params['startTime'],
            'endTime'=>$params['endTime'],
            'to_place'=>$params['to_place'],
            'leave_time'=>$params['leave_time']
        ]);
        return true;
    }
//    获取请假列表
    public function getLeaveList()
    {
        $userId = request()->userInfo['id'];
        $res = $this->where('student_id',$userId)
            ->order('id desc')
            ->paginate(10);
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
            ->find();
        $res['startTime'] = date('y/m/d/H:i', $res['startTime']/1000);
        $res['endTime'] = date('y/m/d/H:i', $res['endTime']/1000);
        return $res;
    }
}
