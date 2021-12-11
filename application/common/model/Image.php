<?php

namespace app\common\model;

use think\Model;

class Image extends Model
{
    //    上传多图
    public function uploadMore(){
        $imgList=array('.png', '.jpg', '.jpeg', '.bmp', '.gif');
        $image = $this->upload(request()->userInfo['id'],'contentPicList');
        if(isset(request()->param()['pathName'])) return $image;
        $imageCount = count($image);
        for ($i=0;$i<$imageCount;$i++){
            $image[$i]['url'] = getFileUrl($image[$i]['url']);
            $str = substr($image[$i]['url'],strrpos($image[$i]['url'],'.'));
            if (in_array($str,$imgList)){
//                是图片
                $image[$i]->is_video=0;
            }else{
                $image[$i]->is_video=1;
            }
        }
        return $image;
    }
    //    封装图片上传事件
    public function upload($field='',$path='image'){
        $userId = getCache('userInfo')['id'];
//        获取图片
        $clock=isset(request()->param()['pathName']);
        if($clock){
            $path=request()->param()['pathName'];
        }
        $files = request()->file($field);
        $arr = [];
        foreach($files as $file){
            $res = \app\common\controller\FileController::uploadEvent($file,$path);
            if($res['status']){
                $arr [] = [
                    'url'=>$res['data'],
                    'user_id'=>$userId
                ];
            }
        }
        if($clock){
            return $arr;
        }
        return $this->saveAll($arr);
    }
}
