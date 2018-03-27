<?php
namespace app\userrelated\controller;

use app\commonuse\controller\Allcontroller;
use think\Request;
use app\userrelated\validate\DepartmentValidate;    //本类验证器
class Department extends Allcontroller{
    
    private $model; //用户初始化model对象的变量
    
    
    /*
     * 增
     */
        public function add( Request $request, DepartmentValidate $departmentvalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                
                if( !$departmentvalidate->scene('add')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $departmentvalidate->getError());
                }
                
                $this->getmodel();  //获取model实例
                return $this->model->add_m( $datas );
            } catch (\Exception $e){
                return return_exception( $e);
            }
        }
    
    
    
    /*
     * 删
     */
        public function delete( Request $request, DepartmentValidate $departmentvalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                
                if( !$departmentvalidate->scene('delete')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $departmentvalidate->getError());
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
        public function update( Request $request, DepartmentValidate $departmentvalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                
                if( !$departmentvalidate->scene('edit')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $departmentvalidate->getError());
                }
                
                //允许提交表单校验
                $allow_fields = ['id','name','code','indexno','description'];
                $datas = allow_request_field($datas, $allow_fields);
                if( $datas === false ){
                    return response_tpl(2, '请求参数过多');
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
        //获取部门列表
        public function showlist(){
            $this->getmodel();
            $datas = $this->model->get_data_list();
            if( $datas['res_code']==500 ){
                dump($datas);exit;
            }
            return view('',$datas['datas']);
        }



    /******************************************************
     * 公用代码区
     */
        //实例化model类方法
        private function getmodel(){
            $this->model = new \app\userrelated\model\Department();
        }
}
