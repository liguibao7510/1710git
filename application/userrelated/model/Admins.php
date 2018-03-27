<?php
namespace app\userrelated\model;
/*
 * 【功能】：用户管理Model类
 */
use think\Db;
class Admins{
    private $table_name;
    private $admins;
    private $db;
    private $redis;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'admins';
    }


    /*
     * 增
     */
        public function add_adminer($datas){
            try{
                $this->get_model(false);
                $datas = add_prefix($datas, 'ads_');    //新增字段前缀
                $datas['ads_passwd'] = salt($datas['ads_passwd'], 'admin'); //密码加盐
                $datas['ads_add_time'] = time();
                Db::startTrans();   //开启事务
                $newest_id = $this->admins->insertGetId($datas);
                if( $newest_id ){
                    Db::commit();   //事物提交
                    return response_tpl(200, '新增成功',$newest_id);    //新增成功返回最新添加的用户id
                }
                return response_tpl(400, '新增失败，请稍后再试');
            } catch (\Exception $e) {
                Db::rollback(); //数据回滚
                return return_exception($e);//系统异常
            }
        }

        //分配权限
        public function give_auth($uid, $auth_ids){
            $auth_ids = trim($auth_ids, ',');
            try {
                $give_resutl = Db::execute(
                        'INSERT INTO '.config("database.prefix").'user_auth '
                        . '(urath_uid,urath_aids) VALUES (?,?) '
                        . 'ON DUPLICATE KEY UPDATE urath_aids = ?',[$uid,$auth_ids,$auth_ids]
                    );
                if( $give_resutl > 0 ){
                    //将权限数据同步到redis
                    $auth_ids_arr = explode(',', $auth_ids[0]['rka_aids']);
                    $condition = '';
                    foreach( $auth_ids_arr as $v ){
                        $condition .= '?,';
                    }
                    $auth_datas = Db::query(
                            'SELECT auth_model,auth_controller,auth_action FROM '.config("database.prefix").'auth '
                            . 'WHERE auth_id IN ('. trim($condition, ',') .')',$auth_ids_arr
                        );
                    $sadd_arr = [];
                    foreach( $auth_datas as $v ){
                        $sadd_arr[] = strtolower($v['auth_model'].'-'.$v['auth_controller'].'-'.$v['auth_action']);
                    }
                    $this->get_redis();
                    if( $this->redis->set_sadd(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['adminer_auth'].$uid, $sadd_arr) ){
                        return response_tpl(200, '权限分配且同步成功');
                    }
                    return response_tpl(400, '权限分配成功但是同步失败');
                }
                return response_tpl(400, '权限分配失败');
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }


    /*
     * 删
     */
        public function del_adminer($datas){
            Db::startTrans();    //开启事务
            try{
                //判断删除模式
                if( $datas['del_mode'] == 1 ){  //单条删除模式
                    $del_result = Db::execute(
                            'DELETE FROM '
                            . $this->table_name.' '
                            . 'WHERE ads_id = ? AND ads_name != ?',[$datas['del_id'], config('FOUNDER')]);
                    if(  $del_result == 0 ){
                        Db::rollback();  //数据回滚
                        return response_tpl(400, '操作失败请稍后再试');
                    }
                    Db::commit();  //事务提交
                    return response_tpl(200, '删除数据成功');
                }else if( $datas['del_mode'] == 2 ){    //多条删除模式
                    $condition = '';
                    foreach( $datas['del_id'] as $v){
                        $condition .= '?,';
                    }
                    $del_result = Db::query(
                            'DELETE FROM '
                            . $this->table_name.' '
                            . 'WHERE ads_id IN ('. trim($condition, ',').') AND ads_name != '.config('FOUNDER'),
                            $datas['del_id']
                        );
                    if( $del_result < count($datas['del_id']) ){
                        Db::rollback();  //数据回滚
                        return response_tpl(400, '操作失败请稍后再试['.count($datas['del_id']).'/'.$del_result.']');
                    }
                    Db::commit();  //事务提交
                    return response_tpl(200, '删除数据成功');
                }
            } catch (\Exception $e) {
                Db::rollback();  //数据回滚
                return return_exception( $e);//系统异常
            }
        }


    /*
     * 改
     */
        public function update_adminer($datas){
            try{
                $this->get_model();
                $datas = add_prefix($datas, 'ads_');    //新增字段前缀
                if( empty( $datas['ads_passwd'] ) ){
                    $eliminate = ['ads_name','ads_passwd','ads_add_time'];
                } else {
                    $datas['ads_passwd'] = salt($datas['ads_passwd'], 'admin'); //密码加盐
                    $eliminate = ['ads_name','ads_add_time'];
                }
                //干掉排除字段(原因：在框架严格检查字段是否存在时如果不进行此操作可能会抛异常)
                $datas = unset_designated_eliment($datas, $eliminate);

                $upd_result = $this->admins
                            ->field($eliminate, true)
                            ->where('ads_id','=',$datas['ads_id'])
                            ->update($datas);
                if( $upd_result > 0 ){
                    return response_tpl(200, '更新操作数据操作成功');
                } else {
                    return response_tpl(400, '操作失败，请稍后再试');
                }
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }


        }

    /*
     * 查
     */
        //获取用户列表
        public function get_adminer_list($condition=null){
            try{
                    //初始化条件
                if( empty($condition) || !is_array($condition) ){
                    $condition['every_get'] = 10;   //默认每页获取10条
                    $condition['current_page'] = 1; //默认当前页码为第1页
                }
                $this->get_model();
                $filed_select=['ads_passwd','ads_desc'];
                $field = clear_field_pre($this->db, $this->table_name, false, $filed_select, 'ads_');
                $result_datas = $this->admins->field($field)->paginate([
                    'list_rows' =>  $condition['every_get'],    //每页获取数量
                    'page'      =>  $condition['current_page']  //当前页码
                ]);
                if( count($result_datas)>0 ){
                    return response_tpl(200, '获取数据成功',$result_datas);
                }
                return response_tpl(404, '没有数据');
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }

        //获取部门与角色数据
        public function get_dprole_list_m(){
            try{
                $db = new Db();
                $depmt_list = Db::query(
                        'SELECT '
                        . clear_field_pre($db, config("database.prefix").'department', false, ['dptmt_code','dptmt_description'], 'dptmt_').' '
                        . 'FROM '.config("database.prefix").'department ORDER BY dptmt_indexno'
                    );

                if( count($depmt_list) == 0 ){
                    return response_tpl(404, '没有部门与角色数据');
                }

                $role_list = Db::query(
                        'SELECT '
                        . clear_field_pre($db, config("database.prefix").'role', false, ['rol_level','rol_description'], 'rol_').' '
                        . 'FROM '.config("database.prefix").'role ORDER BY rol_indexno'
                    );

                //进行数据制作
                foreach( $depmt_list as $k => $v ){
                    $depmt_list[$k]['child'] = [];
                    foreach( $role_list as $kk => $vv ){
                        if( $vv['department'] == $v['id'] ){
                            $depmt_list[$k]['child'][] = $vv;
                        }
                    }
                }
                return response_tpl(200, '获取部门与角色数据成功', $depmt_list);
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }


        //根据id获取对应的一条数据
        public function get_one_data( $id ){
            try {
                $this->get_model();
                $filed_select=['ads_passwd','ads_add_time'];
                $field = clear_field_pre($this->db, $this->table_name, false, $filed_select, 'ads_');
                $data = $this->admins->field($field)->where('ads_id','=',$id)->find();
                if( count($data) == 0 ){
                    return response_tpl(404, '该用户不存在');
                }
                return response_tpl(200, '获取数据成功',$data);
            } catch (Exception $e) {
                return return_exception( $e);//系统异常
            }
        }

        //查询数据并对比
        public function select_check( $username, $passwd ){
            try {
                $data = Db::query(''
                        . 'SELECT ads_id,ads_name,ads_role,ads_department,ads_nike_name FROM '
                        . $this->table_name.' '
                        . 'WHERE '
                        . 'ads_name = ? AND ads_passwd = ? limit 1',
                        [$username, salt($passwd, 'admin')]) ;
                if( count($data) == 0 ){
                    return response_tpl(404, '用户名或者密码错误');
                }

                //制作token
                $token = make_token($data[0]['ads_name']);

                //将个人常用信息存入redis
                $this->get_redis();
                if( !($this->redis->msethash(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['adminer_info'].$data[0]['ads_id'], [
                    'id'            =>  $data[0]['ads_id'],
                    'token'         =>  $token,
                    'name'          =>  $data[0]['ads_name'],
                    'role'          =>  $data[0]['ads_role'],
                    'department'    =>  $data[0]['ads_department'],
                    'nike_name'     =>  $data[0]['ads_nike_name']
                ], config('ADMIN_LOGIN_INVALID_TIME'))) ){
                    return response_tpl(406, '系统缓存错误无法登陆', $token);
                }

                //将用户名和token缓存到本地cookie
                cookie(config('cookie')['extcookie']['adminer_info'], json_encode([
                    'uid'  =>  $data[0]['ads_id'],
                    'rid'  =>  $data[0]['ads_role'],
                    'username'  =>  $username,
                    'token'     =>  $token
                ]), 0);

                return response_tpl(200, '登陆成功');
            } catch (Exception $e) {
                return return_exception( $e);//系统异常
            }
        }






        /*
     * 公用代码区***************************************
     */
        //获取model
        private function get_model( $newdb = true ){
            $this->admins = Db::table($this->table_name);
            if( $newdb === true ){
                $this->db = new Db();
            }
        }

        //获取redis
        private function get_redis(){
            $this->redis =new \app\commonuse\controller\Redis();
        }

}