<?php

namespace app\common\model;

use Carbon\Carbon;
use think\Model;

class SignList extends Model
{
    //        自动写入时间
    protected $autoWriteTimestamp = true;

    public function createSign()
    {
        $param = request()->param();
        $teacher_id = request()->param()['userInfo']['id'];
        $time = Carbon::now()->addMinute(20)->timestamp;
//        获取数据 判断该老师是否有未完成的签到任务
        $signListInfo = $this->where('teacher_id', $teacher_id)->order('id desc')->find();
//        获取当前时间
        $now = Carbon::now()->timestamp;
        if($signListInfo && $now < $signListInfo['invalid_time']){
            TApiException('当前签到还未结束哦！');
        }
//        插入数据并返回id
        $id = $this->insertGetId([
            'teacher_id' => $teacher_id,
            'class_id' => $param['class_id'],
            'course_id' => $param['course_id'],
            'invalid_time' => $time
        ]);
//        获取学生列表
        $studentsList = Model('StudentList')->where('class_id', $param['class_id'])->column('id');
//        获取请假列表
        $leaveList = Model('LeaveList')->where(['is_pass' => 1, 'is_leave_cancel' => 0])->column('student_id');
        $signList = [];
        foreach ($studentsList as $item) {
            if (in_array($item, $leaveList)) {
//                该学生请假了
                $signList[] = ['student_id' => $item, 'sign_id' => $id, 'leave' => 1];
            } else {
//                没有请假
                $signList[] = ['student_id' => $item, 'sign_id' => $id, 'leave' => 0];
            }
        }
//      在学生签到表中添加签到数据
        Model('StudentSignList')->insertAll($signList);
        return true;
    }
}