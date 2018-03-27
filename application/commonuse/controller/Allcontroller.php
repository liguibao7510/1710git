<?php
namespace app\commonuse\controller;
/*
 * 【功能】：后台总控制器
 */
use think\Request;
class Allcontroller {
    private $free_auth; //开放权限(完全开放)
    private $login_free_auth;   //开放权限(要求登陆)
    private $auth_str;
    //构造函数
    function __construct(Request $request) {
        $this->free_auth = [
            'userrelated-admins-login',
            'userrelated-admins-check_amdiner',
        ];
        $this->login_free_auth = [
            'index-index-index',
            'index-index-controller',
            'userrelated-admins-logout',
        ];


        $datas = $request->post();
        $this->auth_str = strtolower($request->module().'-'.$request->controller().'-'.$request->action());
        //如果有传入用户名和密码则默认为重新登陆
        if( !empty($datas['name']) && !empty($datas['passwd']) ){
            return $this->normal_db_check($datas);
        }else if( (empty($datas['name']) || empty($datas['passwd'])) && !in_array($this->auth_str, $this->free_auth, true) ){
            $cookie = json_decode(cookie(config('cookie')['extcookie']['adminer_info']), true);
            if( $cookie == null ){
                if( $request->isAjax(true) ){
                    echo json_encode(response_tpl(100, '您还未登陆'));exit;
                }
                $js=<<<eof
                   <script type="text/javascript">
                    window.top.location.href="/PSD1710/20_CMSstyle/public/backend/login";
                   </script>
eof;
                     echo $js;exit;
//                return response_tpl(404, '请输入用户名和密码');
            }

            $result = $this->cache_check($cookie);
            if( $result['res_code'] == 200 && !in_array($cookie['username'], config('SUPPER_ADMINER')) && !in_array($this->auth_str, $this->free_auth, true) && !in_array($this->auth_str, $this->login_free_auth, true) ){
                $auth_check = $this->auth_check($cookie['uid'], $cookie['rid'], $this->auth_str);
                if( $auth_check['res_code'] !== 200 ){
                    echo json_encode($auth_check);exit;
                    if( $request->isAjax(true) ){
                        echo json_encode($auth_check);exit;
                    }else{
                        $js=<<<eof
                        <script type="text/javascript">
                         window.location.href="/PSD1710/20_CMSstyle/public/common/error/auth";
                        </script>
eof;
                        echo $js;exit;
                    }
                }
            }
            if( $result['res_code'] != 200 ){
                if( $request->isAjax(true) ){
                    echo json_encode($result);exit;
                }
                $msg = $result['msg'];
                $js=<<<eof
                <script type="text/javascript">
                    var r=confirm("$msg");
                    if (r==true){
                        window.top.location.href="/PSD1710/20_CMSstyle/public/backend/login";
                    }else{
                        window.top.location.href="/PSD1710/20_CMSstyle/public/backend/login";
                    }
                </script>
eof;
                echo $js;exit;
            }
        }
    }


    //到缓存中校验
    protected function cache_check($cookie){
        $model = new \app\commonuse\model\Allmodel();
        return $model->check_exists_token($cookie['uid'], $cookie['rid'], $cookie['username'], $cookie['token']);
    }


    //到数据库校验
    private function normal_db_check($datas){
        $admins = new \app\userrelated\model\Admins();
        return $admins->select_check($datas['name'], $datas['passwd']);
    }

    //权限校验
    protected function auth_check($uid, $rid, $auth_str){
        $model = new \app\commonuse\model\Allmodel();
        return $model->auth_check($uid, $rid, $auth_str);
    }

    //禁止重复登陆
    protected function forbidden_login_again(){
        if( $this->auth_str === 'userrelated-admins-login' ){
            $js=<<<eof
                   <script type="text/javascript">
                    window.top.location.href="/PSD1710/20_CMSstyle/public/backend/index";
                   </script>
eof;
                    echo $js;exit;
        }
    }
}

