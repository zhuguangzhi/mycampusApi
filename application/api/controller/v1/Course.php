<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\model\ClassScheduleCard as ScheduleCardModel;
use app\common\model\SignList as SignListModel;
use app\common\validate\CourseValidate;

class Course extends BaseController
{
//
    public function getCourseList(){
        $data = (new ScheduleCardModel())->getCourseList();
        return self::showResCode('获取成功',$data);
    }
//    发布签到
    public function createSign(){
        (new CourseValidate())->goCheck('createSign');
        (new SignListModel())->createSign();
        return self::showResCodeWithOutData('发布成功');
    }
}