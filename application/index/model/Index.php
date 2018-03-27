<?php
namespace app\index\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：后台首页管理Model类
 */
use think\Db;
class Index{
    private $db;
    private $redis;
    
    
    /*
     * 增
     */
        


    /*
     * 删
     */
         
    
    
    /*
     * 改
     */
        
        
        
    
    /*
     * 查
     */
        
        


        /*
     * 公用代码区***************************************
     */
        //获取redis
        private function get_redis(){
            $this->redis =new \app\commonuse\controller\Redis();
        }
        
}