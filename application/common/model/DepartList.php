<?php

namespace app\common\model;

use think\Model;

class DepartList extends Model
{
//    获取系信息
    public function getDepartInfo($d_id){
        return $this->where('id',$d_id)->field('id,d_name,t_id')->find();
    }
}
