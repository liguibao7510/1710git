<?php
namespace app\contents\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：文章图片管理Model类
 */
use think\Db;
class Articleimg {
    private $table_name;
    private $model;
    private $db;
    private $redis;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'article_img';
    }
    
    
    /*
     * 增
     */
        public function add_m($url){
            try{
                $effects = Db::execute(
                        'INSERT INTO '
                        . $this->table_name.' '
                        . '(artimg_url) VALUES (?)',
                        [$url]
                    );
                if( $effects>0 ){
                    return response_tpl(200, '新增成功', Db::getLastInsID());
                }
                return response_tpl(400, '图片管理数据新增失败');
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }


    /*
     * 删
     */
        /*  删除单个文件
         * 【注意事项】：调用此方法一定要进行删除成功后的事务提交(在调用者中进行)
         */
        public function delete_m($id){
            try{
                Db::startTrans();
                $del_result = Db::execute(
                        'DELETE FROM '
                        . $this->table_name.' '
                        . 'WHERE artimg_id = ?',[$id]);
                if(  $del_result == 0 ){
                    Db::rollback();
                    return response_tpl(400, '操作失败请稍后再试');
                }
                return response_tpl(200, '删除数据成功');
            } catch (\Exception $e) {
                Db::rollback();
                return return_exception($e);//系统异常
            }
        } 
        /*  删除多个文件
         * 【注意事项】：调用此方法一定要进行删除成功后的事务提交(在调用者中进行)
         *  @param $ids 包含id的一维数组
         */
        public function deletes($ids){
            try{
                if( !is_array($ids) && (count($ids) === 0) ){
                    return response_tpl(2, '删除多个文件时给的参数不合法[一维数组]');
                }
                Db::startTrans();
                $params = '';
                foreach( $ids as $v ){
                    $params .= '?,';
                }
                $del_result = Db::execute(
                        'DELETE FROM '
                        . $this->table_name.' '
                        . 'WHERE artimg_id IN ('.rtrim($params,',').')'
                        ,$ids
                    );
                if(  $del_result == 0 ){
                    Db::rollback();
                    return response_tpl(400, '操作失败请稍后再试');
                }
                return response_tpl(200, '删除数据成功');
            } catch (\Exception $e) {
                Db::rollback();
                return return_exception($e);//系统异常
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
        
        //获取菜单列表
        public function get_data_list(){
            try{
                $field = clear_field_pre(new Db(), $this->table_name, true, null, 'artcf_');
                return response_tpl(200, '获取数据成功', null);
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