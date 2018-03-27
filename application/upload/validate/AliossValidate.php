<?php
namespace app\upload\validate;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：阿里云OSS文件管理验证器
 */
use think\Validate;
class AliossValidate extends Validate{
    
    //验证规则
    protected $rule =   [
        'id'                  =>  'require|number|min:1',     //必须，数字，最小值为1
        'img_url'             =>  'require',     //必须
    ];
    
    //提示信息
    protected $message  =   [ 
        'id.require'          => '请指定id',
        'id.number'           => 'id必须是数字',
        'id.min'              => 'id最小值为1',
        'img_url.require'     => '请指定图片路径',
    ];
    
    //场景
    protected $scene = [
        'select_one'  =>  [         //查询时需要校验的字段(单条)
            'id'
        ],
        'delete'  =>  [         //删除时需要校验的字段
            'id',
            'img_url'
        ],
        'edit'     =>  [         //编辑时需要校验的字段
            'id',
            'pid',
            'name',
            'icon',
            'url',
            'indexno'
        ],
        'auth'     =>  [         //分配权限时需要校验的字段
            'id',
            'auth_ids',
            'type'
        ],
    ];
    
}

