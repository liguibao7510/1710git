<?php
namespace app\commonuse\controller;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：错误页面管理
 */

class Errorpage {
    
    //没有权限
    public function no_auth(){
        return view();
    }
    
}
