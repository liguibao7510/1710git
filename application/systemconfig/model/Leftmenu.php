<?php
namespace app\systemconfig\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：左侧菜单栏管理Model类
 */
use think\Db;
class Leftmenu{
    private $table_name;
    private $model;
    private $db;
    private $redis;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'left_menu';
    }
    
    
    /*
     * 增
     */
        public function add_m($datas){
            try{
                $datas['pid'] = isset($datas['pid']) ? $datas['pid'] : 0;
                $effects = Db::execute(
                        'INSERT INTO '
                        . $this->table_name.' '
                        . '(ltmu_name,ltmu_icon,ltmu_pid,ltmu_indexno,ltmu_url) VALUES (?,?,?,?,?)',
                        [$datas['name'],$datas['icon'],$datas['pid'],$datas['indexno'],$datas['url']]
                    );
                if( $effects>0 ){
                    return $this->after_insert(Db::getLastInsID(), $datas['pid']);
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
                $exists_check = $this->exists_next($id);
                if( $exists_check['res_code'] !== 404 ){
                    return $exists_check;
                }
                $del_result = Db::execute(
                        'DELETE FROM '
                        . $this->table_name.' '
                        . 'WHERE ltmu_id = ?',[$id]);
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
                
                $effects = Db::execute(
                        'UPDATE '. $this->table_name.' '
                        . 'SET ltmu_name = ?,ltmu_icon = ?,ltmu_indexno = ?,ltmu_url = ? '
                        . 'WHERE ltmu_id = ?',
                        [$datas['name'],$datas['icon'],$datas['indexno'],$datas['url'],$datas['id']]
                    );
                if( $effects>0 ){
                    return response_tpl(200, '更新数据成功',Db::getLastInsID());    //新增成功返回最新添加的id
                }
                return response_tpl(400, '更新失败，请稍后再试');
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }
        
        //维护必要字段
        private function after_insert($newest_id, $pid){
            try{
                //无上级(0)
                if($pid==0){
                    $parent_path=$newest_id;
                }
                //有上级
                else{
                    $parent_path = Db::query(
                            'SELECT ltmu_path FROM '.$this->table_name.' '
                            . 'WHERE ltmu_id = ?',
                            [$pid]
                        );

                    $parent_path=$parent_path[0]['ltmu_path']."-".$newest_id;    //得到其全路径
                }

                $auth_level= substr_count($parent_path, "-");   //根据全路径的"-"个数维护其水平
                //执行更新操作
                $update_result = Db::execute(
                        'UPDATE '.$this->table_name.' '
                        . 'SET ltmu_path = ? , ltmu_level = ? WHERE ltmu_id = ?',
                        [$parent_path, $auth_level, $newest_id]
                    );

                if( $update_result > 0 ){
                    return response_tpl(200, '操作成功',[
                            'id'    =>  $newest_id,
                            'pid'   =>  $pid,
                            'level' =>  $auth_level
                        ]);    //字段维护操作成功
                }
                return response_tpl(400, '二次维护失败');   // 字段维护操作失败！
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }
    
    /*
     * 查
     */
        
        //获取菜单列表
        public function get_data_list(){
            try{
                $field = clear_field_pre(new Db(), $this->table_name, true, null, 'ltmu_');
                $top_menu = Db::query(
                        'SELECT '.$field.' FROM '.$this->table_name.' '
                        . 'WHERE ltmu_level = 0 ORDER BY ltmu_indexno'
                    );
                if( count($top_menu) == 0 ){
                    return response_tpl(404, '没有数据');
                }
                $second_menu = Db::query(
                        'SELECT '.$field.' FROM '.$this->table_name.' '
                        . 'WHERE ltmu_level = 1 ORDER BY ltmu_indexno'
                    );
                if( count($second_menu) > 0 ){
                    foreach( $top_menu as $k => $v ){
                        $top_menu[$k]['child'] = [];
                        foreach( $second_menu as $vv ){
                            if( $vv['pid'] === $v['id'] ){
                                $top_menu[$k]['child'][] = $vv;
                            }
                        }
                    }
                }
                return response_tpl(200, '获取数据成功', $top_menu);
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }
        
        //判断其是否存在下级
        private function exists_next($id){
            try{
                $check_result = Db::query(
                        'SELECT count(*) FROM '
                        . $this->table_name.' '
                        . 'WHERE ltmu_pid = ?',[$id]);
                if(  $check_result[0]['count(*)'] > 0 ){
                    return response_tpl(201, '该数据存在下级');
                }
                return response_tpl(404, '不存在数据');
            } catch (\Exception $e) {
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