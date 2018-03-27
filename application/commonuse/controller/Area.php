<?php
namespace app\commonuse\controller;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：地区管理Controller类
 */
use think\Request;
use app\commonuse\validate\AreaValidate;    //本类验证器
class Area{
    private $model; //用户初始化model对象的变量


    /*
     * 增
     */
        public function add( Request $request, AreaValidate $adminsvalidate ){
            
        }
    
    
    /*
     * 删
     */
        public function delete( Request $request, AreaValidate $adminsvalidate ){
            
        }
    
    
    /*
     * 改
     */
        public function update( Request $request, AreaValidate $adminsvalidate ){
            
        }
    
    /*
     * 查
     */
        
        
        //根据父级地区获取数据
        public function get_datas_by_parent(Request $request, AreaValidate $areavalidate){
            try{
                $condition = $request->post();  //获取请求数据

                if( !$areavalidate->scene('select')->check($condition) ){
                    //验证不通过
                    return response_tpl(2, $areavalidate->getError());
                }
                $this->getmodel();  //获取model实例
                return $this->model->get_datas_by_pid( $condition['pid'] );   //将数据返回模板(系统默认json)
            } catch (\think\exception\ErrorException $e){
                return return_exception( $e->getMessage(), __METHOD__ );//系统异常
            }
        }








        /******************************************************
     * 公用代码区
     */
        //实例化model类方法
        private function getmodel(){
            $this->model = new \app\commonuse\model\Area();
        }
}
