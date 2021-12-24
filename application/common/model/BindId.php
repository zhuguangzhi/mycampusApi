<?php
namespace app\common\model;

use Carbon\Carbon;
use think\Db;
use think\Model;
class BindId extends Model {
    //        自动写入时间
    protected $autoWriteTimestamp =true;
//    更新openId
    public function bindId (){
//        获取次数
        $res = $this->getBindNum();
//        判断用户是否绑定过
        if(empty($res)){
            $num = 4;
        }else {
            $num = (int)$res['bindNum'];
            $time = Carbon::parse($res['deadline'])->timestamp;
            //        判断时间是否到了
            if ($res['time']>=$time){
                $num = 3;
            }
            if ($num < 1){
                TApiException('本年内修改次数已达上限',400,'1004');
            }
        }
        $num -= 1;
        $today = Carbon::today()->timestamp;
        $userId = request()->param()['userId'];
        $openInfo = getCache('openInfo');
        $this->where('userId',$userId)->update([
            "openId" => $openInfo['openId'],
            "model" => $openInfo['model'],
            "time" => $num,
            "record_time" => $today
        ]);
        return true;
    }
//    获取绑定次数
    public function getBindNum() {
        $userId = request()->param()['userId'];
        $res = $this->where('userId',$userId)->find();
        if (empty($res)) return null;
        $deadline  = Carbon::createFromTimestamp($res->record_time)->addYear(1)->toArray();
        $date  = $deadline['year'].'-'.$deadline['month'].'-'.$deadline['day'];
        return(['deadline'=>$date,'bindNum'=>$res->time,'time'=>$res->record_time]);
    }

    //   检验openId
    public function checkOpenId(){
        $params = request()->param();
        $option = [
            'appid'      =>  config('user.appId'),
            'secret'     =>  config('user.secret'),
            'js_code'    =>  $params['code'],
            'grant_type' =>  'authorization_code'
        ];
        $res = json_decode(http_request('https://api.weixin.qq.com/sns/jscode2session',$option));
        $openId = toMd5($res->openid);
        $info = $this->where('userId',$params['userId'])->field('openId,model')->find();

        if ($info['openId'] === $openId &&  $info['model'] === $params['systemId']){
//            通過
            return true;
        }

        $params = [
            "openId" => $openId,
            "model" => $params['systemId']
        ];
//        将前端传入的值存储
        setCache('openInfo',$params,60000);
        return false;
    }
}
