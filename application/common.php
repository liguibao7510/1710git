<?php
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：公共应用函数
 */

// 应用公共文件

//返回数据模板
function response_tpl($res_code, $msg, $datas=null){
    return array(
        'res_code'  =>  $res_code,
        'msg'       =>  $msg,
        'datas'     =>  $datas
    );
}

//写入方法
function mywrite($str,$path="./test.txt",$model='a+'){
    $fp= fopen($path, $model);
    fwrite($fp, $str);
    fclose($fp);
}

//加盐方法
function salt($string ,$type){ //参数，传入被加密字串,sys/api
    return md5( $string.($type=='api'?config('API_TOKEN'):config('SYSTEM_TOKEN')) );
}

//系统异常返回消息模板
/*
 * @param e [Exception] 系统异常对象
 * @param path [String] 发生错误的程序方法
 * @param selfdefine_exp_msg [String] 异常错误信息
 * @return [Array]
 */
function return_exception($e, $selfdefine_exp_msg=null){
    if( $selfdefine_exp_msg!=null ){
        $exp_msg = $selfdefine_exp_msg;
    }else{
        $exp_msg = '[文件:'.$e->getFile().'][行数:'.$e->getLine().'][信息:'.$e->getMessage().']';
    }
    //如果为运营阶段，异常会写入日志文件
    if( config('IS_SHOW_SYSTEM_EXP') === false ){
        recored_system_log($exp_msg);
    }
    return array('res_code'=>500, 'msg'=>'系统异常', 'exp_msg' => (config('IS_SHOW_SYSTEM_EXP') ? $exp_msg : '查看详细异常信息请到配置文件开启权限'));
}

//添加前缀
function add_prefix($arr, $prefix){
    $prefix_arr=array();
    foreach($arr as $k=>$v){
        $prefix_arr[$prefix.$k]=$v;
    }
    return $prefix_arr;
}

//去掉字段前缀
/*
 * @param $db [Db] 系统db类
 * @param $is_choose_those [boolean] true为选择字段，false为排除字段
 * @param $filed_select [Array] 字段名称数组
 * @param $field_pre [String] 字段前缀
 * @param $table_alias [String] 数据库表别名
 * @return [String] 返回tp方法field需要传入的字符串参数(这里屏蔽了tp第二个参数true，即不能再使用field('???',true)形式，否则可能会出错)
 */
function clear_field_pre($db, $table_name, $is_choose_those, $filed_select, $field_pre, $table_alias=null){
//    try{
        $field = '';
        $colums_info = $db::query('SHOW COLUMNS FROM `'.$table_name.'`');   //显示该表所有字段
    
        foreach( $colums_info as $v ){
            //全部查询
            if( $filed_select == null ){
                if( $table_alias === null ){
                    $field.= $v['Field'].' as '.str_replace($field_pre, '', $v['Field']).',';
                }else{
                    $field.= $table_alias.'.'.$v['Field'].' as '.str_replace($field_pre, '', $v['Field']).',';
                }
            }
            //选择字段
            else if( $is_choose_those === true && in_array($v['Field'], $filed_select) ){
                if( $table_alias === null ){
                    $field.= $v['Field'].' as '.str_replace($field_pre, '', $v['Field']).',';
                }else{
                    $field.= $table_alias.'.'.$v['Field'].' as '.str_replace($field_pre, '', $v['Field']).',';
                }
            }
            //排除字段
            else if( $is_choose_those === false && !in_array($v['Field'], $filed_select) ){
                if( $table_alias === null ){
                    $field.= $v['Field'].' as '.str_replace($field_pre, '', $v['Field']).',';
                }else{
                    $field.= $table_alias.'.'.$v['Field'].' as '.str_replace($field_pre, '', $v['Field']).',';
                }
            }
        }
        return rtrim($field, ',');    //去除多余逗号
//    } catch (\Exception $e){
//        return return_exception($e);  //出现异常返回异常信息
//    }
}

//判断数组是否为一维数组
function is_one_array($arr){
    if( !is_array($arr) || count($arr)==0 ){
        return 403;
    }
    return is_array(reset($arr)) ? false : true;
}

//制作加密id
function make_id_key($id, $uid, $uid_filed, $action_id_filed, $out_time_field, $config_c, $token_field){
    $id_key=md5($id.$uid.time().rand(1000, 9999).config('ID_KEY_TOKEN'));
    return array($uid_filed=>$uid,$action_id_filed=>$id,$out_time_field=>(time()+$config_c),$token_field=>$id_key);
}

//将制作好的数据新增到加密表(单条)
function add_id_key_one($datas, $db_name, $token_field){
    try{
        return M($db_name)->add($datas)?$datas[$token_field]:returns(500, '加密数据新增失败');
    } catch (\Exception $e){
        throw new Exception($e);
    }
}

//将制作好的数据新增到加密表(多条)
/*
 * @param $old_datas_id_key_name一般是主键字段去掉前缀后后的
 */
function add_id_keys($datas, $old_datas, $db_name, $old_datas_id_key_name, $token_field){
    foreach($old_datas as $k=>$v){
        $old_datas[$k][$old_datas_id_key_name]=$datas[$k][$token_field];
    }
    try{
        if(M($db_name)->addAll($datas)){
            return returns(200, '获取数据成功', $old_datas);
        }
    }catch(\Exception $e){
        return return_exception($e);
    }
    return returns(500, '加密数据新增失败');
}

//通过key_id找到对应的id
function get_id_by_key($uid, $token, $db_name, $uid_filed, $token_field, $id_filed, $true_id_field, $out_time_field){
    try {
        $info=M($db_name)->field($id_filed.','.$true_id_field.','.$out_time_field)->where(array($uid_filed=>$uid,$token_field=>$token))->find();
    } catch (\Exception $e) {
        return return_exception($e);
    }
    if(!$info){
        return returns(404, '该资源已经过期或者不存在');
    }
    if( $info && ($info[$out_time_field]!=0 && $info[$out_time_field]<=time()) ){
        //物理删除过期资源
        try{
            M($db_name)->where(array($id_filed=>$info[$id_filed]))->delete();
        }catch (\Exception $e){
            //进行系统日志记录
            recored_system_log(date('Y-m-d/H:i:s'),'过期加密数据删除时出现异常'.$e->getMessage(),'返回最新加密数据，未成功删除数据待处理(还有机会被删除)');
            return $info[$true_id_field];
        }
        return returns(404, '该资源已经过期或者不存在');
    }  
    return $info[$true_id_field];
}

//记录系统日志
/*
 * param cause 记录发生错误原因
 */
function recored_system_log($cause){
    if(!$cause){
        return;
    }
    trace('['.date('Y-m-d H:i:s').']'.'['.$cause.']', 'my_error');
}


//删除指定数组元素
/*
 * @param arr 目标数组，即元素将要被部分删除的数组
 * @param tpl_arr 参考数组(一维数组，目标数组下标匹配此数组元素都建会被删除掉)
 */
function unset_designated_eliment($arr, $tpl_arr){
    foreach ($tpl_arr as $v){
        unset($arr[$v]);
    }
    return $arr;
}


//表单允许提交字段校验
/*
 * @param request_datas [Array] 表单数据
 * @param $allow_fileds [Array] 过滤参考数组
 * @return request_datas [Array] 整理过后的request_datas
 */
function allow_request_field( $request_datas, $allow_fileds ){
    //限制个数异常(3倍)
    if( count($request_datas) > (count($allow_fileds)*3) ){
        return false;
    }
    $new_arr = array();
    foreach( $request_datas as $k=>$v ){
        if( !in_array($k, $allow_fileds) ){
            $new_arr[] = $k;
        }
    }
    //干掉非法字段
    if( count($new_arr) > 0 ){
        foreach( $new_arr as $v ){
            unset($request_datas[$v]);
        }
    }
    return $request_datas;
}


//制作token
function make_token( $username ){
    return md5($username.str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.+-*/?,.<>~!@#$%^&*()_;:\'"\\'));
}