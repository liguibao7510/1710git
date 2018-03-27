<?php
namespace app\contents\controller;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：文章接口Controller类
 */
use think\Request;
use app\contents\validate\ApiArticleValidate;    //本类验证器
class Apiarticle{
    
    private $model; //用户初始化model对象的变量
    
    
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
        
        //获取文章数据列表
        public function listDatas( Request $request, ApiArticleValidate $apiarticlevalidate ){
            try{
                $request_datas = $request->post();  //获取请求数据

                if( !$apiarticlevalidate->scene('select')->check($request_datas) ){
                    //验证不通过
                    return response_tpl(2, $apiarticlevalidate->getError());
                }
                $this->getmodel();  //获取model实例
                return $this->model->getDataList($request_datas);   //将数据返回模板(系统默认json)
            } catch ( \Exception $e ) {
                return return_exception($e);//系统异常
            }
        }
        
        //查询一条文章数据
        public function getOne( Request $request, ApiArticleValidate $apiarticlevalidate ){
            try{
                $request_datas = $request->post();  //获取请求数据

                if( !$apiarticlevalidate->scene('select_one')->check($request_datas) ){
                    //验证不通过
                    return response_tpl(2, $apiarticlevalidate->getError());
                }
                $this->getmodel();  //获取model实例
                return $this->model->getOneData($request_datas['id']);   //将数据返回模板(系统默认json)
            } catch (\Exception $e) {
                return return_exception($e);//系统异常
            }
        }



    /******************************************************
     * 公用代码区
     */
        //实例化model类方法
        private function getmodel(){
            $this->model = new \app\contents\model\Apiarticle();
        }
}
