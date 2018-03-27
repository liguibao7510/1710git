<?php
namespace app\upload\model;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：阿里云OSS文件管理Model类
 */
use OSS\OssClient;
use OSS\Core\OssException;
use app\contents\model\Articleimg;
require VENDOR_PATH.'aliyun_oss/autoload.php';
class Alioss{
    private $datas;
    private $ossClient;
    private $redis;
    private $bucket;
    
    function __construct($datas) {
        $this->redis = new \app\commonuse\controller\Redis();
        $oss_conf_info = $this->redis->hashget(config('cache')['redis']['prefix'].config('cache')['redis']['extprefix']['system_config'], 'alioss');
        $oss_conf_info = json_decode($oss_conf_info, true);
        $this->ossClient = new OssClient($oss_conf_info['OSS_ACCESS_ID'], $oss_conf_info['OSS_ACCESS_KEY'], $oss_conf_info['OSS_ENDPOINT']);
        $this->datas = $datas;
        if( isset($this->datas['bucket']) ){
            $this->bucket = $this->datas['bucket'];
        }else{
            $this->bucket = $oss_conf_info['OSS_TEST_BUCKET'];
        }
    }



    /*
     * 增
     * 
     */
        function add_m($file){
            try{
                $content = file_get_contents($file['object']['tmp_name']);
                $object = uniqid(date('Ymd')). substr($file['object']['name'], strrpos($file['object']['name'], '.'));
                $r = $this->ossClient->putObject($this->bucket, $object, $content);
                if( $r['info']['http_code'] == 200 ){
                    //将数据同步到图片管理表
                    $add_img = (new Articleimg())->add_m($r['info']['url']);
                    if( $add_img['res_code'] === 200 ){
                        $r['info']['id'] = $add_img['datas'];
                        return response_tpl(200, '文件上传成功', $r['info']);
                    }else{
                        return $add_img;
                    }
                }
                return response_tpl(400, '文件上传失败', $r['info']);
            } catch (\Exception $e){
                if( isset($r['info']['http_code']) && ($r['info']['http_code'] == 200) ){
                    $delete_result = $this->ossClient->deleteObject($this->bucket, substr($r['info']['url'], strrpos($r['info']['url'], '/')+1));
                    if( $delete_result['info']['http_code'] != 204 ){
                        return return_exception(null, '出现异常并且尝试删除已上传文件失败,请手动到阿里云删除对应的文件'.$object);
                    }
                }
                return return_exception($e);
            } catch (OssException $e){
                return return_exception($e);
            }
        }
    
    
    /*
     * 删
     */
        //删除单个文件[单个object]
        /*
         * @param $del_img_table_datas 是否要进行删除图片表数据
         * @param 构造函数参数datas为一维数组，包含id[可选],img_url键值
         */
        public function delete_m( $del_img_table_datas = false ){
            try{
                if( $del_img_table_datas === true ){ 
                    $del_img_data = (new Articleimg())->delete_m($this->datas['id']); //注意，若删除成功此方法要进行事务处理
                    if( $del_img_data['res_code'] !== 200 ){
                        return $del_img_data;
                    }
                }
                try{
                    $delete_result = $this->ossClient->deleteObject($this->bucket, substr($this->datas['img_url'], strrpos($this->datas['img_url'], '/')+1));
                    if( $delete_result['info']['http_code'] == 204 ){
                        if( $del_img_table_datas === true ){
                            \think\Db::commit();
                        }
                        return response_tpl(200, '图片与数据和删除成功');
                    }
                    if( $del_img_table_datas === true ){
                        \think\Db::rollback();
                    }
                    return response_tpl(400, '图片删除失败');
                } catch (OssException $e){
                    if( $del_img_table_datas === true ){
                        \think\Db::rollback();
                    }
                    return return_exception($e);
                }
            } catch (\Exception $e){
                return return_exception( $e);
            }
        }
        
        //删除多个文件[多个object]
        /*
         * @param $del_img_table_datas 是否要进行删除图片表数据
         * @param 构造函数参数datas为二维数组，包含ids[一维数组][可选],img_urls[一维数组][可选]键值
         */
        public function deletes( $del_img_table_datas = false ){
            try{
                if( $del_img_table_datas === true ){ 
                    $del_img_data = (new Articleimg())->deletes($this->datas['ids']); //注意，若删除成功此方法要进行事务处理
                    if( $del_img_data['res_code'] !== 200 ){
                        return $del_img_data;
                    }
                }
                try{
                    //提取文件名object
                    foreach( $this->datas['img_urls'] as $k => $v ){
                        $this->datas['img_urls'][$k] = substr($v, strrpos($v, '/')+1);
                    }
                    $this->ossClient->deleteObjects($this->bucket, $this->datas['img_urls']);
                    return response_tpl(200, '已执行文件删除操作，且无错误');
                } catch (OssException $e){
                    if( $del_img_table_datas === true ){
                        \think\Db::rollback();
                    }
                    return return_exception($e);
                } catch (\Exception $e){
                    if( $del_img_table_datas === true ){
                        \think\Db::rollback();
                    }
                    return return_exception($e);
                }
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
        
}
