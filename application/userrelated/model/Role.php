<?php
namespace app\userrelated\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：角色管理Model类
 */
use think\Db;
class Role{
    private $table_name;
    private $model;
    private $db;
    private $redis;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'role';
    }
    
    
    /*
     * 增
     */
        public function add_m($datas){
            try{
                $check_result = $this->select_exists($datas['name'], $datas['department']);
                if( $check_result['res_code'] == 200 ){
                    return response_tpl(600, '角色已经存在');
                }
                
                $effects = Db::execute(
                        'INSERT INTO '
                        . $this->table_name.' '
                        . '(rol_name,rol_department,rol_level,rol_description,rol_indexno) VALUES (?,?,?,?,?)',
                        [$datas['name'],$datas['department'],$datas['level'],$datas['description'],$datas['indexno']]
                    );
                if( $effects>0 ){
                    return response_tpl(200, '添加数据成功',Db::getLastInsID());    //新增成功返回最新添加的id
                }
                return response_tpl(400, '新增失败，请稍后再试');
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }


    /*
     * 删
     */
        public function delete_m($id){
            try{
                $del_result = Db::execute(
                        'DELETE FROM '
                        . $this->table_name.' '
                        . 'WHERE rol_id = ?',[$id]);
                if(  $del_result == 0 ){
                    return response_tpl(400, '操作失败请稍后再试');
                }
                return response_tpl(200, '删除数据成功');
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        } 
    
    
    /*
     * 改
     */
        public function update_m($datas){
            try{
                $check_result = $this->select_exists($datas['name'], $datas['department'], $datas['id']);
                if( $check_result['res_code'] == 200 ){
                    return response_tpl(600, '角色已经存在');
                }
                $effects = Db::execute(
                        'UPDATE '. $this->table_name.' '
                        . 'SET rol_name = ?,rol_department = ?,rol_level = ?,rol_description = ?,rol_indexno = ? '
                        . 'WHERE rol_id = ?',
                        [$datas['name'],$datas['department'],$datas['level'],$datas['description'],$datas['indexno'],$datas['id']]
                    );
                if( $effects>0 ){
                    return response_tpl(200, '更新数据成功',Db::getLastInsID());    //新增成功返回最新添加的id
                }
                return response_tpl(400, '更新失败，请稍后再试');
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }
    
    /*
     * 查
     */
        //获取部门列表
        public function get_data_list(){
            try{
                $field = clear_field_pre(new Db(), $this->table_name, false, null, 'rol_');
                $result_datas = Db::query(
                    'SELECT '.$field.' FROM '
                    . $this->table_name.' '
                    . 'ORDER BY rol_indexno ASC'
                );
                if( count($result_datas)>0 ){
                    return response_tpl(200, '获取数据成功',$result_datas);
                }
                return response_tpl(404, '没有数据');
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }
        
        //获取权限列表
        public function get_auth_list($role_id){
            try{
                $auth_table = config("database.prefix").'auth';
                $field = clear_field_pre(new Db(), $auth_table, true, null, 'auth_');
                $model = Db::query(
                        'SELECT '.$field.' FROM '.$auth_table.' '
                        . 'WHERE auth_level = 0 ORDER BY auth_indexno'
                    );
                if( count($model) == 0 ){
                    return response_tpl(404, '没有数据');
                }
                $controller = Db::query(
                        'SELECT '.$field.' FROM '.$auth_table.' '
                        . 'WHERE auth_level = 1 ORDER BY auth_indexno'
                    );
                $have_controller = false;
                $have_action = false;
                if( count($controller) > 0 ){
                    $action = Db::query(
                            'SELECT '.$field.' FROM '.$auth_table.' '
                            . 'WHERE auth_level = 2 ORDER BY auth_indexno'
                        );
                    if( count($action) > 0 ){
                        $have_action = true;
                    }
                    $have_controller = true;
                }
                
                //查询该角色已经拥有的权限id
                $role_had_ids = Db::query(
                        'SELECT rka_aids FROM '.config("database.prefix").'rlinka '
                        . 'WHERE rka_rid = ?',[$role_id]
                    );
                $had_auth = false;
                if( count($role_had_ids) > 0 ){
                    $had_auth = true;
                    $ids_arr = explode(',', $role_had_ids[0]['rka_aids']);
                }
                
                foreach( $model as $k=>$v ){
                    $model[$k]['selected'] = 0;
                    if( $have_controller === true ){
                        $model[$k]['child'] = [];
                        foreach( $controller as $kk=>$vv ){
                            if( $v['id'] == $vv['pid'] ){
                                $controller[$kk]['selected'] = 0;
                                if( $have_action === true ){
                                    $controller[$kk]['child'] = [];
                                    foreach( $action as $kkk=>$vvv ){
                                        if( $vv['id'] == $vvv['pid'] ){
                                            if( ($had_auth === true) && in_array($vvv['id'], $ids_arr) ){
                                                $vvv['selected'] = 1;
                                                $model[$k]['selected'] = 1;
                                                $controller[$kk]['selected'] = 1;
                                            }else{
                                                $vvv['selected'] = 0;
                                            }
                                            $controller[$kk]['child'][] = $vvv;
                                        }
                                    }
                                }
                                $model[$k]['child'][] = $controller[$kk];
                            }
                        }
                    }
                }
                return response_tpl(200, '获取数据成功', $model);
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }
        
        
        //查询数据是否已经存在
        public function select_exists( $name, $department, $id=0 ){
            try {
                if( !empty($name) && !empty($department) ){
                    if( $id == 0 ){
                        $check_result = Db::query(
                                'SELECT count(*) FROM '
                                . $this->table_name.' '
                                . 'WHERE rol_name = ? AND rol_department = ?',
                                [$name, $department]);
                    }else if( $id != 0 ){
                        $check_result = Db::query(
                                'SELECT count(*) FROM '
                                . $this->table_name.' '
                                . 'WHERE rol_id != ? AND rol_name = ? AND rol_department = ?',
                                [$id, $name, $department]);
                    }
                    if( $check_result[0]['count(*)'] == 0 ){
                        return response_tpl(404, '没有数据'); 
                    }
                    return response_tpl(200, '存在数据');
                }
                return response_tpl(403, '请求参数不合法');
            } catch (Exception $e) {
                return return_exception( $e);//系统异常
            }
        }


        /*
     * 公用代码区***************************************
     */
        //获取model
        private function get_model( $newdb = true ){
            $this->model = Db::table($this->table_name);
            if( $newdb === true ){
                $this->db = new Db();
            }
        }
        
        //获取redis
        private function get_redis(){
            $this->redis =new \app\commonuse\controller\Redis();
        }
        
}