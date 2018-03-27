<?php
namespace app\userrelated\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：部门管理Model类
 */
use think\Db;
class Department{
    private $table_name;
    private $model;
    private $db;
    private $redis;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'department';
    }
    
    
    /*
     * 增
     */
        public function add_m($datas){
            try{
                $check_result = $this->select_exists('name', $datas['name']);
                if( $check_result['res_code'] == 200 ){
                    return response_tpl(600, '部门名称已经存在');
                }
                
                $check_result = $this->select_exists('code', $datas['code']);
                if( $check_result['res_code'] == 200 ){
                    return response_tpl(600, '部门编号已经存在');
                }
                
                
                $effects = Db::execute(
                        'INSERT INTO '
                        . $this->table_name.' '
                        . '(dptmt_name,dptmt_code,dptmt_indexno,dptmt_description) VALUES (?,?,?,?)',
                        [$datas['name'],$datas['code'],$datas['indexno'],$datas['description']]
                    );
                if( $effects>0 ){
                    return response_tpl(200, '添加数据成功',Db::getLastInsID());    //新增成功返回最新添加的id
                }
                return response_tpl(400, '新增失败，请稍后再试');
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }




    /*
     * 删
     */
        public function delete_m($id){
            try{
                
                //判断该部门下是否存在角色，若存在禁止删除
                $role_counts = Db::query(
                        'SELECT count(*) FROM '.config("database.prefix").'role '
                        . 'WHERE rol_department = ?',[$id]
                    );
                if( $role_counts[0]['count(*)'] > 0 ){
                    return response_tpl(403, '该部门还存在角色，禁止删除');
                }
                
                $del_result = Db::execute(
                        'DELETE FROM '
                        . $this->table_name.' '
                        . 'WHERE dptmt_id = ?',[$id]);
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
                $datas = add_prefix($datas, 'dptmt_');    //新增字段前缀
                
                //获取旧数据
                $old_data = Db::query(
                        'SELECT * FROM '
                        . $this->table_name.' WHERE dptmt_id = ? LIMIT 1',
                    [$datas['dptmt_id']]);
                if( $old_data[0]['dptmt_name'] != $datas['dptmt_name'] ){
                    $check_result = $this->select_exists('name', $datas['dptmt_name']);
                    if( $check_result['res_code'] == 200 ){
                        return response_tpl(600, '部门名称已经存在');
                    }
                }
                if( $old_data[0]['dptmt_code'] != $datas['dptmt_code'] ){
                    $check_result = $this->select_exists('code', $datas['dptmt_code']);
                    if( $check_result['res_code'] == 200 ){
                        return response_tpl(600, '部门编号已经存在');
                    }
                }
                $upd_result = Db::execute(
                        'UPDATE '.$this->table_name.' '
                        . 'SET dptmt_name=?,dptmt_code=?,dptmt_indexno=?,dptmt_description=? '
                        . 'WHERE dptmt_id=?',
                        [$datas['dptmt_name'],$datas['dptmt_code'],$datas['dptmt_indexno'],$datas['dptmt_description'],$datas['dptmt_id']]
                    );
                if( $upd_result > 0 ){
                    return response_tpl(200, '更新操作数据操作成功');
                } else {
                    return response_tpl(400, '操作失败，请稍后再试');
                }
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
                $field = clear_field_pre(new Db(), $this->table_name, false, null, 'dptmt_');
                $result_datas = Db::query(
                    'SELECT '.$field.' FROM '
                    . $this->table_name.' '
                    . 'ORDER BY dptmt_indexno ASC'
                );
                if( count($result_datas)>0 ){
                    return response_tpl(200, '获取数据成功',$this->make_role_datas($result_datas));
                }
                return response_tpl(404, '没有数据');
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }
        
        //制作包含角色数据
        private function make_role_datas($result_datas){
            $roles = (new \app\userrelated\model\Role())->get_data_list();
            if( $roles['res_code'] != 200 ){
                return $result_datas;
            }
            foreach( $result_datas as $k=>$v ){
                foreach( $roles['datas'] as $vv ){
                    if( $vv['department'] == $v['id'] ){
                        $result_datas[$k]['roles'][] = $vv;
                    }
                }
            }
            return $result_datas;
        }




        //查询数据是否已经存在
        public function select_exists( $key, $val ){
            try {
                if( !empty($key) && !empty($val) ){
                    $key = 'dptmt_'.$key;
                    $allow_fileds = ['dptmt_name','dptmt_code'];
                    if( !in_array($key, $allow_fileds) ){
                        return response_tpl(403, '请求参数不合法');
                    }
                
                    $check_result = Db::query(
                            'SELECT count(*) FROM '
                            . $this->table_name.' '
                            . 'WHERE '.$key.' = ?',
                            [$val]);
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