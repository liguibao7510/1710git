<?php
namespace app\webshow\controller;


use think\Request;
use app\commonuse\controller\Showcontroller;
class Index extends Showcontroller{
    function __construct(Request $request) {
        parent::__construct($request);
    }

    //前台首页
    public function index(Request $request){
        // var_dump($this->tplpath.$request->action(),$this->currenttpl);
        return view($this->tplpath.$request->action(),['currenttpl'=>$this->currenttpl]);
    }
}

