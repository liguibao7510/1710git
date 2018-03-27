<?php
namespace app\userrelated\validate;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：角色管理验证器
 */
use think\Validate;
class RoleValidate extends Validate{
    
    //验证规则
    protected $rule =   [
        'id'                =>  'require|number|between:1,65535',     //必须，数字，范围1-2147483647
        'auth_ids'          =>  ['regex'=>'^(([1-9]\d{0,8}|[1-9]\d{0,8},|,[1-9]\d{0,8}){1,100})$'],     //格式1或者1,或者,1
        'name'              =>  'require|chsDash|length:2,20',     //必须，汉字或数字或字母或下划线或者-,长度2-20位
        'department'        =>  'require|number|between:1,65535',     //必须，数字，范围1-65535
        'level'              =>  'number|between:0,65535',     //数字，范围1-65535
        'indexno'           =>  'number|between:0,65535',     //，数字，范围1-65535
        'type'              =>  ['regex'=>'^(role)$'],     //，只能是role
    ];
    
    //提示信息
    protected $message  =   [ 
        'id.require'            => '请指定id',
        'id.number'             => 'id只能为数字',
        'id.between'            => 'id范围1-65535',
        'auth_ids.regex'        => '分配权限格式不正确或者长度过长',
        'name.require'          => '请输入名称',
        'name.chsDash'          => '名字只能是汉字/字母/数字/下划线/扩折号',
        'name.length'           => '名字长度在2-20个字符',
        'department.require'    => '请指定部门',
        'department.number'     => '部门只能为数字',
        'department.between'    => '部门范围1-65535',
        'leve.number'           => '权限值只能为数字',
        'leve.between'          => '权限值范围1-65535',
        'indexno.number'        => '序号只能为数字',
        'indexno.between'       => '序号范围1-65535',
        'type.regex'            => '分配权限时id类型type只能是role',
    ];
    
    //场景
    protected $scene = [
        'select_one'  =>  [         //查询时需要校验的字段(单条)
            'id'
        ],
        'add'     =>  [         //新增时需要校验的字段
            'name',
            'department',
            'level',
            'indexno'
        ],
        'delete'  =>  [         //删除时需要校验的字段
            'id'
        ],
        'edit'     =>  [         //编辑时需要校验的字段
            'id',
            'name',
            'department',
            'level',
            'indexno'
        ],
        'auth'     =>  [         //分配权限时需要校验的字段
            'id',
            'auth_ids',
            'type'
        ],
    ];
    
}

