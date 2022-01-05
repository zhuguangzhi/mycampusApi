<?php

namespace app\common\model;

use think\Model;

class SchoolYear extends Model
{
    public function getCurrentSchoolYear(){
        return $this->order('id desc')->find();
    }
}