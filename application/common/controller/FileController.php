<?php

namespace app\common\controller;

use think\Controller;

class FileController extends Controller
{
    static public function uploadEvent($files,$path){
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $files->move('static/'.$path);
        return [
            'data'=>$info ? $info->getPathname() : $files->getError(),
            'status'=> (bool)$info
        ];
    }
}

