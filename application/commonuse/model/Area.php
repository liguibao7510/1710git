<?php
namespace app\commonuse\model;
/*

 * 【功能】：地区管理Model类
 */
use think\Db;
class Area{
    private $table_name;
    private $table;
    private $db;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'area';
        $this->table = Db::table($this->table_name);
        $this->db = new Db();
    }

    /*
     * 增
     */



    /*
     * 删
     */



    /*
     * 改
     */




    /*
     * 查
     */

        //根据父级地区查出对应的地区数据
        public function get_datas_by_pid( $pid ){
            try {
//                $filed_select=['id','leve'];
//                $field = clear_field_pre($this->db, $this->table_name, false, $filed_select, '');
                $data = $this->table->field(['id','leve'],true)->where('parent_id','=',$pid)->select();
                if( count($data) == 0 ){
                    return response_tpl(404, '地区数据不存在');
                }
                return response_tpl(200, '获取数据成功',$data);
            } catch (Exception $e) {
                return return_exception( $e->getMessage(), __METHOD__ );//系统异常
            }
        }

}
