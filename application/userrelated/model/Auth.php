<?php
namespace app\userrelated\model;
/*
 * 【功能】：权限管理Model类
 */
use think\Db;
class Auth{
    private $table_name;
    private $model;
    private $db;
    private $redis;

    //构造函数
    function __construct() {
        $this->table_name = config("database.prefix").'auth';
    }

    //增
        public function add_m($datas){
            try{
                $datas['pid']=$datas['pid2']?$datas['pid2']:$datas['pid1'];

                $check_exists = $this->check_exists($datas['model'], $datas['controller'], $datas['action']);
                if( $check_exists['res_code'] != 404 ){
                    return $check_exists;
                }

                Db::startTrans();    //开启事务
                //执行插入操作
                $insert_result = Db::execute(
                        'INSERT INTO '.$this->table_name.' '
                        . '(auth_name,auth_pid,auth_controller,auth_action,auth_model,auth_indexno) VALUES (?,?,?,?,?,?)',
                        [
                            $datas['name'], $datas['pid'],
                            strtolower($datas['controller']), strtolower($datas['action']),
                            strtolower($datas['model']), $datas['indexno']
                        ]
                    );
                if( $insert_result == 0 ){
                    return response_tpl(400, '新增失败，请稍后再试');
                    Db::commit();  //事务提交
                }
                $newest_id = Db::getLastInsID();
                //进行二次维护
                $update_result = $this->after_insert($newest_id, $datas['pid']);
                if( $update_result['res_code'] === 200 ){
                    Db::commit();  //事务提交
                    $datas['id'] = $update_result['datas']['id'];
                    $datas['level'] = $update_result['datas']['level'];
                    return response_tpl(200, '新增数据成功', $datas);
                }
                Db::rollback();  //数据回滚
                return $update_result;
            } catch (\Exception $e){
                Db::rollback();  //事务回滚
                return return_exception($e);
            }
        }

        //分配权限
        public function give_auth($datas){
            try{
                $datas['auth_ids'] = trim($datas['auth_ids'],',');
                Db::startTrans();   //开启事务
                if( $datas['type'] == 'role' ){
                    $effects = Db::execute(
                            'INSERT INTO '
                            . config("database.prefix").'rlinka '
                            . '(rka_rid,rka_aids) VALUES (?,?) ON DUPLICATE KEY UPDATE rka_aids = ?',
                            [$datas['id'],$datas['auth_ids'],$datas['auth_ids']]
                        );
                }else{
                    $effects = Db::execute(
                            'INSERT INTO '
                            . config("database.prefix").'user_auth '
                            . '(urath_uid,urath_aids) VALUES (?,?) ON DUPLICATE KEY UPDATE urath_aids = ?',
                            [$datas['id'],$datas['auth_ids'],$datas['auth_ids']]
                        );
                }
                if( $effects>0 ){
                    //将权限同步到redis
                    $auth_ids_arr = explode(',', $datas['auth_ids']);
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
                    if( $datas['type'] == 'role' ){
                        $add_redis_result = $this->redis->set_sadd(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['role_auth'].$datas['id'], $sadd_arr);
                    }else{
                        $add_redis_result = $this->redis->set_sadd(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['adminer_auth'].$datas['id'], $sadd_arr);
                    }
                    if( $add_redis_result ){
                        Db::commit(); //事务提交
                        return response_tpl(200, '权限分配且同步成功');
                    }
                    Db::rollback(); //事务回滚
                    return response_tpl(201, '权限分配成功但是同步失败');
                }
                Db::commit(); //事务提交
                return response_tpl(400, '权限分配失败，请稍后再试');
            } catch (\Exception $e) {
                Db::rollback(); //事务回滚
                return return_exception($e);//系统异常
            }
        }


    //删
        public function delete_m($auth_ids){
            try{
                $auth_ids = trim($auth_ids, ',');
                $auth_ids_arr = explode(',', $auth_ids);
                //判断其是否存在下级，如果存在禁止操作
                $has_child = Db::query(
                        'SELECT auth_pid FROM '.$this->table_name. ' '
                        . 'WHERE auth_pid IN (?)',[$auth_ids]
                    );
                $has_child = array_column($has_child, 'auth_pid');  //转为一维数组
                $condition = '';
                $auth_ids_arr_tmp = [];
                foreach( $auth_ids_arr as $v ){
                    if( !in_array($v, $has_child) ){
                        $condition .= '?,';
                        $auth_ids_arr_tmp[] = $v;
                    }
                }
                if( count($auth_ids_arr_tmp) === 0 ){
                    return response_tpl(400, '没有数据被删除');
                }
                $result = Db::execute(
                        'DELETE FROM '.$this->table_name.' '
                        . 'WHERE auth_id IN ('. trim($condition, ',').')',$auth_ids_arr_tmp
                    );
                if( $result > 0 ){
                    return response_tpl(200, '删除操作成功');
                }
                return response_tpl(400, '删除操作失败');
            }catch(\Exception $e){
                return return_exception($e);
            }
        }
    //改
        public function update_m($datas){
            try{
                $datas['pid'] = $datas['pid2'] > 0 ? $datas['pid2'] : $datas['pid1'];

                $check_exists = $this->check_exists($datas['model'], $datas['controller'], $datas['action'], $datas['id']);
                if( $check_exists['res_code'] != 404 ){
                    return $check_exists;
                }

                $old_datas = Db::query(
                        'SELECT * FROM '.$this->table_name.' '
                        . 'WHERE auth_id = ?',[$datas['id']]
                    );    //获取旧数据
                if( count($old_datas) == 0 ){
                    return response_tpl(404,'编辑的数据不存在');    //该地区存在下级，禁止移动
                }
                //更改位置
                $is_change_path = false;
                if( $old_datas[0]['auth_pid']!=$datas['pid'] ){
                    //判断其是否存在下级，如果存在禁止操作
                    $check_result = Db::query(
                            'SELECT count(*) FROM '.$this->table_name.' '
                            . 'WHERE auth_pid = ?',[$datas['id']]
                        );
                    if( $check_result[0]['count(*)'] > 0 ){
                        return response_tpl(403,'该权限存在下级，禁止移动其位置');    //该地区存在下级，禁止移动
                    }

                    //禁止选择自己作为上级
                    if( $datas['pid']==$old_datas[0]['auth_id'] ){
                        return response_tpl(403,'你不能将自己作为自己的上级');    //不能将自己作为自己的上级
                    }
                    $is_change_path=true;
                }
                Db::startTrans();    //开启事务
                //执行更新操作
                $upd_result = Db::execute(
                        'UPDATE '.$this->table_name.' '
                        . 'SET auth_name = ?,auth_pid = ?,auth_controller = ?,auth_action = ?,auth_model = ?, auth_indexno = ? '
                        . 'WHERE auth_id = ?',
                        [
                            $datas['name'], $datas['pid'],
                            strtolower($datas['controller']), strtolower($datas['action']),
                            strtolower($datas['model']), $datas['indexno'], $datas['id']
                        ]
                    );
                if( ($upd_result > 0) && ($is_change_path === true) ){   //基本字段更新成功且存在移动位置
                    $update_result = $this->after_insert($datas['id'], $datas['pid']);
                    if( $update_result['res_code'] === 200 ){
                        Db::commit();  //事务提交
                        $datas['id'] = $update_result['datas']['id'];
                        $datas['level'] = $update_result['datas']['level'];
                        $datas['change_pid'] = 1;   //切换了父级id
                        return response_tpl(200, '更新数据成功', $datas);
                    }
                    Db::rollback();    //更新失败进行数据回滚
                    return $update_result;
                }else if( ($upd_result > 0) && ($is_change_path === false) ){ //更新但不移动位置
                    Db::commit();  //事务提交
                    $datas['change_pid'] = 0;   //切换了父级id
                    return response_tpl(200, '更新数据成功', $datas);    //更新操作成功！
                }else if( $upd_result == 0 ){
                    Db::commit();  //事务提交
                    return response_tpl(400, '更新数据失败');    //更新操作失败！
                }
            } catch (\Exception $e){
                Db::rollback();  //事务回滚
                return return_exception($e);
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
                            'SELECT auth_path FROM '.$this->table_name.' '
                            . 'WHERE auth_id = ?',
                            [$pid]
                        );

                    $parent_path=$parent_path[0]['auth_path']."-".$newest_id;    //得到其全路径
                }

                $auth_level= substr_count($parent_path, "-");   //根据全路径的"-"个数维护其水平
                //执行更新操作
                $update_result = Db::execute(
                        'UPDATE '.$this->table_name.' '
                        . 'SET auth_path = ?,auth_level=? WHERE auth_id = ?',
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

    //查
        //获取列表数据
        public function get_data_list(){
            try{

                $field = clear_field_pre(new Db(), $this->table_name, true, null, 'auth_');

                $model = Db::query(
                        'SELECT '.$field.' FROM '.$this->table_name.' '
                        . 'WHERE auth_level = 0 ORDER BY auth_indexno'
                    );
                if( count($model) == 0 ){
                    return response_tpl(404, '没有数据');
                }
                $controller = Db::query(
                        'SELECT '.$field.' FROM '.$this->table_name.' '
                        . 'WHERE auth_level = 1 ORDER BY auth_indexno'
                    );
                $have_controller = false;
                $have_action = false;
                if( count($controller) > 0 ){
                    $action = Db::query(
                            'SELECT '.$field.' FROM '.$this->table_name.' '
                            . 'WHERE auth_level = 2 ORDER BY auth_indexno'
                        );
                    if( count($action) > 0 ){
                        $have_action = true;
                    }
                    $have_controller = true;
                }
                foreach( $model as $k=>$v ){
                    if( $have_controller === true ){
                        $model[$k]['child'] = [];
                        foreach( $controller as $kk=>$vv ){
                            if( $v['id'] == $vv['pid'] ){
                                if( $have_action === true ){
                                    $controller[$kk]['child'] = [];
                                    foreach( $action as $kkk=>$vvv ){
                                        if( $vv['id'] == $vvv['pid'] ){
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

        //检测数据是否已经存在
        public function check_exists($model, $controller, $action, $id=null){
            try{
                if( $id === null ){
                    $check_result = Db::query(
                            'SELECT count(*) FROM '.$this->table_name.' '
                            . 'WHERE auth_model = ? AND auth_controller = ? AND auth_action = ?',
                            [$model,$controller,$action]
                        );
                }else{
                    $check_result = Db::query(
                            'SELECT count(*) FROM '.$this->table_name.' '
                            . 'WHERE auth_id != ? AND auth_model = ? AND auth_controller = ? AND auth_action = ?',
                            [$id, $model,$controller,$action]
                        );
                }
                if( $check_result[0]['count(*)'] == 0 ){
                    return response_tpl(404, '数据不存在');
                }
                return response_tpl(600, '数据已经存在');
            } catch (\Exception $e) {
                return return_exception( $e);//系统异常
            }
        }


    /******************************************************
     * 公用代码区
     */
        //获取redis
        private function get_redis(){
            $this->redis =new \app\commonuse\controller\Redis();
        }

}

