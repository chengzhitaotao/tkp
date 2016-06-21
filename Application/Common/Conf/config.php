<?php
//应用配置 全局配置
return array(
	//'配置项'=>'配置值'
	//数据库PDO访问配置
// 	"DSN"=>"mysql:host=localhost;dbname=guanli",
//     "DBUSER"=>"root",
//     "DBPASS"=>"123456",
//     "DBPORT"=>3306,
//     "PDOOPTIONS"=>array(
//         \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION
//     ),

    //修改默认的模板目录结构
//     'TMPL_FILE_DEPR'=>'_',
    //分页查询相关配置
    "PAGENO"=>1,
    "PAGESIZE"=>10,
    
    //设置控制器目录为二级
//     'CONTROLLER_LEVEL'=>2,
    
//     //Action参数绑定
//     'URL_PARAMS_BIND' => true, 

//     //设置参数绑定的方式为按顺序绑定 默认是按变量名称绑定
//     'URL_PARAMS_BIND_TYPE' => 1
    
//     'VAR_MODULE' => 'mo', // 默认模块获取变量
//     'VAR_CONTROLLER' => 'co', // 默认控制器获取变量
//     'VAR_ACTION' => 'ac', // 默认操作获取变量
    
//     "URL_MODEL"=>2

    //开启路由
//     'URL_ROUTER_ON'=>true,
//     //动态路由
//     'URL_ROUTE_RULES'=>array(
//         'tttt/:name/:id'            =>"Home/Index/test",       //静态和动态相结合                     
// //         '/^bbb\/(\d{4})\/(\d{2})\/(\d{2})$/' =>"Home/Index/test",
//     'bbb/:year/:month/:day' => "Home/Index/test",
//     ),
//     //静态路由
//     'URL_MAP_RULES'=>array(
//         'ttt'                       =>"Home/index/index",       //静态规则路由
//         'login'                     =>"Home/User/www",
//         'aaa'                       =>array("http://www.baidu.com",302),
//     )

    "THINKPHP_DSN"=>"mysql://root:123456@localhost:3306/guanli#utf8",
        
    //数据库设置 Model的数据库连接
    'DB_TYPE'   => 'mysql',     // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_USER'   => 'root',      // 用户名
    'DB_PWD'    => '123456',    // 密码
    'DB_PORT'   => '3306',      // 端口
    'DB_NAME'   => 'guanli',    // 数据库
    'DB_CHARSET'=> 'utf8',      // 数据库编码默认采用utf8
    'DB_PARAMS' =>  array(
        \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION
    ), // 数据库连接参数
    
    
    
);