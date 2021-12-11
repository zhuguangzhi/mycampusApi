<?php
namespace app\common\model;

use think\Model;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Iai\V20200303\IaiClient;
use TencentCloud\Iai\V20200303\Models\CreatePersonRequest;
use TencentCloud\Iai\V20200303\Models\VerifyFaceRequest;
use TencentCloud\Iai\V20200303\Models\DeletePersonRequest;

class Face extends Model
{
    //        自动写入时间
    protected $autoWriteTimestamp =true;
//    增加人脸
    public function createFace(){
         $user = request()->param();
         try {
             $client = $this->myFace();
             $req = new CreatePersonRequest();
             $params = array(
                 "GroupId" => "myCampus_98979695",
                 "PersonName" => "myCampus",
                 "PersonId" => $user['userId'],
                 "Image" => $user['image'],
                 "PersonExDescriptionInfos" => array(
                     array(
                         "PersonExDescription" => "mycampus"
                     )
                 ),
                 "UniquePersonControl" => 2,
                 'QualityControl' => 3
             );
             $req->fromJsonString(json_encode($params));
             $resp = $client->CreatePerson($req);
             if ($resp->SimilarPersonId) {
//                 有疑似人脸
                 TApiException('请联系管理员，您已有账号：'.$resp->SimilarPersonId , 400 , '1002');
             }
             if($this->where(['user_id'=>$user['userId']])->find()){
//                 更新
                 return $this->where('user_id',$user['userId'])->update([
                     'face_id' => $resp->FaceId,
                     'face_rect' => $resp->FaceRect,
                     'similar_person_id' => $resp->SimilarPersonId,
                     'request_id' =>$resp->RequestId
                 ]);
             }

             return $this->save([
                 'user_id'=>$user['userId'],
                 'face_id' => $resp->FaceId,
                 'face_rect' => $resp->FaceRect,
                 'similar_person_id' => $resp->SimilarPersonId,
                 'request_id' =>$resp->RequestId
             ]);
         }
         catch(TencentCloudSDKException $e) {
             $this->faceError($e);
         }
    }
//    人脸核对
    public function checkFace(){
        $user = request()->param();
        try {
            $client = $this->myFace();
            $req = new VerifyFaceRequest();
            $params = array(
                "PersonId" => $user['userId'],
                "Image" => $user['image'],
                'QualityControl' => 3
            );
            $req->fromJsonString(json_encode($params));

            $resp = $client->VerifyFace($req);
            if((bool)$resp->IsMatch) return $resp;
            TApiException('核验未通过',400,'1005');
        }
        catch(TencentCloudSDKException $e) {
            $this->faceError($e);
        }
    }
//    人脸删除
    public function delFace(){
        $user = request()->param();
        try {
            $client = $this->myFace();
            $req = new DeletePersonRequest();

            $params = array(
                "PersonId" => $user['userId']
            );
            $req->fromJsonString(json_encode($params));

            $resp = $client->DeletePerson($req);
            return $resp;
        }
        catch(TencentCloudSDKException $e) {
            $this->faceError($e);
        }
    }

    public function myFace(){
        $cred = new Credential(config('user.faceSecretId'), config('user.faceSecretKey'));
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("iai.tencentcloudapi.com");

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);
        return new IaiClient($cred, "ap-beijing", $clientProfile);
    }
//    错误处理
    public function faceError($e) {
        $e=(string)$e;
        $b = mb_strpos($e,'message:') + mb_strlen('message:');
        $a = mb_strpos($e,'requestId') - $b;
        $e= mb_substr($e,$b,$a);
        if ($e=='人员ID已经存在。人员ID不可重复。 ') TApiException($e,400,'1005');
        TApiException($e,400,'1003');
    }
}
