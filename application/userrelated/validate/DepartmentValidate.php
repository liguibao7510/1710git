<?php
namespace app\userrelated\validate;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：部门管理验证器
 */
use think\Validate;
class DepartmentValidate extends Validate{
    
    //验证规则
    protected $rule =   [
        'id'                =>  'require|number|between:1,65535',     //必须，数字，范围1-2147483647
        'name'              =>  'require|chsDash|length:2,20',     //必须，汉字或数字或字母或下划线或者-,长度2-20位
        'code'              =>  'require|alphaDash|length:3,32',     //必须，数字或字母或下划线或者-,长度3-32位
        'indexno'             =>  'number|between:0,65535',     //数字，范围0-65535
    ];
    
    //提示信息
    protected $message  =   [ 
        'id.require'    => '请指定id',
        'id.number'     => 'id只能为数字',
        'id.between'    => 'id范围1-65535',
        'name.require'  => '请输入名称',
        'name.chsDash'  => '名字只能是汉字/字母/数字/下划线/扩折号',
        'name.length'   => '名字长度在2-20个字符',
        'code.require'  => '请输入编码',
        'code.chsDash'  => '编码只能是字母/数字/下划线/扩折号',
        'code.length'   => '编码长度在3-32个字符',
        'indexno.number'  => '排序序号只能是数字',
        'indexno.between' => '排序序号范围0-65535',
    ];
    
    //场景
    protected $scene = [
        'select_one'  =>  [         //查询时需要校验的字段(单条)
            'id'
        ],
        'add'     =>  [         //新增时需要校验的字段
            'name',
            'code',
            'index'
        ],
        'delete'  =>  [         //删除时需要校验的字段
            'id'
        ],
        'edit'     =>  [         //编辑时需要校验的字段
            'id',
            'name',
            'code',
            'indexno'
        ],
    ];
    
}

