<?php
    return [
        'slot'      =>    'myCampus',
//        用户缓存名
        'cacheName'         =>    'currentUser',
        'appId'             =>    'wx56fbd7de36772ad9',
        'secret'            =>    '55a316e5b69c9a79c4664b58560f2abf',
        'faceSecretId'      =>    'AKIDmNhkW1EApEMEqBG4g5FH6f2AVdV7TZP0',
        'faceSecretKey'     =>    'NUKvFJ5apnUt9E5i7IJ8lCPM4YmkSv98',
//        请假审批模板id
        'subscribeApply'    =>    'u8iq6ta6gp4kvY2ZgavSXXRMpb15ldIZatY97Xn6zlY',
//        请假审批结果模板id
        'subscribeResult'   => 'ZbsfN9zKvTQ7xFTHT7wo0HTXMv0LteibQAGXJhln_Uo',
//        订阅请求
        'subscribeUrl'      =>     "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=%s",
//      获取access_token
        'accessTokenUrl'    =>      "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s"
    ];
