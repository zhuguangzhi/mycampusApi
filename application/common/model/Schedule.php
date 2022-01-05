<?php

namespace app\common\model;

use think\Model;

class Schedule extends Model
{
    public function getSchedule()
    {
        $schoolYearInfo = model('SchoolYear')->getCurrentSchoolYear();
        return $this->where('id',$schoolYearInfo['current_schedule_id'])->find();
    }
}