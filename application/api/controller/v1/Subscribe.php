<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use think\Model;
use think\Request;

class Subscribe extends BaseController
{
//    获取小程序全局唯一后台接口调用凭据
    public function getAccessToken(){
        $APPID = config('user.appId');
        $APPSECRET = config('user.secret');
        //将路径中占位符%s替换为$access_token值
        $url = sprintf(config('user.accessTokenUrl'), $APPID,$APPSECRET);
        $res = http_request($url);
        return json_decode($res);
    }
//    请假审批订阅提醒
    public function subscribeLeaveApply($params){
//        获取教师的userId
        $userId=\model('TeacherList')->where('id',$params['headmasterId'])->field('t_id')->find()['t_id'];
        $touser = \model('BindId')->where('userId',$userId)->field('openId')->find()['openId'];
        $access_token = $this->getAccessToken()->access_token;

        $tempId = config('user.subscribeApply');
        //将路径中占位符%s替换为$access_token值
        $url = sprintf(config('user.subscribeUrl'), $access_token);
        $send_data = [
            'touser'   =>  $touser,
            'page'     => 'pages/Leave/Mine/searchLeaveApply',
            'template_id' =>  $tempId,
            'miniprogramState' => 'developer',
            'data'  =>  [
                'thing1' => [
                    'value' => '请假审批'
                ],
                'thing2'  => [
                    'value' => $params['leave_type']
                ],
                'thing3'  =>  [
                    'value' => $params['leave_reason']
                ]
            ]
        ];
        $res = json(curl_post($url,$send_data));
        return $res;
    }
//    请假结果订阅
    public function subscribeLeaveResult(){
        $params=[
            'applyStudentId' => '1',
            'leaveType' => '病假',
            'result' => '同意',
            'refuse_reason' => '可以可以'
        ];
//        获取学生的userId
        $userInfo = \model('StudentList')->where('id',$params['applyStudentId'])->field('s_id,name')->find();
//        获取学生的openId
        $touser = \model('BindId')->where('userId',$userInfo['s_id'])->field('openId')->find()['openId'];
        $access_token = $this->getAccessToken()->access_token;

        $tempId = config('user.subscribeResult');
        //将路径中占位符%s替换为$access_token值
        $url = sprintf(config('user.subscribeUrl'), $access_token);
        $send_data = [
            'touser'   =>  $touser,
            'template_id' =>  $tempId,
            'miniprogramState' => 'developer',
            'data'  =>  [
                'name4' => [
                    'value' => $userInfo['name']
                ],
                'thing9'  => [
                    'value' => $params['leaveType']
                ],
                'phrase1'  =>  [
                    'value' => $params['result']
                ],
                'thing3' => [
                    'value' => $params['refuse_reason']
                ]
            ]
        ];
        return json(curl_post($url,$send_data));
    }
}