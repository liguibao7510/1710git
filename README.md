ThinkPHP 5.0
===============

[![Total Downloads](https://poser.pugx.org/topthink/think/downloads)](https://packagist.org/packages/topthink/think)
[![Latest Stable Version](https://poser.pugx.org/topthink/think/v/stable)](https://packagist.org/packages/topthink/think)
[![Latest Unstable Version](https://poser.pugx.org/topthink/think/v/unstable)](https://packagist.org/packages/topthink/think)
[![License](https://poser.pugx.org/topthink/think/license)](https://packagist.org/packages/topthink/think)

ThinkPHP5在保持快速开发和大道至简的核心理念不变的同时，PHP版本要求提升到5.4，对已有的CBD模式做了更深的强化，优化核心，减少依赖，基于全新的架构思想和命名空间实现，是ThinkPHP突破原有框架思路的颠覆之作，其主要特性包括：

 + 基于命名空间和众多PHP新特性
 + 核心功能组件化
 + 强化路由功能
 + 更灵活的控制器
 + 重构的模型和数据库类
 + 配置文件可分离
 + 重写的自动验证和完成
 + 简化扩展机制
 + API支持完善
 + 改进的Log类
 + 命令行访问支持
 + REST支持
 + 引导文件支持
 + 方便的自动生成定义
 + 真正惰性加载
 + 分布式环境支持
 + 更多的社交类库

> ThinkPHP5的运行环境要求PHP5.4以上。

详细开发文档参考 [ThinkPHP5完全开发手册](http://www.kancloud.cn/manual/thinkphp5)

## 目录结构

初始的目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─module_name        模块目录
│  │  ├─config.php      模块配置文件
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  │
│  ├─command.php        命令行工具配置文件
│  ├─common.php         公共函数文件
│  ├─config.php         公共配置文件
│  ├─route.php          路由配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─database.php       数据库配置文件
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~

> router.php用于php自带webserver支持，可用于快速测试
> 切换到public目录后，启动命令：php -S localhost:8888  router.php
> 上面的目录结构和名称是可以改变的，这取决于你的入口文件和配置参数。

## 命名规范

`ThinkPHP5`遵循PSR-2命名规范和PSR-4自动加载规范，并且注意如下规范：

### 目录和文件

*   目录不强制规范，驼峰和小写+下划线模式均支持；
*   类库、函数文件统一以`.php`为后缀；
*   类的文件名均以命名空间定义，并且命名空间的路径和类库文件所在路径一致；
*   类名和类文件名保持一致，统一采用驼峰法命名（首字母大写）；

### 函数和类、属性命名
*   类的命名采用驼峰法，并且首字母大写，例如 `User`、`UserType`，默认不需要添加后缀，例如`UserController`应该直接命名为`User`；
*   函数的命名使用小写字母和下划线（小写字母开头）的方式，例如 `get_client_ip`；
*   方法的命名使用驼峰法，并且首字母小写，例如 `getUserName`；
*   属性的命名使用驼峰法，并且首字母小写，例如 `tableName`、`instance`；
*   以双下划线“__”打头的函数或方法作为魔法方法，例如 `__call` 和 `__autoload`；

### 常量和配置
*   常量以大写字母和下划线命名，例如 `APP_PATH`和 `THINK_PATH`；
*   配置参数以小写字母和下划线命名，例如 `url_route_on` 和`url_convert`；

### 数据表和字段
*   数据表和字段采用小写加下划线方式命名，并注意字段名不要以下划线开头，例如 `think_user` 表和 `user_name`字段，不建议使用驼峰和中文作为数据表字段命名。

## 参与开发
请参阅 [ThinkPHP5 核心框架包](https://github.com/top-think/framework)。

## 版权信息

ThinkPHP遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2006-2017 by ThinkPHP (http://thinkphp.cn)

All rights reserved。

ThinkPHP® 商标和著作权所有者为上海顶想信息科技有限公司。

更多细节参阅 [LICENSE.txt](LICENSE.txt)




 【用户管理】
    后台用户管理

    常规中大型系统开发的框架前期准备工作
    实现“增删该查”操作，如果是新手的童鞋可以参考此模块进行开发，
    这几个操作中往往让一般人头疼的就是查询操作(分页，以及带条件分页，
    这里我已经实现了异步分页，带条件搜索进行分页我后期肯定会加上，不会太久)

    (专业化)：小到任何一个参数的校验，大到系统错误或者异常的处理，都已经进行了封装，代码够安全
    ，代码与资源都已经进行了架构部署上的分类，让你的代码不再乱！<br/>
    (简单化)：在开发过程中担心新手有些看不懂，所以大部分代码我都进行了通俗的注释！

【用户管理】

    后台用户登陆

    用户登陆校验采用mysql配合redis进行，第一次进行mysql校验之后会动态生成一个唯一token，
    这个token会返回且保存到本地cookie，在之后的每次请求中带着cookie的token到redis中去寻
    找是否存在token，如果存在，校验通过，并且旧token会失效，生成最新的唯一token返回
    速度提升：目前以及后面的后续开发中都会大量采用redis，让我们的数据读取更快
    安全：目前已经是动态token校验，下一个版本会新增手机验证码校验硬件，做到(即使别人知道你
    密码，也无法登陆你账号)，当前版本已经支持单点登陆，支持异地登陆提醒！让账户更安全
    权限采用RBAC+Auth结合，让你的权限系统在严谨的同时增加灵活性，前期暂时只提供RBAC，Auth
    个人感觉代码有点冗余，很多人都说它灵活，但是代码量会增加，特点就是到处验证，对这两种
    都用过的人应该知道它们两个其实没多少区别，如果要进行规则验证。还是得不断新增代码以及 相关数据库表的角色与部门管理，部分权限管理(待完成)

    规范的企业产品是需要部门=》职位的，所以老王就这样设计了，不喜欢的可以自己去掉！
    权限管理：目前只是实现权限的新增，目的是完成常规的权限控制，同时实现通过角色去给用户
    限制权限，也可以单独给用户添加权限，权限会同步到redis，因为权限校验这个操作太频繁了

    。。。由于其它事情比较忙，速度不会很快，同时有不足之处还请各位大牛指出！

【用户管理】

        后台用户登陆

                优化之前的登陆规则,代码上的优化,缓存键进行全局配置化
        角色与部门管理，部分权限管理
                完成权限的管理，强大的异步界面，提高用户体验
                权限控制(RBAC),特点就是通过角色给用户分配权限，同时也可以
                单独给用户分配权限，这些数据已经全部同步到redis，mysql会保留一份，
                确保redis出问题时可以通过mysql进行恢复

                权限的校验全部在redis上进行，所以速度相当快！

【系统设置】
        左侧菜单栏
                完成左侧菜单栏的管理，前台显示暂时只处理最高到二级菜单，后台代码(model)
                和数据库表设计是支持多级菜单的，如果有需要自己修改前段html以及controller
                返回数组重组部分代码

        其他修复

                部分代码的优化和错误修复














