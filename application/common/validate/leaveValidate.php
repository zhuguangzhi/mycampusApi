<?php

namespace app\common\validate;

class leaveValidate extends BaseValidate
{
    protected $rule =[
        'userId'       => 'require',
        'leaveType'    => 'require',
        'leaveReason'  => 'require',
        'mobile'       => 'require|mobile',
        'parentMobile' => 'require|mobile',
        'startTime'    => 'require|number',
        'endTime'      => 'require|number',
        'place'        => 'require',
        'leaveHours'   => 'require'


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
            'leaveType',
            'leaveReason',
            'mobile',
            'parentMobile',
            'startTime',
            'endTime',
            'place'   ,
            'leaveHours'
        ]
    ];
}
