<?php

namespace app\common\validate;

class leaveValidate extends BaseValidate
{
    protected $rule =[
        'userId'       => 'require',
        'leave_type'    => 'require',
        'leave_reason'  => 'require',
        'student_mobile'       => 'require|mobile',
        'parent_mobile' => 'require|mobile',
        'startTime'    => 'require|number',
        'endTime'      => 'require|number',
        'to_place'        => 'require',
        'leave_time'   => 'require',
        'page'         => 'require|integer',
        'leaveId'      => 'require|integer'
    ];

    protected $message=[
        'mobile.require' => '请填写联系方式',
        'mobile.mobile' => '请填写正确的联系方式',
        'parentMobile.require' => '请填写联系方式',
        'parentMobile.mobile' => '请填写正确的联系方式',
        'leaveReason.require' => '请填写请假原因',
        'startTime.require' => '请填写请假时间',
        'endTime.require' => '请填写请假时间',
        'place.require' => '请填写目的地',
//        'password' => '请填写密码',
    ];

    protected $scene=[
        'leaveForm' => [
            'leave_type',
            'leave_reason',
            'student_mobile',
            'parent_mobile',
            'startTime',
            'endTime',
            'to_place'   ,
            'leave_time'
        ],
        'page' => ['page'],
        'leaveId' => ['leaveId'],
    ];
}
