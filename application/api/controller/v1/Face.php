<?php
namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\model\Face as FaceModel;
use think\Request;

class Face extends BaseController
{
//    增加人脸
    public function createFace(){
        (new FaceModel())->createFace();
        return self::showResCodeWithOutData('录入成功');
    }
//    人脸核验
    public function checkFace(){
        (new FaceModel())->checkFace();
        return self::showResCodeWithOutData('核验结果');
    }
//    人脸删除
    public function delFace(){
        (new FaceModel())->delFace();
        return self::showResCodeWithOutData('删除成功');
    }
}
