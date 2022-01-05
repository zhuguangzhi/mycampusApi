<?php

namespace app\common\model;

use think\Model;

class ClassScheduleCard extends Model
{
//    关联教室表
    public function ClassList ()
    {
        return $this->hasOne('ClassList','id','class_id');
    }
//  关联课程表
    public function CourseList ()
    {
        return $this->hasOne('CourseList','id','course_id');
    }
//    查找所带课程
    public function getCourseList(){
        $userId = request()->param()['userInfo']['id'];
//        获取当前学年id
        $schoolYear = model('SchoolYear')->getCurrentSchoolYear();
        $res = $this
            ->where(['teacher_id'=>$userId,'school_year_id'=>$schoolYear['id']])
            ->distinct('class_id')
            ->with([
                'ClassList'=>function($query){
                    $query->field('id,abbreviation');
                },
                'CourseList'=>function($query){
                    $query->field('id,course_name');
                }
            ])
            ->field('class_id,course_id')
            ->select();
        foreach ($res as $item){
            $item['className'] = $item['class_list']['abbreviation'];
            $item['courseName'] = $item['course_list']['course_name'];
            unset($item['class_list'],$item['course_list']);
        }
        return $res;
    }
}