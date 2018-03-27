<?php

     use think\Route;


     Route::rule('test','userrelated/admins/login');
    Route::group('', function(){
        Route::group('backend', function(){   //后台模块

            Route::get('index' , 'index/index/index');    //后台首页
            Route::get('controller' , 'index/index/controller');    //后台首页控制台
            Route::group('adminer', function(){   //管理员
                Route::get('list' , 'userrelated/admins/showlist');    //管理员列表模板
                Route::post('listdatas' , 'userrelated/admins/list_datas');   //获取管理员数据(多条)
                Route::post('one' , 'userrelated/admins/get_one_data');   //获取管理员数据(单条)
                Route::post('create' , 'userrelated/admins/add');   //新增管理员
                Route::post('remove' , 'userrelated/admins/delete');   //删除管理员
                Route::post('edit' , 'userrelated/admins/update');   //编辑管理员资料
                Route::post('check' , 'userrelated/admins/check_amdiner');   //后台人员登陆校验
                Route::post('depmtrole' , 'userrelated/admins/get_dprole_list');   //获取部门与角色数据
                Route::post('giveauth' , 'userrelated/admins/distribution_auth');   //分配权限

                });
            Route::get('login' , 'userrelated/admins/login');   //后台人员登陆页面
            Route::post('logout' , 'userrelated/admins/logout');
            Route::group('bumeng', function(){   //部门
                Route::get('list' , 'userrelated/department/showlist');    //列表
                Route::post('one' , 'userrelated/admins/get_one_data');   //获取数据(单条)
                Route::post('create' , 'userrelated/department/add');   //新增数据
                Route::post('remove' , 'userrelated/department/delete');   //删除数据
                Route::post('edit' , 'userrelated/department/update');   //编辑数据
            });
            Route::group('sysconf', function(){   //系统设置
                Route::get('lfmulist' , 'systemconfig/leftmenu/showlist');    //左侧菜单栏列表
                Route::post('lfmucreate' , 'systemconfig/leftmenu/add');   //新增左侧菜单栏
                Route::post('lfmuremove' , 'systemconfig/leftmenu/delete');   //删除左侧菜单栏
                Route::post('lfmuedit' , 'systemconfig/leftmenu/update');   //编辑删除左侧菜单栏
                Route::get('paramlist' , 'systemconfig/paramconfig/showlist');    //参数列表
                Route::post('paramcreate' , 'systemconfig/paramconfig/add');   //新增参数
                Route::post('paramremove' , 'systemconfig/paramconfig/delete');   //删除参数
                Route::post('paramedit' , 'systemconfig/paramconfig/update');   //编辑参数
            });
            Route::group('position', function(){   //角色
                Route::get('list' , 'userrelated/role/showlist');    //列表
                Route::post('one' , 'userrelated/role/get_one_data');   //获取数据(单条)
                Route::post('create' , 'userrelated/role/add');   //新增数据
                Route::post('remove' , 'userrelated/role/delete');   //删除数据
                Route::post('edit' , 'userrelated/role/update');   //编辑数据
                Route::get('auth/:id/:type' , 'userrelated/role/auth_showlist');   //权限列表(type说明是用户id还是角色id,可选[role/adminer])
                Route::post('giveauth' , 'userrelated/role/distribution_auth');   //分配权限
            });
            Route::group('limit', function(){   //权限
                Route::get('list' , 'userrelated/auth/showlist');    //列表
                Route::post('one' , 'userrelated/auth/get_one_data');   //获取数据(单条)
                Route::post('create' , 'userrelated/auth/add');   //新增数据
                Route::post('remove' , 'userrelated/auth/delete');   //删除数据
                Route::post('edit' , 'userrelated/auth/update');   //编辑数据
            });
            Route::group('article', function(){   //文章管理
                //文章分类
                Route::get('cflist' , 'contents/articleclassify/showlist');    //文章分类列表
                Route::post('cfcreate' , 'contents/articleclassify/add');   //文章分类新增数据
                Route::post('cfremove' , 'contents/articleclassify/delete');   //文章分类删除数据
                Route::post('cfedit' , 'contents/articleclassify/update');   //编辑数据
                //文章
                Route::get('list' , 'contents/article/showlist');    //文章列表(模板)
                Route::post('list' , 'contents/article/list_datas');    //文章列表(数据)
                Route::get('create' , 'contents/article/add');    //添加文章(模板)
                Route::post('create' , 'contents/article/adddatas');    //添加文章(数据)
                Route::post('remove' , 'contents/article/delete');    //删除文章(单条)
                Route::get('edit/:id' , 'contents/article/update');    //编辑文章(模板)
                Route::post('edit' , 'contents/article/updatedatas');    //编辑文章(数据)
                Route::post('data' , 'contents/article/getone');    //获取一条文章数据
                Route::post('checkstatus' , 'contents/article/changeCheck');    //切换审核状态
                //文章接口
                Route::post('apilist' , 'contents/apiarticle/listdatas');    //【接口】文章列表(数据)
                Route::post('detail' , 'contents/apiarticle/getone');    //【接口】文章数据(单条)
            });
            Route::group('webindex', function(){   //网站导航管理(网站地图)
                Route::get('list' , 'contents/webmap/showlist');    //导航列表
                Route::post('create' , 'contents/webmap/add');   //导航新增数据
                Route::post('remove' , 'contents/webmap/delete');   //导航删除数据
                Route::post('edit' , 'contents/webmap/update');   //编辑数据
            });
            Route::group('file', function(){   //文件管理
                Route::post('create' , 'upload/alioss/add');    //文件上传
                Route::post('remove' , 'upload/alioss/delete');    //文件删除
            });
            Route::group('test', function(){   //测试
                Route::get('test1' , 'userrelated/test/test1');    //列表
            });

        });
        Route::group('common', function(){   //通用
            Route::group('tool', function(){   //工具
                Route::post('get_url' , 'commonuse/tools/make_url');    //获取制作url
                Route::post('get_area' , 'commonuse/area/get_datas_by_parent');    //获取地区数据
                Route::get('getjson' , 'commonuse/tools/make_classify_json');    //获取json
            });
            Route::group('auth', function(){   //权限控制
                Route::post('get_url' , 'commonuse/tools/make_url');    //获取制作url
            });
            Route::group('cache', function(){   //缓存
                Route::get('test' , 'commonuse/testredis/set_key');    //获取制作url
            });
            Route::group('error', function(){   //错误页面
                Route::get('auth' , 'commonuse/errorpage/no_auth');    //无权限提示页面
            });
        });
        Route::group('web', function(){   //前台
            Route::get('index' , 'webshow/index/index');    //前台首页
            Route::get('article' , 'webshow/article/articlelist');    //文章列表
            Route::get('artdetail/:id' , 'webshow/article/artdetail');    //文章详情
            Route::get('about' , 'webshow/ourinfo/aboutus');    //关于我们
            Route::get('contact' , 'webshow/ourinfo/findus');    //联系我们
        });
    });
