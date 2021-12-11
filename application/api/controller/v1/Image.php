<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\model\Image as ImageModel;

class Image extends BaseController
{
    public function Upload(){
        (new ImageModel())->upload();
    }
}
