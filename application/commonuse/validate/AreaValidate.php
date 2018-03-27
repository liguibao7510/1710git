<?php
namespace app\commonuse\validate;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：地区管理验证器
 */
use think\Validate;
class AreaValidate extends Validate{
    
    //验证规则
    protected $rule =   [
        'pid'                =>  'require|number|between:0,144781210120784720',     //必须，数字，范围0-144781210120784720
    ];
    
    //提示信息
    protected $message  =   [
        'pid.require'            => '请指定父级id',
        'pid.number'             => '父级id只能是数字',
        'pid.between'            => '父级id范围0-144781210120784720',
    ];
    
    //场景
    protected $scene = [
        'select'  =>  [         //查询时需要校验的字段
            'pid'
        ],
    ];
    
}

