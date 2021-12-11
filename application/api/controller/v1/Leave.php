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
}
