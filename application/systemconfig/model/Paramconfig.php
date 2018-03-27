<?php
namespace app\systemconfig\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：参数配置管理Model类
 */
use think\Db;
class Paramconfig{
    private $table_name;
    private $model;
    private $db;
    private $redis;
    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'system_config';
    }
    
    
    /*
     * 增
     */
        public function add_m($datas){
            try{
                Db::startTrans();   //开启事务
                $effects = Db::execute(
                        'INSERT INTO '
                        . $this->table_name.' '
                        . '(syscfg_name,syscfg_flag,syscfg_type,syscfg_value,syscfg_description,syscfg_indexno) '
                        . 'VALUES (?,?,?,?,?,?)',
                        [
                            $datas['name'],$datas['flag'],2,htmlspecialchars($datas['value']),
                            $datas['description'],$datas['indexno']
                        ]
                    );
                if( $effects>0 ){
                    //将配置信息同步到redis
                    $this->get_redis();
                    if( $this->redis->sethash( config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['system_config'],
                            $datas['flag'],$datas['value'] ) !== true ){
                        Db::rollback(); //事务回滚
                        return response_tpl(400, 'redis缓存同步失败');
                    }
                    Db::commit();   //提交事务
                    return response_tpl(200, '新增成功',Db::getLastInsID());
                }
                Db::rollback(); //事务回滚
                return response_tpl(400, '新增失败，请稍后再试');
            } catch (\Exception $e) {
                Db::rollback(); //事务回滚
                return return_exception($e);//系统异常
            }
        }


    /*
     * 删
     */
        public function delete_m($datas){
            try{
                Db::startTrans();
                $del_result = Db::execute(
                        'DELETE FROM '
                        . $this->table_name.' '
                        . 'WHERE syscfg_id = ? AND syscfg_flag = ? AND syscfg_type = ?',
                        [$datas['id'],$datas['flag'],2]);
                if(  $del_result > 0 ){
                    //删除redis的数据
                    $this->get_redis();
                    if( $this->redis->hashdel( config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['system_config'],
                            $datas['flag'] ) === false ){
                        Db::rollback(); //事务回滚
                        return response_tpl(400, 'redis缓存删除失败');
                    }
                    Db::commit();   //提交事务
                    return response_tpl(200, '删除数据成功');
                }
                Db::rollback(); //事务回滚
                return response_tpl(400, '删除失败,请稍后再试');
            } catch (\Exception $e) {
                Db::rollback(); //事务回滚
                return return_exception( $e);//系统异常
            }
        } 
    
    
    /*
     * 改
     */
        public function update_m($datas){
            try{
                Db::startTrans();   //开启事务
                if( $datas['type'] == 1 ){
                    $effects = Db::execute(
                            'UPDATE '. $this->table_name.' '
                            . 'SET syscfg_value = ? '
                            . 'WHERE syscfg_flag = ?',
                            [htmlspecialchars($datas['value']),$datas['flag']]
                        );
                }else if( $datas['type'] == 2 ){
                    $effects = Db::execute(
                            'UPDATE '. $this->table_name.' '
                            . 'SET syscfg_name = ? ,syscfg_flag = ? ,syscfg_value = ?,syscfg_description = ?,syscfg_indexno = ? '
                            . 'WHERE syscfg_id = ?',
                            [
                                $datas['name'],$datas['flag'],htmlspecialchars($datas['value']),
                                $datas['description'],$datas['indexno'],$datas['id']
                            ]
                        );
                }
                if( $effects>0 ){
                    //将配置信息同步到redis
                    $this->get_redis();
                    if( $this->redis->sethash( config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['system_config'],
                            $datas['flag'],$datas['value'] ) === false ){
                        Db::rollback(); //事务回滚
                        return response_tpl(400, 'redis缓存同步失败');
                    }
                    Db::commit();   //提交事务
                    return response_tpl(200, '更新数据成功',Db::getLastInsID());    //新增成功返回最新添加的id
                }
                Db::rollback(); //事务回滚
                return response_tpl(400, '更新失败，请稍后再试');
            } catch (\Exception $e) {
                Db::rollback(); //事务回滚
                return return_exception( $e);//系统异常
            }
        }
    
    /*
     * 查
     */
        
        //获取系统配置列表
        public function get_data_list(){
            try{
                $field = clear_field_pre(new Db(), $this->table_name, true, null, 'syscfg_');
                $fixed_param = Db::query(
                        'SELECT '.$field.' FROM '.$this->table_name.' '
                        . 'WHERE syscfg_type = 1 ORDER BY syscfg_indexno'
                    );
                $freed_param = Db::query(
                        'SELECT '.$field.' FROM '.$this->table_name.' '
                        . 'WHERE syscfg_type = 2 ORDER BY syscfg_indexno'
                    );
                $datas = [
                    'fixed_param'   => $fixed_param,
                    'freed_param'   => $freed_param
                ];
                return response_tpl(200, '获取数据成功', $datas);
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