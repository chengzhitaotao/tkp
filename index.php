<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

//处理中文乱码
header("Content-Type:text/html;charset=utf-8");

//定义常量ROOT表示项目根目录的绝对路径
define("ROOT", str_replace("\\", "/", dirname(__FILE__)."/"));

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// //绑定模块 访问地址中不能写模块名称，默认访问Home模块
// define("BIND_MODULE","Home");

// define("DB_DSN", "mysql://root:123456@localhost:3306/guanli#utf8");

// 定义应用目录
define('APP_PATH',ROOT.'./Application/');

// 引入ThinkPHP入口文件
require ROOT.'./ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单