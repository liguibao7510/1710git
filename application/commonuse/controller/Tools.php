<?php
namespace app\commonuse\controller;

/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：通用工具类
 */
use think\Request;
class Tools {
    private $all_datas;


    //使用系统助手函数生成url
    public function make_url( Request $request ){
        try{
            $datas = $request->post();
            if( !empty($datas['path']) && empty($datas['param']) ){
                return response_tpl(200, '制作url成功', url($datas['path']));
            } elseif ( !empty($datas['path']) && !empty($datas['param']) ){
                if( strpos($datas['param'], '{') === true ){
                    $datas['param'] = json_decode($datas['param'], true);
                }
                return response_tpl(200, '制作url成功', url($datas['path'], $datas['param']));
            }
            return response_tpl(400, '请传入正确的参数才能制作url');
        } catch (\think\exception\ErrorException $e){
            return return_exception( $e->getMessage(), __METHOD__ );//系统异常
        }
    }
    
    
    //制作分类数据json
    public function make_classify_json(){
        $handle = fopen('./test.txt', 'a+');
        
        
        ini_set('memory_limit','1024M');
        set_time_limit(600);
        $db = new \think\Db();
        $all_datas = array();
        for($i=1;$i<=5;$i++){
            $table = $db::table('cf_area');
            $all_datas[$i] = $table->where('level','=',$i)->select();
        }
//        $this->all_datas=$all_datas;
        fwrite($handle, json_encode($all_datas));
        fclose($handle);
        exit;
        dump($all_datas);exit;
        $all_arr = $this->make_arr(1, 'code', 'parent_id', 'name');
        dump($all_arr);
        ini_set('memory_limit','128M');
    }
    
    private function make_arr($index, $id_field, $pid_field, $name_field, $pid=1){
        $all_arr = array();
        dump($pid);
        if( $pid == 1 ){
            foreach( $this->all_datas[$index] as $k=>$v ){
                $all_arr[$v[$id_field]]['id']=$v[$id_field];
                $all_arr[$v[$id_field]]['name']=$v[$name_field];
                $all_arr[$v[$id_field]]['child']=$this->make_arr($index+1, $id_field, $pid_field, $name_field, $v[$id_field]);
            }
            return $all_arr;
        }
        else {
            foreach( $this->all_datas[$index] as $k=>$v ){
                if( $v[$pid_field] == $pid ){
                    $all_arr[$v[$id_field]]['id']=$v[$id_field];
                    $all_arr[$v[$id_field]]['name']=$v[$name_field];
                    if( $index < 5 ){
                        $all_arr[$v[$id_field]]['child']=$this->make_arr($index+1, $id_field, $pid_field, $name_field, $v[$id_field]);
                    }else{
                        $all_arr[$v[$id_field]]['child']='';
                    }
                }
            }
            return $all_arr;
        }
    }
    
}

