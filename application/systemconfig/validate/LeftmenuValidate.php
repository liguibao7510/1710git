<?php
namespace app\systemconfig\validate;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：左侧菜单栏管理验证器
 */
use think\Validate;
class LeftmenuValidate extends Validate{
    
    //验证规则
    protected $rule =   [
        'id'                =>  'require|number|between:1,65535',     //必须，数字，范围1-2147483647
        'name'              =>  'require|chsDash|length:2,20',     //必须，汉字或数字或字母或下划线或者-,长度2-20位
        'icon'              =>  ['regex'=>'([A-Za-z0-9_\- ]){1,20}'],     //必须，数字或字母或下划线或者-,长度2-20位
        'pid'               =>  'number|between:1,65535',     //必须，数字，范围1-65535
        'indexno'           =>  'number|between:0,65535',     //，数字，范围1-65535
        'url'               =>  ['regex'=>'(\/{0,1}[A-Za-z0-9_]{0,16}){1,5}'],     //，pathinfo模式的url(剔除域名)
    ];
    
    //提示信息
    protected $message  =   [ 
        'id.require'            => '请指定id',
        'id.number'             => 'id只能为数字',
        'id.between'            => 'id范围1-65535',
        'pid.number'            => '父级id只能为数字',
        'pid.between'           => '父级id范围0-65535',
        'name.require'          => '请输入名称',
        'name.chsDash'          => '名字只能是汉字/字母/数字/下划线/扩折号',
        'name.length'           => '名字长度在2-20个字符',
        'icon.regex'            => '图标只能是字母/数字/下划线/扩折号/空格',
        'indexno.number'        => '序号只能为数字',
        'indexno.between'       => '序号范围1-65535',
        'url.regex'             => 'url格式不正确',
    ];
    
    //场景
    protected $scene = [
        'select_one'  =>  [         //查询时需要校验的字段(单条)
            'id'
        ],
        'add'     =>  [         //新增时需要校验的字段
            'pid',
            'name',
            'icon',
            'url',
            'indexno'
        ],
        'delete'  =>  [         //删除时需要校验的字段
            'id'
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

