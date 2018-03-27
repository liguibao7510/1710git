<?php
namespace app\userrelated\controller;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// 测试类
use think\Db;
use OSS\OssClient;
use OSS\Core\OssException;
use app\commonuse\controller\Redis;
require VENDOR_PATH.'aliyun_oss/autoload.php';
class Test{
    
    public function test1(){
        try {
            $redis = new Redis();
            $oss_conf_info = $redis->hashget(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['system_config'], 'alioss');
            dump($oss_conf_info);
            $oss_conf_info = json_decode($oss_conf_info, true);
            dump($oss_conf_info);
            $ossClient = new OssClient($oss_conf_info['OSS_ACCESS_ID'], $oss_conf_info['OSS_ACCESS_KEY'], $oss_conf_info['OSS_ENDPOINT']);
//            dump($ossClient->getBucketCors('article-file'));
            $this->putObject($ossClient, 'article-file');
        } catch (OssException $e) {
            dump(666);
            print $e->getMessage();
        }
    }
    
    function putObject($ossClient, $bucket)
    {
        $object = "aaa.jpg";
        $content = file_get_contents(__FILE__);
        try{
            $r = $ossClient->putObject($bucket, $object, $content);
            dump($r);
        } catch(OssException $e) {
            dump(888);
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }
    
    
}