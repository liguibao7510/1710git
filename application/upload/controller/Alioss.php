<?php
namespace app\upload\controller;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：阿里云OSS文件管理Controller类
 */
use app\commonuse\controller\Allcontroller;
use think\Request;
use app\upload\validate\AliossValidate;    //本类验证器
class Alioss extends Allcontroller{
    private $bucket;
    private $datas;
    private $model;
            
    function __construct(Request $request) {
        parent::__construct($request);
        $this->datas = $request->post();
        
        if( isset($this->datas['bucket']) ){
            $pattern = '/^[0-9a-z][0-9a-z-]{3,64}[0-9a-z]$/';  
            $str = $this->datas['bucket'];  
            if( preg_match($pattern, $str) === 0 ){
                echo json_encode(response_tpl(2, 'bucket只能含小写字母数字和短横线，小写字母和数字开头结尾，长度3-64'));exit;
            }
            $this->bucket = $this->datas['bucket'];
        }
    }



    /*
     * 增
     */
        function add(){
            try{
//                $file = $request->file();
                $file = $_FILES;
//                file_put_contents('./aa.jpg', $file['object']);
                if( $file['object']['error'] != 0 ){
                    return response_tpl(400, '本地文件上传失败');
                }
                $this->getmodel();
                return $this->model->add_m($file);
            } catch (\Exception $e){
                return return_exception($e);
            }
        }
    
    
    /*
     * 删
     */
        public function delete( Request $request, AliossValidate $aliossvalidate ){
            try{
                $datas = $request->post();  //获取请求数据
                
                if( !$aliossvalidate->scene('delete')->check($datas) ){
                    //验证不通过
                    return response_tpl(2, $aliossvalidate->getError());
                }
                $this->getmodel();
                return $this->model->delete_m(true);
            } catch (\Exception $e){
                return return_exception( $e);
            }
        }
    
    
    
    /*
     * 改
     */
        
    
    
    
    /*
     * 查
     */
    
        




    /******************************************************
     * 公用代码区
     */
        //实例化model类方法
        private function getmodel(){
            $this->model = new \app\upload\model\Alioss($this->datas);
        }
}
