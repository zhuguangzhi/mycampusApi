<?php
$appid = 'wx56fbd7de36772ad9';
$bucket = "tencentyun";
$secret_id = 'AKIDmNhkW1EApEMEqBG4g5FH6f2AVdV7TZP0';
$secret_key = 'NUKvFJ5apnUt9E5i7IJ8lCPM4YmkSv98';
$expired = time() + 2592000;
$onceExpired = 0;
$current = time();
$rdm = rand();
$userid = "0";
$fileid = "tencentyunSignTest";
$srcStr = 'a='.$appid.'&b='.$bucket.'&k='.$secret_id.'&e='.$expired.'&t='.$current.'&r='.$rdm.'&f=';
$srcWithFile = 'a='.$appid.'&b='.$bucket.'&k='.$secret_id.'&e='.$expired.'&t='.$current.'&r='.$rdm.'&f='.$fileid;
$srcStrOnce= 'a='.$appid.'&b='.$bucket.'&k='.$secret_id.'&e='.$onceExpired .'&t='.$current.'&r='.$rdm
    .'&f='.$fileid;
$signStr = base64_encode(hash_hmac('SHA1', $srcStr, $secret_key, true).$srcStr);
$srcWithFile = base64_encode(hash_hmac('SHA1', $srcWithFile , $secret_key, true).$srcWithFile );
$signStrOnce = base64_encode(hash_hmac('SHA1',$srcStrOnce,$secret_key, true).$srcStrOnce);
echo $signStr."\n";
echo $srcWithFile ."\n";
echo $signStrOnce."\n";
?>
