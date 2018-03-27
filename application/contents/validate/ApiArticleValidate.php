<?php
namespace app\contents\validate;
/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：文章接口管理验证器
 */
use think\Validate;
class ApiArticleValidate extends Validate{
    
    //验证规则
    protected $rule =   [
        'id'                =>  'require|number|between:1,2147483647',     //必须，数字，范围1-2147483647
        'cover'             =>  'url|length:8,128', //url地址，长度8-128
        'title'             =>  'require|length:2,80',     //必须,长度2-80位
        'abstract'          =>  'require|length:2,200',     //必须,长度2-200位
        'keywords'          =>  'require|length:2,100',     //必须,长度2-100位
        'contents'          =>  'require',     //必须
        'classify'          =>  'require|number|between:1,65535',     //必须，数字，范围1-65535
        'current_page'      =>  'number|between:1,2147483647',     //必须，数字，范围1-2147483647
        'every_get'         =>  'number|between:1,200',     //必须，数字，范围5-200
        'is_check'          =>  'require|number|between:1,2',     //必须，数字，范围1-2
    ];
    
    //提示信息
    protected $message  =   [ 
        'id.require'            => '请指定id',
        'id.number'             => 'id只能为数字',
        'id.between'            => 'id范围1-2147483647',
        'cover.url'             => '封面地址只能为url',
        'cover.length'          => '封面url地址长度8-128',
        'title.require'         => '请输入标题',
        'title.length'          => '标题长度在2-80个字符',
        'abstract.require'      => '请输入文章摘要',
        'abstract.length'       => '文章摘要长度在2-200个字符',
        'keywords.require'      => '请输入文章关键字',
        'keywords.length'       => '关键字长度在2-100个字符',
        'contents.require'      => '文章内容不能为空',
        'classify.require'      => '请指定文章分类',
        'classify.number'       => '分类只能是数字',
        'classify.between'      => '分类范围1-65535',
        'current_page.number'   => '当前页码只能为数字',
        'current_page.between'  => '当前页码范围1-2147483647',
        'every_get.number'      => '每页条数只能为数字',
        'every_get.between'     => '每页条数范围1-200',
        'is_check.require'      => '请指定is_check参数',
        'is_check.number'       => 'is_check参数只能为数字',
        'is_check.between'      => 'is_check参数范围1-2',
    ];
    
    //场景
    protected $scene = [
        'select_one'  =>  [         //查询时需要校验的字段(单条)
            'id'
        ],
        'select'  =>  [         //查询时需要校验的字段(多条)
            'current_page',
            'every_get'
        ],
        'add'     =>  [         //新增时需要校验的字段
            'cover',
            'title',
            'abstract',
            'keywords',
            'contents',
            'classify'
        ],
        'delete'  =>  [         //删除时需要校验的字段
            'id'
        ],
        'edit'     =>  [         //编辑时需要校验的字段
            'id',
            'cover',
            'title',
            'abstract',
            'keywords',
            'contents',
            'classify'
        ],
        'check'     =>  [         //切换审核状态时需要校验的字段
            'id',
            'is_check'
        ],
    ];
    
}

