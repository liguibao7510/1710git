<?php
namespace app\index\controller;


use app\commonuse\controller\Allcontroller;
class Index extends Allcontroller{


    //后台首页
    public function index(){
        $datas = (new \app\systemconfig\model\Leftmenu())->get_data_list();
        if( $datas['res_code']==500 ){
            dump($datas);exit;
        }
        $cookie = json_decode(cookie(config('cookie')['extcookie']['adminer_info']), true);
        $user = (new \app\commonuse\controller\Redis())->hashgetall(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['adminer_info'].$cookie['uid']);
        unset($user['token']);
        // print_r($datas['datas']);
        return view('',['vars'=>$datas['datas'], 'userinfo'=>$user]);
    }

    //后台控制台
    public function controller(){
        return view();  //模板
    }
}

