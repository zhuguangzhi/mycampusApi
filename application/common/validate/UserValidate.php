<?php
    namespace app\common\validate;


    use think\validate;

    class UserValidate extends BaseValidate {
        protected $rule =[
            'userId' => 'require',
            'password' => 'require',
            'token' =>'require',
            'openId'=>'require',
        ];

        protected $message=[
          'userId.require' => '请填写登录账号',
          'password' => '请填写密码',
        ];

        protected $scene=[
            'UserLogin' => ['userId','password'],
            'checkToken'=> ['token'],
            'openId'=>['openId','userId'],
        ];
    }
