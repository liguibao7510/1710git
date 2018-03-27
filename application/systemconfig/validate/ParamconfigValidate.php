<?php
namespace app\systemconfig\validate;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：配置参数配置管理验证器
 */
use think\Validate;
class ParamconfigValidate extends Validate{
    
    //验证规则
    protected $rule =   [
        'id'                =>  'require|number|between:1,65535',     //必须，数字，范围1-2147483647
        'name'              =>  'require|chsDash|length:2,20',     //必须，汉字或数字或字母或下划线或者-,长度2-20位
        'flag'              =>  'require|alphaDash|length:2,20',     //必须，数字或字母或下划线或者-,长度2-20位
        'type'              =>  'require|number|between:1,2',     //必须，数字，范围1-2147483647
        'value'             =>  'require|length:2,256',     //必须,长度2-256位
        'indexno'           =>  'number|between:0,256',     //，数字，范围0-256
    ];
    
    //提示信息
    protected $message  =   [ 
        'id.require'            => '请指定id',
        'id.number'             => 'id只能为数字',
        'id.between'            => 'id范围1-65535',
        'name.require'          => '请输入名称',
        'name.chsDash'          => '名字只能是汉字/字母/数字/下划线/扩折号',
        'name.length'           => '名字长度在2-20个字符',
        'flag.require'          => '请输入标识符',
        'flag.alphaDash'        => '标识符只能是汉字/字母/数字/下划线/扩折号',
        'flag.length'           => '标识符长度在2-20个字符',
        'type.require'          => '请指定类型',
        'type.number'           => '类型只能为数字',
        'type.between'          => '类型只能为1或2',
        'value.require'         => '请输入配置内容',
        'value.length'          => '配置内容长度为2-256',
        'indexno.number'        => '序号只能为数字',
        'indexno.between'       => '序号范围0-256',
    ];
    
    //场景
    protected $scene = [
        'select_one'  =>  [         //查询时需要校验的字段(单条)
            'id'
        ],
        'add'     =>  [         //新增时需要校验的字段
            'name',
            'flag',
            'type',
            'value',
            'indexno'
        ],
        'delete'  =>  [         //删除时需要校验的字段
            'id',
            'flag'
        ],
        'fix_edit'     =>  [         //编辑时需要校验的字段
            'id',
            'flag',
            'value'
        ],
        'freed_edit'     =>  [         //编辑时需要校验的字段
            'id',
            'name',
            'flag',
            'type',
            'value',
            'indexno'
        ],
        'auth'     =>  [         //分配权限时需要校验的字段
            'id',
            'auth_ids',
            'type'
        ],
    ];
    
}

