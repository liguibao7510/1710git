<?php
namespace app\commonuse\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：总控制器的model
 */
class Allmodel {
    private $redis;

    //构造函数
    function __construct() {
        $this->redis = new \app\commonuse\controller\Redis();
    }

    //查找内存缓存中是否存在对应的token
    public function check_exists_token($uid, $role_id, $username, $token){
        try{
            $adminer_info = $this->redis->hashgetall( config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['adminer_info'].$uid );
            if( count($adminer_info) > 0 ){
                //存在缓存信息
                if( $token == $adminer_info['token'] ){
                    //token校验成功
                    //更新token
                    $new_token = make_token($username);
                    if( !($this->redis->sethash(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['adminer_info'].$uid, 'token',
                    $new_token, config('ADMIN_LOGIN_INVALID_TIME'))) ){
                        //更新token失败
                        return response_tpl(400, '登陆失败:缓存错误001');
                    }

                    //将用户名和token缓存到本地cookie
                    cookie(config('cookie')['extcookie']['adminer_info'], json_encode([
                        'uid'  =>  $uid,
                        'rid'  =>  $role_id,
                        'username'  =>  $username,
                        'token'     =>  $new_token
                    ]), 0);

                    return response_tpl(200, '登陆成功');
                }
                //token校验失败
                return response_tpl(300, '账号已经在其它地方登陆');
            }
            //不存在缓存信息
            return response_tpl(300, '身份信息已过期，请重新登陆');
        } catch (\think\exception\ErrorException $e) {
            return return_exception( $e);//系统异常
        }
    }

    //校验权限
    public function auth_check($uid, $rid, $auth_str){
        $auth_datas = $this->redis->set_smembers(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['role_auth'].$rid); //角色权限(redis缓存)
        if( count($auth_datas) > 0 ){
            if( in_array($auth_str, $auth_datas, true) ){
                return response_tpl(200, '权限校验通过');
            }
        }

        $auth_datas = $this->redis->set_smembers(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['adminer_auth'].$uid); //用户权限(redis缓存)
        if( count($auth_datas) > 0 ){
            if( in_array($auth_str, $auth_datas, true) ){
                return response_tpl(200, '权限校验通过');
            }
        }
        return response_tpl(404, '无权限');
    }
}

