<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\leaveValidate;
use app\common\model\LeaveList as LeaveModel;

class Leave extends BaseController
{
    public function leave(){
        (new leaveValidate())->goCheck('leaveForm');
        (new LeaveModel())->leave();
        return self::showResCodeWithOutData('申请成功');
    }
//    获取请假列表
    public function getLeaveList(){
        (new leaveValidate())->goCheck('page');
        $data = (new LeaveModel())->getLeaveList();
        return self::showResCode('获取成功',$data);
    }
//    获取请假信息
    public function getLeaveInfo(){
        (new leaveValidate())->goCheck('leaveId');
        return self::showResCode(
            "获取成功",
            (new LeaveModel())->getLeaveInfo()
        );
    }
}
