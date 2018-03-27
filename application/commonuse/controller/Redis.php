<?php
namespace app\commonuse\controller;
/*

 * 【功能】：redis快捷使用
 */

class Redis{
    protected $redis;


    //构造方法
    function __construct() {
        $this->redis=new \Redis(); //实例化php系统操作redis类
        $this->redis->connect(config('cache')['redis']['host'],config('cache')['redis']['port']); //连接redis服务
        $this->redis->auth(config('cache')['redis']['password']);   //使用密码登录redis服务
    }
    
    //创建一个key，并给其赋一个值
    public function kvset($key, $val, $expire=0){
        if( ($expire != 0) && ($this->redis->set($key,$val)) ){
            if( $this->redis->expire($key, $expire) ){
                return true;
            }else{
                $this->redis->delete($key);
                return false;
            }
        }else if( ($expire == 0) && ($this->redis->set($key,$val)) ){
            return true;
        }
        return false;
    }
    
    //获取set的值
    public function kvget($key){   //传入要获取的key对应的值
        return $this->redis->get($key);
    }
    
    //数值自增
    public function kvincr($key){
        return $this->redis->incr($key);
    }
    
    //删除某个key
    public function kvdelete($key){
        return $this->redis->delete($key);
    }
    
    //设置/刷新 key的失效时间
    public function setexpire($key, $expire){
        if( (int)$expire > 0 ){
            return $this->redis->expire($key, $expire);
        }
        return false;
    }
    
    //设置一个hash/更新field值
    public function sethash($key, $field, $val, $expire=0){
        if( ((int)$expire > 0) && $this->redis->hExists($key, $field) ){
            $old_val = $this->redis->hGet($key, $field);
        }
        $result = $this->redis->hSet($key, $field, $val);
        if( ($result == 0) || ($result == 1) ){
            if( $expire>0 ){
                if( $this->redis->expire($key, $expire) ){
                    return true;
                }
                if( $result == 0 ){
                    //恢复原值
                    $this->redis->hSet($key, $field, $old_val);
                    return false;
                }
                $this->redis->hDel($key, $field);   //删除刚刚新建的field
                return false;
            }
            return true;
        }
        return false;
    }
    
    //新增(或更新)批量的hash field
    /*
     * @param key [String] 键名
     * @param field [Array] field-val  示例['test01'=>'val01','test02'=>'val02']
     * @notice 注意事项：数组只能为一维数组，并且是关联数组
     */
    public function msethash($key, $arr, $expire=0){
        $result = $this->redis->hMset($key, $arr);
        if( ((int)$expire > 0) && $result ){
            $this->redis->expire($key, $expire);
        }
        return $result;
    }


    //删除指定的hash filed (单个或者批量)
    /*
     * @param key [String] 键名
     * @param field [String/Array] field 当为字符串时为单个删除，如果为数组时为批量删除
     * @notice 注意事项：field为数组时只能为一维数组，并且是索引数组
     * @return success[int] fail[boolean]  成功时返回影响条数，失败时返回false
     */
    public function hashdel($key, $field){
        if( is_array($field) && (count($field)>0) ){
            array_unshift($field, $key);    //制作动态参数
            return call_user_func_array(array($this->redis, 'hDel'), $field);   //传入动态参数
        }else if( is_string($field) && (strlen($field)>0) ){
            return $this->redis->hDel($key, $field);
        }
        return false;
    }
    
    //获取指定的hash field值
    public function hashget($key, $field){
        return $this->redis->hGet($key, $field);
    }
    
    //获取多个指定的hash field值
    /*
     * @param key [Strng] 键名
     * @param field_arr [Array] 格式为一维数组，元素为field
     */
    public function mhashget($key, $field_arr){
        return $this->redis->hMget($key, $field_arr);
    }
    
    //获取所有的hash field值
    public function hashgetall($key){
        return $this->redis->hGetAll($key);
    }
    
    //添加Set(一个或多个)
    public function set_sadd($key, $val, $expire=0){
        if( !is_array($val) ){
            if( $this->redis->sAdd($key,$val) ){
                if( $expire > 0 ){
                    $this->redis->expire($key, $expire);
                }
                return true;
            }
            return false;
        }else{
            if( $this->redis->sAddArray($key,$val) ){
                if( $expire > 0 ){
                    $this->redis->expire($key, $expire);
                }
                return true;
            }
            return false;
        }
    }
    
    //获取Set对应key的val
    public function set_smembers($key){
        return $this->redis->sMembers($key);
    }
}

