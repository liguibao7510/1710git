<?php
namespace app\userrelated\validate;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：权限管理验证器
 */
use think\Validate;
class AuthValidate extends Validate{
    
    //验证规则
    protected $rule =   [
        'id'                =>  'require|number|between:1,65535',     //必须，数字，范围1-2147483647
        'del_id'            =>  ['regex'=>'^(([1-9]\d{0,8}|[1-9]\d{0,8},|,[1-9]\d{0,8}){1,100})$'],     //格式1或者1,或者,1
        'name'              =>  'require|chsDash|length:2,20',     //必须，汉字或数字或字母或下划线或者-,长度2-20位
        'pid1'              =>  'require|number|between:0,65535',     //必须，数字，范围1-2147483647
        'pid2'              =>  'require|number|between:0,65535',     //必须，数字，范围1-2147483647
        'controller'        =>  'alphaDash|length:2,20',     //数字或字母或下划线或者-，长度2-20位
        'action'            =>  'alphaDash|length:2,20',     //数字或字母或下划线或者-，长度2-20位
        'model'             =>  'require|alphaDash|length:2,20',     //必须，数字或字母或下划线或者-，长度2-20位
        'indexno'           =>  'number|between:0,65535',     //必须，数字，范围1-2147483647
    ];
    
    //提示信息
    protected $message  =   [ 
        'id.require'            => '请指定id',
        'id.number'             => 'id只能为数字',
        'id.between'            => 'id范围1-65535',
        'del_id.regex'          => '指定删除id格式不正确或者长度过长',
        'name.require'          => '请输入名称',
        'name.chsDash'          => '名字只能是汉字/字母/数字/下划线/扩折号',
        'name.length'           => '名字长度在2-20个字符',
        'pid1.require'          => '请指定模块id',
        'pid1.number'           => '模块id只能为数字',
        'pid1.between'          => '模块id范围0-65535',
        'pid2.require'          => '请指定控制器id',
        'pid2.number'           => '控制器id只能为数字',
        'pid2.between'          => '控制器id范围0-65535',
        'controller.alphaDash'  => '控制器只能包含数字或字母或下划线或者-',
        'controller.length'     => '控制器长度为2-20',
        'action.alphaDash'      => '操作方法只能包含数字或字母或下划线或者-',
        'action.length'         => '操作方法长度为2-20',
        'model.require'         => '请输入模块',
        'model.alphaDash'       => '模块只能包含数字或字母或下划线或者-',
        'model.length'          => '模块长度为2-20',
    ];
    
    //场景
    protected $scene = [
        'select_one'  =>  [         //查询时需要校验的字段(单条)
            'id'
        ],
        'add'     =>  [         //新增时需要校验的字段
            'name',
            'pid1',
            'pid2',
            'controller',
            'action',
            'model',
            'indexno'
        ],
        'delete'  =>  [         //删除时需要校验的字段
            'del_id'
        ],
        'edit'     =>  [         //编辑时需要校验的字段
            'id',
            'name',
            'department',
            'level',
            'indexno'
        ],
    ];
    
}

