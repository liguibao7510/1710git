<?php
namespace app\contents\controller;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：网站地图管理Controller类
 */
use app\commonuse\controller\Allcontroller;
use think\Request;
use app\contents\validate\WebmapValidate;    //本类验证器
class Webmap extends Allcontroller{
    
    private $model; //用户初始化model对象的变量
    
    
    /*
     * 增
     */
        public function add( Request $request, WebmapValidate $webmapvalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                
                if( !$webmapvalidate->scene('add')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $webmapvalidate->getError());
                }
                
                $this->getmodel();  //获取model实例
                return $this->model->add_m( $datas );
            } catch (\Exception $e){
                return return_exception($e);
            }
        }
    
    
    /*
     * 删
     */
        public function delete( Request $request, WebmapValidate $webmapvalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                
                if( !$webmapvalidate->scene('delete')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $webmapvalidate->getError());
                }
                
                $this->getmodel();  //获取model实例
                return $this->model->delete_m( $datas['id'] );
            } catch (\Exception $e){
                return return_exception( $e);
            }
        }
    
    
    
    /*
     * 改
     */
        public function update( Request $request, WebmapValidate $webmapvalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                
                if( !$webmapvalidate->scene('edit')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $webmapvalidate->getError());
                }
                
                $this->getmodel();  //获取model实例
                return $this->model->update_m( $datas );
            } catch (\Exception $e){
                return return_exception( $e);
            }
        }
    
    
    
    /*
     * 查
     */
    
        public function showlist(){
            $this->getmodel();
            $datas = $this->model->get_data_list();
            if( $datas['res_code']==500 ){
                dump($datas);exit;
            }
//            dump(view('',$datas['datas']));
            return view('',$datas['datas']);
        }




    /******************************************************
     * 公用代码区
     */
        //实例化model类方法
        private function getmodel(){
            $this->model = new \app\contents\model\Webmap();
        }
}
