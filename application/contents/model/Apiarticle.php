<?php
namespace app\contents\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：文章接口管理Model类
 */
use think\Db;
class Apiarticle {
    private $table_name;
    private $model;
    private $db;
    private $redis;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'article';
    }


        /*
     * 查
     */
        
        //获取文章列表
        public function getDataList($condition){
            try{
                //初始化分页条件
                $condition['every_get'] = empty($condition['every_get']) ? 10 : $condition['every_get'];   //默认每页获取10条
                $condition['current_page'] = empty($condition['current_page']) ? 1 : $condition['current_page'];   //默认当前页码为第1页
                
                $condition = add_prefix($condition, 'atic_');   //给搜索条件添加前缀
                
                $allow_search_fields = [
                    'atic_add_time','atic_upd_time','atic_uid','atic_classify','atic_province',
                    'atic_state_city','atic_area_county','atic_town','atic_village','atic_is_up',
                    'atic_is_check'
                    ];
                
                //制作$where
                $where = '';
                $param = [];
                $count = 0;
                foreach( $condition as $k=>$v ){
                    if( in_array($k, $allow_search_fields,true) && !empty($v) ){
                        //将json字符转为Array对象
                        $v = json_decode(htmlspecialchars_decode($v),true);
                        
                        if( $count > 0 ){
                            if( is_array($v) && (count($v) === 2) ){
                                $where .= 'AND '.$k.' >= ? AND '.$k.' <= ? ';
                                $param[] = $v[0];
                                $param[] = $v[1];
                            }else{
                                if( $k === 'atic_is_up' ){
                                    $where .= 'AND '.$k.' > ? ';
                                    $param[] = 0;
                                }else{
                                    $where .= 'AND '.$k.' = ? ';
                                    $param[] = $v;
                                }
                            }
                        }else{
                            $where = ' WHERE ';
                            if( is_array($v) && (count($v) === 2) ){
                                $where .= $k.' >= ? AND '.$k.' <= ? ';
                                $param[] = $v[0];
                                $param[] = $v[1];
                            }else{
                                if( $k === 'atic_is_up' ){
                                    $where .= $k.' > ? ';
                                    $param[] = 0;
                                }else{
                                    $where .= $k.' = ? ';
                                    $param[] = $v;
                                }
                            }
                        }
                        $count++;
                    }
                }
                if( $count > 0 ){
                    $where .= ' AND atic_is_check = 2 AND atic_is_del = 1 ';
                }else{
                    $where .= 'WHERE atic_is_check = 2 AND atic_is_del = 1 ';
                }
                $param[] = ($condition['atic_current_page']-1)*$condition['atic_every_get'];    //当前页码
                $param[] = $condition['atic_every_get'];    //每页获取数目
                $select = [
                    'atic_keywords','atic_contents','atic_file_id','atic_add_time','atic_province',
                    'atic_state_city','atic_area_county','atic_town','atic_village'
                    ];
                
                $this->get_model();
                $field = clear_field_pre((new Db()), $this->table_name, false, $select, 'atic_');
//                $result_datas = $this->model->field($field)->paginate([
//                    'list_rows' =>  $condition['every_get'],    //每页获取数量
//                    'page'      =>  $condition['current_page']  //当前页码
//                ]);
                //算出总条数
                $total_arr = Db::query(''
                        . 'SELECT count(*) FROM '.$this->table_name
                        );
                //将时间戳转为常规时间格式
//                $field = str_replace('atc.atic_upd_time as upd_time', 'FROM_UNIXTIME(atc.atic_upd_time) as upd_time', $field);
                $list_datas = Db::query(
                        'SELECT '.$field.' FROM '.$this->table_name.' '.$where
                        . 'LIMIT ?,?',$param
                    );
                
                if( count($list_datas)>0 ){
                    $result_datas['data'] = $list_datas;
                    $result_datas['total'] = $total_arr[0]['count(*)'];
                    $result_datas['per_page'] = $condition['atic_every_get'];
                    $result_datas['last_page'] = ceil($total_arr[0]['count(*)']/$result_datas['per_page']);
                    $result_datas['current_page'] = $condition['atic_current_page'];
                    return response_tpl(200, '获取数据成功',$result_datas);
                }
                return response_tpl(404, '没有数据');
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }
        
        //获取一条数据
        public function getOneData($id){
            try{
                $field = clear_field_pre((new Db()), $this->table_name, false, null, 'atic_');
                $datas = Db::query(''
                        . 'SELECT '.$field.' FROM '.$this->table_name.' '
                        . 'WHERE atic_id = ?'
                        ,[$id]
                    );
                if( count($datas)>0 ){
                    return response_tpl(200, '获取数据成功',$datas);
                }
                return response_tpl(404, '没有数据');
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
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