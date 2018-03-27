<?php
namespace app\commonuse\controller;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：前台总控制器
 */
use think\Request;
class Showcontroller {
    protected $tpltype;    //模板类型(移动端为mvie/电脑端为view)
    protected $currenttpl;  //当前模板(默认为defaulttemplate)
    protected $tplpath; //模板路径
    //构造函数
    function __construct(Request $request) {
        if( $request->isMobile() ){
            $this->tpltype = 'mview';
        }else{
            $this->tpltype = 'view';
        }
        $this->currenttpl = 'defaulttemplate';  //动态配置当前模板
        config('template.view_path', '../template/'.$this->currenttpl.'/');
        $this->tplpath = strtolower($request->module()).'/'.$this->tpltype.'/'.strtolower($request->controller()).'/';
    }
}

