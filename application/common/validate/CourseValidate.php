<?php

namespace app\common\validate;

class CourseValidate extends BaseValidate
{
    protected $rule =[
        'class_id'=> 'require',
        'course_id' => 'require'
    ];
    protected $scene=[
        'createSign' => ['class_id','course_id']
    ];
}