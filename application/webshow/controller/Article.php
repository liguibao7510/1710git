<?php
namespace app\webshow\controller;

/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：前台文章控制器
 */
use think\Request;
use app\commonuse\controller\Showcontroller;
class Article extends Showcontroller{
    function __construct(Request $request) {
        parent::__construct($request);
    }
    
    //文章列表
    public function articlelist(Request $request){
        return $this->views($request);
    }
    
    //文章详情页
    public function artdetail(Request $request){
        return $this->views($request);
    }
    
    //关于我们
    public function aboutus(Request $request){
        return $this->views($request);
    }


    /*
     * 共享代码区
     */
        //二次封装模板返回
        private function views($request){
            return view($this->tplpath.$request->action(),['currenttpl'=>$this->currenttpl]);
        }
}
