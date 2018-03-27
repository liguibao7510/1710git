<?php
namespace app\userrelated\validate;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：后台用户管理验证器
 */
use think\Validate;
class AdminsValidate extends Validate{
    
    //验证规则
    protected $rule =   [    
        'current_page'      =>  'require|number|between:1,600',     //必须，数字，范围1-600
        'every_get'         =>  'require|number|between:5,100',     //必须，数字，范围5-100
        'del_mode'          =>  'require|number|between:1,2',     //必须，数字，范围1-2
        'id'                =>  'require|number|between:1,2147483647',     //必须，数字，范围1-2147483647
        'del_id'            =>  ['regex'=>'^(([1-9]\d{0,8}|[1-9]\d{0,8},|,[1-9]\d{0,8}){1,100})$'],     //格式1或者1,或者,1
        'auth_ids'          =>  ['regex'=>'^(([1-9]\d{0,8}|[1-9]\d{0,8},|,[1-9]\d{0,8}){1,100})$'],     //格式1或者1,或者,1
        'name'              =>  'require|alphaDash|length:5,20',     //必须，数字或字母或下划线或者-,长度5-20位
        'passwd'            =>  'require|alphaDash|length:6,20',     //必须，数字或字母或下划线或者-，长度6-20位
        'role'              =>  'number|between:0,256',     //数字，长度0-256
        'department'        =>  'number|between:0,65535',     //数字，长度0-65535位数
        'nike_name'         =>  'chsDash|length:2,20',     //数字或字母或下划线或者-，长度2-20位
        'ture_name'         =>  'chsDash|length:2,20',     //数字或字母或下划线或者-，长度2-20位
        'phone'             =>  'mobile',     //手机号码
        'email'             =>  'email',     //邮箱
        'qq'                =>  'number|between:6,15',     //数字，6-15位
        'wx'                =>  'alphaDash|length:6,20',     //数字或字母或下划线或者-，长度6-20位
        'sex'               =>  'number|between:1,3',     //数字，1-3
        'age'               =>  'number|between:0,150',     //数字，0-150
        'country'           =>  'number|between:0,65535',     //数字，0-65535
        'province'          =>  'number|between:0,144781210120784720',     //数字，0-144781210120784720
        'city'              =>  'number|between:0,144781210120784720',     //数字，0-144781210120784720
        'area'              =>  'number|between:0,144781210120784720',     //数字，0-144781210120784720
        'county'            =>  'number|between:0,144781210120784720',     //数字，0-144781210120784720
        'town'              =>  'number|between:0,144781210120784720',     //数字，0-144781210120784720
        'village'           =>  'number|between:0,144781210120784720',     //数字，0-144781210120784720
        'addr'              =>  'chsDash|length:8,32',     //汉字或数字或字母或下划线或者-，长度8-32位
        'zjno'              =>  'alphaDash|length:8,32',     //数字或字母或下划线或者-，长度8-32位
        'check'             =>  'number|between:1,2',     //数字，1-2
        'type'              =>  ['regex'=>'^(adminer)$'],     //，只能是adminer
    ];
    
    //提示信息
    protected $message  =   [ 
        'current_page.require'  => '请指定但也页码',
        'current_page.number'   => '当前页码只能是数字',
        'current_page.between'  => '当前页码只能为1-600',
        'del_mode.require'      => '必须指定删除模式(1-单条/2-多条)',
        'del_mode.number'       => '删除模式只能是数字',
        'del_mode.between'      => '删除模式值只能为1-2',
        'every_get.require'     => '每次获取条数必须传',
        'every_get.number'      => '每次获取条数只能是数字',
        'every_get.between'     => '每次获取条数只能为5-100',
        'id.require'            => '请指定id',
        'id.number'             => 'id只能是数字',
        'id.between'            => 'id范围1-2147483647',
        'del_id.regex'          => '指定删除id格式不正确或者长度过长',
        'name.require'          => '用户名不能为空',
        'name.alphaDash'        => '用户名只能是字母/数字/下划线和括折号',
        'name.length'           => '用户名长度为5-20',
        'passwd.require'        => '密码不能为空',
        'passwd.alphaDash'      => '密码只能是字母/数字/下划线和括折号',
        'passwd.length'         => '密码长度为6-20',
        'role.number'           => '角色必须填入数字',
        'role.between'          => '角色值只能在0-256',
        'department.number'     => '部门必须填入数字',
        'department.between'    => '部门值只能在0-65535',
        'nike_name.chsDash'     => '昵称只能是汉字/字母/数字/下划线和括折号',
        'nike_name.length'      => '昵称长度在2-20',
        'ture_name.chsDash'     => '姓名只能是汉字/字母/数字/下划线和括折号',
        'ture_name.length'      => '姓名长度在2-20',
        'phone.mobile'          => '请输入正确的手机号码',
        'email.email'           => '请输入正确的邮箱',
        'wx.alphaDash'          => '微信号只能是字母/数字/下划线和括折号',
        'wx.length'             => '微信号长度在6-20位',
        'sex.number'            => '性别只能是数字',
        'sex.between'           => '性别值只能在1-3',
        'age.number'            => '年龄只能是数字',
        'age.between'           => '年龄值只能在0-150',
        'country.number'        => '国家只能是数字',
        'country.between'       => '国家值只能在0-65535',
        'province.number'       => '省只能是数字',
        'province.between'      => '省值只能在0-144781210120784720',
        'city.number'           => '市只能是数字',
        'city.between'          => '市值只能在0-144781210120784720',
        'area.number'           => '区只能是数字',
        'area.between'          => '区值只能在0-144781210120784720',
        'county.number'         => '县只能是数字',
        'county.between'        => '县值只能在0-144781210120784720',
        'town.number'           => '镇只能是数字',
        'town.between'          => '镇值只能在0-144781210120784720',
        'village.number'        => '村只能是数字',
        'village.between'       => '村值只能在0-144781210120784720',
        'addr.chsDash'          => '地址只能是汉字/字母/数字/下划线和括折号',
        'addr.length'           => '地址长度为8-32',
        'zjno.chsDash'          => '证件号只能是字母/数字/下划线和括折号',
        'zjno.length'           => '证件号长度为8-32',
        'type.regex'            => '分配权限时id类型type只能是adminer',
    ];
    
    //场景
    protected $scene = [
        'select'  =>  [         //查询时需要校验的字段(多条)
            'current_page',
            'every_get'
        ],
        'select_one'  =>  [         //查询时需要校验的字段(单条)
            'id'
        ],
        'add'     =>  [         //新增时需要校验的字段
            'name',
            'passwd',
            'role',
            'department',
            'nike_name',
            'ture_name',
            'phone',
            'email',
            'qq',
            'wx',
            'sex',
            'age',
            'country',
            'province',
            'city',
            'area',
            'county',
            'town',
            'village',
            'addr',
            'zjno',
            'check'
        ],
        'delete'  =>  [         //删除时需要校验的字段
            'del_mode', //删除模式1-单个id删除，2-多个id进行删除
            'del_id'
        ],
        'edit'     =>  [         //编辑时需要校验的字段
            'id',
            'role',
            'department',
            'nike_name',
            'ture_name',
            'phone',
            'email',
            'qq',
            'wx',
            'sex',
            'age',
            'country',
            'province',
            'city',
            'area',
            'county',
            'town',
            'village',
            'addr',
            'zjno',
            'check'
        ],
         'login'  =>  [         //登陆时需要校验的字段
            'name', //删除模式1-单个id删除，2-多个id进行删除
            'passwd'
        ],
         'auth'  =>  [         //登陆时需要校验的字段
            'id',
            'auth_ids',
            'type'
        ],
    ];
    
}

