<?php
namespace app\contents\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：文章分类管理Model类
 */
use think\Db;
class Articleclassify {
    private $table_name;
    private $model;
    private $db;
    private $redis;
    private $max_level;
    private $datas_arr;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'article_classify';
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
                        . '(artcf_name,artcf_icon,artcf_pid,artcf_indexno,artcf_url) VALUES (?,?,?,?,?)',
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
                        . 'WHERE artcf_id = ?',[$id]);
                if(  $del_result == 0 ){
                    return response_tpl(400, '操作失败请稍后再试');
                }
                $this->get_data_list(true);
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
                        . 'SET artcf_name = ?,artcf_icon = ?,artcf_indexno = ?,artcf_url = ? '
                        . 'WHERE artcf_id = ?',
                        [$datas['name'],$datas['icon'],$datas['indexno'],$datas['url'],$datas['id']]
                    );
                if( $effects>0 ){
                    $this->get_data_list(true);
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
                            'SELECT artcf_path FROM '.$this->table_name.' '
                            . 'WHERE artcf_id = ?',
                            [$pid]
                        );

                    $parent_path=$parent_path[0]['artcf_path']."-".$newest_id;    //得到其全路径
                }

                $auth_level= substr_count($parent_path, "-");   //根据全路径的"-"个数维护其水平
                //执行更新操作
                $update_result = Db::execute(
                        'UPDATE '.$this->table_name.' '
                        . 'SET artcf_path = ? , artcf_level = ? WHERE artcf_id = ?',
                        [$parent_path, $auth_level, $newest_id]
                    );

                if( $update_result > 0 ){
                    return response_tpl(200, '操作成功',[
                            'id'    =>  $newest_id,
                            'pid'   =>  $pid,
                            'level' =>  $auth_level
                        ]);    //字段维护操作成功
                    $this->get_data_list(true);
                }
                return response_tpl(400, '二次维护失败');   // 字段维护操作失败！
            } catch (\Exception $e) {
                Db::rollback(); //数据回滚
                return return_exception( $e);//系统异常
            }
        }
    
    /*
     * 查
     */
        
        //获取菜单列表
        public function get_data_list($make_json=false){
            try{
                $field = clear_field_pre(new Db(), $this->table_name, true, null, 'artcf_');
                //查探层级数
                $top_level = Db::query(
                        'SELECT MAX(artcf_level) FROM '.$this->table_name
                    );
                $this->max_level = $top_level[0]['MAX(artcf_level)'];
                //顶级
                $top = Db::query(
                        'SELECT '.$field.' FROM '.$this->table_name.' '
                        . 'WHERE artcf_level = 0 ORDER BY artcf_indexno'
                    );
                if( $this->max_level > 0 ){
                    $this->datas_arr = [];
                    for( $i=1; $i<=$this->max_level; $i++ ){
                        $this->datas_arr[$i] = Db::query(
                            'SELECT '.$field.' FROM '.$this->table_name.' '
                            . 'WHERE artcf_level = ? ORDER BY artcf_indexno',[$i]
                        );
                    }
                    $top = $this->make_list_datas($top,$this->datas_arr[1],1,$make_json);
                }
                return response_tpl(200, '获取数据成功', $top);
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }
        
        //层级数据制作
        private function make_list_datas($arr, $child_arr, $current_level=1, $make_json=false){
            $tmp_arr = [];
            if( is_array($arr) && is_array($child_arr) && (count($child_arr)>0) ){
                $current_level++;
                foreach( $arr as $k => $v ){
                    $arr[$k]['child'] = [];
                    foreach( $child_arr as $vv ){
                        if( $vv['pid'] === $v['id'] ){
                            if( $current_level <= $this->max_level ){
                                $vv = $this->make_list_datas($child_arr, $this->datas_arr[$current_level],$current_level);
                                foreach( $vv as $vvv ){
                                    if( $vvv['pid'] === $v['id'] && !in_array($vvv, $arr[$k]['child'], true) ){
                                        $arr[$k]['child'][$vvv['id']] = $vvv;
                                    }
                                }
                            }else{
                                $arr[$k]['child'][$vv['id']] = $vv;
                            }
                        }
                    }
                    $tmp_arr[$v['id']] = $arr[$k];
                }
            }
            if( $make_json === true ){
                mywrite('var articleclassify = '.json_encode($tmp_arr),'./static/js/json_datas/articleclassify.js','w');
            }
            return $tmp_arr;
        }


        //判断其是否存在下级
        private function exists_next($id){
            try{
                $check_result = Db::query(
                        'SELECT count(*) FROM '
                        . $this->table_name.' '
                        . 'WHERE artcf_pid = ?',[$id]);
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