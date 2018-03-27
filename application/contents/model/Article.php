<?php
namespace app\contents\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：文章管理Model类
 */
use think\Db;
class Article {
    private $table_name;
    private $model;
    private $db;
    private $redis;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'article';
    }
    
    
    /*
     * 增
     */
        public function add_m($datas){
            try{
                $datas['cover'] = isset($datas['cover']) ? $datas['cover'] : '';
                if( !empty($datas['file_id']) ){
                    $file_id_check = $this->check_file_id($datas['file_id']);
                    if( $file_id_check !== true ){
                        return $file_id_check;
                    }
                    $datas['file_id'] = trim($datas['file_id'], ',');
                }
                $cookie = json_decode(cookie(config('cookie')['extcookie']['adminer_info']), true);
                
                $effects = Db::execute(
                        'INSERT INTO '
                        . $this->table_name.' '
                        . '(atic_cover,atic_title,atic_abstract,atic_keywords,atic_contents,'
                        . 'atic_file_id,atic_add_time,atic_upd_time,atic_uid,atic_classify,'
                        . 'atic_province,atic_state_city,atic_area_county,atic_town,atic_village) '
                        . 'VALUES '
                        . '(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                        [
                            $datas['cover'],$datas['title'],$datas['abstract'],$datas['keywords'],
                            $datas['contents'],$datas['file_id'],time(),time(),$cookie['uid'],$datas['classify'],
                            $datas['province'],$datas['state_city'],$datas['area_county'],$datas['town'],$datas['village'],
                        ]
                    );
                if( $effects>0 ){
                    return response_tpl(200, '新增数据成功', Db::getLastInsID());
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
                //判断图片管理表是否存在对于的图片数据，如果存在则删除
                Db::startTrans();
                $file_ids = Db::query(''
                        . 'SELECT atic_file_id FROM '.$this->table_name.' '
                        . 'WHERE atic_id = ?',
                        [$id]
                    );
                if( $file_ids[0]['atic_file_id'] != '' ){
                    $prepare = '';
                    $file_id_arr = explode(',', $file_ids[0]['atic_file_id']);
                    foreach( $file_id_arr as $v ){
                        $prepare .= '?,';
                    }
                    
                    //从图片管理表获取文件路径
                    $img_urls = Db::query(''
                            . 'SELECT artimg_url FROM '.config("database.prefix").'article_img '
                            . 'WHERE artimg_id IN ('.rtrim($prepare,',').')',
                            $file_id_arr
                        );
                    $img_urls = array_column($img_urls, 'artimg_url');  //将二维数组变为一维数组
                    
                    //删除图片管理表数据
                    $del_file_datas = Db::execute(''
                            . 'DELETE FROM '.config("database.prefix").'article_img '
                            . 'WHERE artimg_id IN ('.rtrim($prepare,',').')',
                            $file_id_arr
                        );
                    if( $del_file_datas === 0 ){
                        Db::commit();
                        return response_tpl(400, '文件数据无法删除，请稍后再试');
                    }
                }
                
                $del_result = Db::execute(
                        'DELETE FROM '
                        . $this->table_name.' '
                        . 'WHERE atic_id = ?',[$id]
                    );
                if(  $del_result == 0 ){
                    Db::rollback();
                    return response_tpl(400, '操作失败请稍后再试');
                }
                
                if( $file_ids[0]['atic_file_id'] != '' ){
                    //删除阿里云文件
                    $del_file = (new \app\upload\model\Alioss( ['img_urls'=>$img_urls] ))->deletes();
                    if( $del_file['res_code'] !== 200 ){
                        Db::rollback();
                        return $del_file;
                    }
                }
                Db::commit();
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
                        . 'SET atic_cover = ?,atic_title = ?,atic_abstract = ?,atic_keywords = ?,'
                        . 'atic_contents = ?,atic_file_id = ?,atic_upd_time = ?,atic_classify = ?,'
                        . 'atic_province = ?,atic_state_city = ?,atic_area_county = ?,atic_town = ?,'
                        . 'atic_village = ? '
                        . 'WHERE atic_id = ?',
                        [
                            $datas['cover'],$datas['title'],$datas['abstract'],$datas['keywords'],
                            $datas['contents'],$datas['file_id'],time(),$datas['classify'],
                            $datas['province'],$datas['state_city'],$datas['area_county'],$datas['town'],
                            $datas['village'],$datas['id'],
                        ]
                    );
                if( $effects>0 ){
                    return response_tpl(200, '更新数据成功');    //新增成功返回最新添加的id
                }
                return response_tpl(400, '更新失败，请稍后再试');
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }
        
        //切换审核状态
        public function changeCheckStatus($datas){
            try{
                $effects = Db::execute(
                        'UPDATE '. $this->table_name.' '
                        . 'SET atic_is_check = ? '
                        . 'WHERE atic_id = ?',
                        [$datas['is_check'],$datas['id']]
                    );
                if( $effects>0 ){
                    return response_tpl(200, '状态切换成功');    //新增成功返回最新添加的id
                }
                return response_tpl(400, '状态切换失败，请稍后再试');
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }


        /*
     * 查
     */
        
        //获取文章列表
        public function get_data_list($condition){
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
                $param[] = ($condition['atic_current_page']-1)*$condition['atic_every_get'];    //当前页码
                $param[] = $condition['atic_every_get'];    //每页获取数目
                
                $this->get_model();
                $field = clear_field_pre((new Db()), $this->table_name, false, null, 'atic_','atc');
//                $result_datas = $this->model->field($field)->paginate([
//                    'list_rows' =>  $condition['every_get'],    //每页获取数量
//                    'page'      =>  $condition['current_page']  //当前页码
//                ]);
                //算出总条数
                $total_arr = Db::query(''
                        . 'SELECT count(*) FROM '.$this->table_name
                        );
                //将时间戳转为常规时间格式
                $field = str_replace('atc.atic_upd_time as upd_time', 'FROM_UNIXTIME(atc.atic_upd_time) as upd_time', $field);
                
                $list_datas = Db::query(''
                        . 'SELECT '.$field.',atccf.artcf_name as cfname FROM '.$this->table_name.' atc '
                        . 'LEFT JOIN '.config("database.prefix").'article_classify atccf '
                        . 'ON (atc.atic_classify=atccf.artcf_id) '.$where.''
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
                $field = clear_field_pre((new Db()), $this->table_name, false, null, 'atic_','atc');
                $datas = Db::query(''
                        . 'SELECT '.$field.',atccf.artcf_path as cfpath FROM '.$this->table_name.' atc '
                        . 'LEFT JOIN '.config("database.prefix").'article_classify atccf '
                        . 'ON (atc.atic_classify=atccf.artcf_id) WHERE atc.atic_id = ?'
                        ,[$id]
                    );
                if( count($datas)>0 ){
                    //获取图片的图片数据
                    $field = clear_field_pre((new Db()), config("database.prefix").'article_img', false, null, 'artimg_');
                    $param = '';
                    $param_arr = explode(',', $datas[0]['file_id']);
                    foreach( $param_arr as $v ){
                        $param .= '?,';
                    }
                    $datas[0]['file_id'] = Db::query(''
                            . 'SELECT '.$field.' FROM '.config("database.prefix").'article_img '
                            . 'WHERE artimg_id IN('.rtrim($param,',').')',
                            $param_arr
                        );
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
        
        //校验file_id
        private function check_file_id($file_id){
            $pattern = '/^(([1-9]\d{0,8}|[1-9]\d{0,8},|,[1-9]\d{0,8}){1,100})$/';
            if( preg_match($pattern, $file_id) === 0 ){
                return response_tpl(2, 'file_id格式不合法(例如11,2,43,)');
            }
            return true;
        }
        
}