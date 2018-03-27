<?php
namespace app\commonuse\controller;
/*
 * 测试redis功能
 */

use app\commonuse\controller\Redis;
use think\Request;
class Testredis {
    private $redis;
    function __construct() {
        $this->redis = new Redis();
    }
    
    //探查设置一个key返回值
    public function set_key(Request $request){
//        dump($request->module());exit;
//        $result = $this->redis->set_smembers('auth_test005');
//        dump($result);
        $str = '[1512489600000,1512541499000]';
        dump(json_decode(htmlspecialchars_decode($str),true));
    }
    
    
}

