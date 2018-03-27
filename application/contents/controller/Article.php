<?php
namespace app\contents\controller;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：文章管理Controller类
 */
use app\commonuse\controller\Allcontroller;
use think\Request;
use app\contents\validate\ArticleValidate;    //本类验证器
class Article extends Allcontroller{
    
    private $model; //用户初始化model对象的变量
    
    
    /*
     * 增
     */
        //新增文章页面
        public function add( Request $request ){
            try{
               return view();   //模板
            } catch (\think\exception\TemplateNotFoundException $e){
                return return_exception($e);
            }
        }
        
        //新增文章数据
        public function addDatas( Request $request, ArticleValidate $articlevalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                //设置默认值
                $datas['province'] = $request->post('province',0);
                $datas['state_city'] = $request->post('state_city',0);
                $datas['area_county'] = $request->post('area_county',0);
                $datas['town'] = $request->post('town',0);
                $datas['village'] = $request->post('village',0);
                
                if( !$articlevalidate->scene('add')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $articlevalidate->getError());
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
        public function delete( Request $request, ArticleValidate $articlevalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                
                if( !$articlevalidate->scene('delete')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $articlevalidate->getError());
                }
                
                $this->getmodel();  //获取model实例
                return $this->model->delete_m( $datas['id'] );
            } catch (\Exception $e){
                return return_exception($e);
            }
        }
    
    
    
    /*
     * 改
     */
        //模板
        public function update(){
            try{
               return view();   //模板
            } catch (\think\exception\TemplateNotFoundException $e){
                return return_exception($e);
            }
        }
        
        //数据
        public function updatedatas( Request $request, ArticleValidate $articlevalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                //设置默认值
                $datas['cover'] = $request->post('cover','');
                $datas['province'] = $request->post('province',0);
                $datas['state_city'] = $request->post('state_city',0);
                $datas['area_county'] = $request->post('area_county',0);
                $datas['town'] = $request->post('town',0);
                $datas['village'] = $request->post('village',0);
                
                if( !$articlevalidate->scene('edit')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $articlevalidate->getError());
                }
                
                $this->getmodel();  //获取model实例
                return $this->model->update_m( $datas );
            } catch (\Exception $e){
                return return_exception( $e);
            }
        }
        
        //切换审核状态
        public function changeCheck( Request $request, ArticleValidate $articlevalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                
                if( !$articlevalidate->scene('check')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $articlevalidate->getError());
                }
                
                $this->getmodel();  //获取model实例
                return $this->model->changeCheckStatus( $datas );
            } catch (\Exception $e){
                return return_exception( $e);
            }
        }


        /*
     * 查
     */
        
        //文章列表显示模板
        public function showlist(){
            return view();   //模板
        }
        
        //获取文章数据列表
        public function list_datas( Request $request, ArticleValidate $articlevalidate ){
            try{
                $request_datas = $request->post();  //获取请求数据

                if( !$articlevalidate->scene('select')->check($request_datas) ){
                    //验证不通过
                    return response_tpl(2, $articlevalidate->getError());
                }
                $this->getmodel();  //获取model实例
                return $this->model->get_data_list($request_datas);   //将数据返回模板(系统默认json)
            } catch ( \Exception $e ) {
                return return_exception($e);//系统异常
            }
        }
        
        //查询一条文章数据
        public function getOne( Request $request, ArticleValidate $articlevalidate ){
            try{
                $request_datas = $request->post();  //获取请求数据

                if( !$articlevalidate->scene('select_one')->check($request_datas) ){
                    //验证不通过
                    return response_tpl(2, $articlevalidate->getError());
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
            $this->model = new \app\contents\model\Article();
        }
}
