<?php
namespace app\userrelated\controller;


use app\commonuse\controller\Allcontroller;
use think\Request;
use app\userrelated\validate\AdminsValidate;    //本类验证器
class Admins extends Allcontroller{
    private $model; //用户初始化model对象的变量

    /*
     * 增
     */
        public function add( Request $request, AdminsValidate $adminsvalidate ){
            try{
                $datas = $request->post();  //获取请求数据

                //允许提交表单校验
                $allow_fields = ['name','passwd','role','department','nike_name',
                    'header_img','ture_name','phone','email','qq','wx','sex','age',
                    'country','province','city','area','county','town','village','addr',
                    'zjno','zjimg','description','check'];
                $datas = allow_request_field($datas, $allow_fields);
                if( $datas === false ){
                    return response_tpl(2, '请求参数过多');
                }

                if( !$adminsvalidate->scene('add')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $adminsvalidate->getError());
                }
                $this->getmodel();  //获取model实例
                return $this->model->add_adminer( $datas );
            } catch (\Exception $e){
                return return_exception( $e);
            }
        }

        //分配权限
        public function distribution_auth( Request $request, AdminsValidate $adminsvalidate ){
            try{
                $datas = $request->post();  //获取请求数据

                if( !$adminsvalidate->scene('auth')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $adminsvalidate->getError());
                }

                $this->getmodel();  //获取model实例
                return (new \app\userrelated\model\Auth())->give_auth($datas);
            } catch (\Exception $e){
                return return_exception($e);
            }
        }


    /*
     * 删
     */
        public function delete( Request $request, AdminsValidate $adminsvalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                if( !$adminsvalidate->scene('delete')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $adminsvalidate->getError());
                }

                //严格校验
                if( ($datas['del_mode'] == 1) && ((preg_match('/^[1-9]\d{0,9}$/', $datas['del_id']) == 0) || ($datas['del_id'] > 2147483647)) ){  //单条删除模式
                    return response_tpl(2, 'id格式与模式不匹配或者长度过长');
                }else if( $datas['del_mode'] == 2 ){    //多条删除模式
                    $ids_arr = explode(',', trim($datas['del_id'], ','));
                    $is_too_long = false;
                    foreach( $ids_arr as $v ){
                        if( $v > 2147483647 ){
                            $is_too_long = true;
                            break;
                        }
                    }
                    if( $is_too_long == true ){
                        return response_tpl(2, 'id长度过长');
                    }
                    $datas['del_id'] = $ids_arr;
                }

                $this->getmodel();  //获取model实例
                return $this->model->del_adminer( $datas );
            } catch (\Exception $e){
                return return_exception( $e);
            }
        }


    /*
     * 改
     */
        public function update( Request $request, AdminsValidate $adminsvalidate ){
            try{
                $datas = $request->post();  //获取请求数据

                //允许提交表单校验
                $allow_fields = ['id','passwd','role','department','nike_name',
                    'header_img','ture_name','phone','email','qq','wx','sex','age',
                    'country','province','city','area','county','town','village','addr',
                    'zjno','zjimg','description','check'];
                $datas = allow_request_field($datas, $allow_fields);
                if( $datas === false ){
                    return response_tpl(2, '请求参数过多');
                }

                if( !$adminsvalidate->scene('edit')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $adminsvalidate->getError());
                }
                $this->getmodel();  //获取model实例
                return $this->model->update_adminer( $datas );
            } catch (\Exception $e){
                return return_exception( $e);//系统异常
            }
        }

    /*
     * 查
     */
        //用户列表
        public function showlist(){
            return view();   //模板
        }

        //获取用户数据列表
        public function list_datas(Request $request, AdminsValidate $adminsvalidate){
            try{
                $condition = $request->post();  //获取请求数据

                if( !$adminsvalidate->scene('select')->check($condition) ){
                    //验证不通过
                    return response_tpl(2, $adminsvalidate->getError());
                }
                $this->getmodel();  //获取model实例
                return $this->model->get_adminer_list($condition);   //将数据返回模板(系统默认json)
            } catch ( \Exception $e ) {
                return return_exception($e);//系统异常
            }
        }

        //获取一条用户数据
        public function get_one_data(Request $request, AdminsValidate $adminsvalidate){
            try{
                $condition = $request->post();  //获取请求数据

                if( !$adminsvalidate->scene('select_one')->check($condition) ){
                    //验证不通过
                    return response_tpl(2, $adminsvalidate->getError());
                }
                $this->getmodel();  //获取model实例
                return $this->model->get_one_data( $condition['id'] );   //将数据返回模板(系统默认json)
            } catch (\Exception $e){
                return return_exception( $e);//系统异常
            }
        }

        //用户登陆
        public function check_amdiner(Request $request, AdminsValidate $adminsvalidate){


            try{
                $condition = $request->post();  //获取请求数据

                if( !$adminsvalidate->scene('login')->check($condition) ){
                    //验证不通过
                    return response_tpl(2, $adminsvalidate->getError());
                }
                $this->getmodel();  //获取model实例
                return $this->model->select_check($condition['name'], $condition['passwd']);   //将数据返回模板(系统默认json)
            } catch (\Exception $e){
                return return_exception( $e);//系统异常
            }
        }

        //用户登陆页面
        public function login(){
            $cookie = json_decode(cookie(config('cookie')['extcookie']['adminer_info']), true);
            if( $cookie != null ){
                $result = $this->cache_check($cookie);
                if( $result['res_code'] == 200 ){
                    $this->forbidden_login_again();
                }
            }
            return view(); //模板
        }
        public function index() {
            return $this->fetch('admins/login'); //模板
        }

        //用户退出登陆
        public function logout() {
            //清空redis和cookie
            $cookie = cookie(config('cookie')['extcookie']['adminer_info']);
            if( $cookie != null ){
                try{
                    $cookie = json_decode($cookie, true);
                    $redis = new \app\commonuse\controller\Redis();
                    if( $redis->kvdelete(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['adminer_info'].$cookie['uid']) ){
                        cookie(null);
                        //成功退出登陆
                        return response_tpl(200, '您已退出登陆，请点击确定跳转');
                    }
                    //退出登陆失败
                    return response_tpl(400, '退出失败:缓存无法删除');
                } catch (\Exception $e){
                    return return_exception( $e);//系统异常
                }
            }else{
            //并未登陆，禁止退出操作，跳转登录页面
            echo <<<eof
               window.top.location.href="/backend/login";
eof;
            }
        }


        //获取所有角色列表
        public function get_dprole_list(){
            try{
                $this->getmodel();  //获取model实例
                return $this->model->get_dprole_list_m();
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }



    /******************************************************
     * 公用代码区
     */
        //实例化model类方法
        private function getmodel(){
            $this->model = new \app\userrelated\model\Admins();
        }
}

