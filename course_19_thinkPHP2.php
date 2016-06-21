<?php
/**
 * 第十九章：ThinkPHP架构和路由
 * 一、ThinkPHP架构
 *  1、ThinkPHP的架构思想
 *      ThinkPHP采用模块化架构设计思想，一个完整的ThinkPHP应用是基于模块/控制器/操作
 *  2、ThinkPHP架构的核心概念
 *      应用：基于同一入口文件访问的项目
 *      模块：一个应用可以包括多个模块(模块可以看做是一个项目下多个相关业务构成的集合)，每个模块都是应用下的一个独立子目录
 *      控制器：每个模块可以包含多个控制器，每个控制器通常体现为一个控制器类
 *      操作：每个控制器类可以包含多个操作方法，也可能是绑定的某个操作类，每个操作是URL访问的最小单元
 *  3、ThinkPHP模块的分类
 *      3.1、公共模块(Common)
 *          概念：公共模块，也被称为应用公共模块，该模块中的配置文件(Conf/config.php)和
 *          公共函数文件(Common/Common/function.php)会在访问其它模块前被加载
 *          特点：公共模块不能直接通过URL访问 
 *          注意：公共函数文件thinkPHP不会自动创建，需要自己添加，文件名必须为function.php
 *          公共模块默认的位置：Application/Common
 *          公共模块自定义位置：通过在入口文件中设置常量COMMON_PATH
 *          示例： define(“COMMON_PATH”,”./Common/”);//相对路径
 *          注意：可以在Common/Common目录下创建多份公共函数文件(自定义命名)，然后将这些文件require到function.php文件中
 *      3.2、自动生成模块(通过配置生成模块控制器和模型)
 *          概念：指排除默认生成模块(Home)外，由开发人员在入口文件中定义生成的其它模块
 *          特点：自动生成模块支持批量生成该模块的控制器和模型
 *          注意：默认模块也支持批量生成控制器和模型，用法和自动生成模块相同
 *          自动生成控制器和模型操作步骤：
 *          A、在入口文件绑定该入口文件对应的模块(注意一个入口文件绑定一个模块，如果有多个模块指定多个入口文件)
 *              define(“BIND_MODULE”,”模块名”);
 *          B、定义生成的控制器
 *              define(“BUILD_CONTROLLER_LIST”,”控制器名称1,…,名称n”);
 *          C、定义生成的模型
 *              define(“BUILD_MODEL_LIST”,”模型名称1,…,模型名称n”);
 *          注意：只有第一次运行入口文件才能生成，生成后如果修改了配置将不会发生作用，除非删除了模块重新生成
 *          通过ThinkPHP框架的Think\Build类生成控制器和模型：
 *          //生成控制器
 *          \Think\Build::buildController(‘模块名’,‘控制器名');
 *          //生成模型
 *          \Think\Build::buildModel(‘模块名’,‘模型名');
 *          此种方法如果控制器和模型不存在将创建，存在不创建。创建的控制器继承Think\Controller，模型继承Think\Model。
 *          如果要继承自定义的控制器父类或模型类，手动创建不要在应用入口文件中写配置
 *          此种方法的代码调用必须放在应用入口文件require './ThinkPHP/ThinkPHP.php‘;之后
 *      3.3、禁止访问模块
 *          概念：不能通过url访问的模块，一般这些模块都是框架内部调用
 *          框架默认的禁止访问模块：Common和Runtime
 *          如何自定义禁止访问模块：
 *              在应用配置文件(Common\Conf\config.php)中增加如下配置
 *              // 设置禁止访问的模块列表 
 *              ‘MODULE_DENY_LIST’ => array(‘Common’,‘Runtime’,‘自定义禁止访问模块名称');
 *      3.4、设置允许访问模块列表
 *          概念：表示项目中所有能够被URL访问的模块
 *          设置方法：在应用配置文件(Common\Conf\config.php)中增加如下配置
 *          ‘MODULE_ALLOW_LIST’ => array(‘模块名1’,‘模块名2’,‘模块名n')
 *      3.5、设置默认模块
 *          概念：表示通过URL访问不指定模块时默认使用的模块，此模块最大的特点是不需要在URL中指定模块名称
 *          设置方法：在应用配置文件(Common\Conf\config.php)中增加如下配置
 *          ‘DEFAULT_MODULE’ => ‘模块名‘
 *          注意：thinkPHP框架配置的默认模块是Home
 *      3.6、单模块
 *          概念：指web应用只有一个模块的情况下对框架做的设置
 *          设置方法：在应用配置文件(Common\Conf\config.php)中增加如下配置
 *          'MULTI_MODULE' => false,
 *          ‘DEFAULT_MODULE’ => ‘模块名‘  //thinkPHP默认模块是Home
 *          注意：单模块只能访问配置的默认模块，但公共模块依然可以在框架内部访问
 *  4、ThinkPHP的多入口设计
 *      什么是多入口设计？
 *          指给每个模块单独设置一份入口文件，从而通过不同的入口文件访问不同模块的功能
 *      多入口实现步骤：
 *          A、在web项目根目录下创建入口文件，名称自定义，以”.php”结尾，例如为Admin模块创建入口文件，即名为admin.php
 *          B、在admin.php配置文件中设置常量，基本与index.php相同，只需要添加define(“BIND_MODULE”,”Admin”),
 *          此句表示这个入口文件与Admin绑定，通过admin.php文件只能访问Admin模块
 *          C、可以将入口文件与模块的某个控制器的某个方法绑定，设置如下：define(“BIND_CONTROLLER”,“控制器名称"); 
 *          define("BIND_ACTION“,”方法名“);，但注意添加此设置表示入口文件只能访问模块的指定控制器的指定方法，
 *          其它控制器和方法均不能访问，所以一般不做此配置
 *  5、ThinkPHP的URL模式
 *      ThinkPHP采用单一入口的设计，所有的请求都从一个入口文件开始，由入口文件提取URL中传递的模块、
 *      控制器和操作名称从而调用对应的模块，控制器和操作方法，我们把这种方式成为URL模式。
 *      ThinkPHP支持的URL模式有如下几种：
 *          URL_MODEL=0   普通模式
 *          URL_MODEL=1   PATHINFO模式
 *          URL_MODEL=2   REWRITE模式
 *          URL_MODEL=3   兼容模式
 *      ThinkPHP默认的URL模式为1，此设置在ThinkPHP->Conf->convention.php中’URL_MODEL’=>1
 *      ThinkPHP允许给每个模块单独设置URL模式，设置方法在模块下的Conf->config.php中增加’URL_MODEL’设置
 *      ThinkPHP的URL模式不区分大小写
 *      ThinkPHP的URL模式详解：
 *        1、普通模式：采用传统的GET提交方式指定模块、控制器和操作，例如*****
 *          http://localhost:8080/项目名/index.php?m=Home&c=Index&a=index&var=value
 *          等同于
 *          http://localhost:8080/项目名/index.php?m=home&c=index&a=index&var=value   
 *          注意：模块、控制器、操作名称不区分大小写
 *          m代表模块名称、c代表控制器名称、a代表操作（方法名称）、var代表其他参数，可以自由定义
 *          可以通过在应用配置文件做如下配置改变默认的m,c,a名称
 *          'VAR_MODULE' => 'mo', // 默认模块获取变量    
 *          'VAR_CONTROLLER' => 'co', // 默认控制器获取变量
 *          'VAR_ACTION' => 'ac', // 默认操作获取变量
 *          注意：设置普通模式，如果用pathinfo访问依然可以被支持，反之亦可，所以thinkPHP默认URL_MODEL为1(pathinfo模式)
 *        2、PATHINFO模式：thinkPHP默认的URL模式*****
 *          模式格式为：
 *          http://localhost:8080/项目名/入口文件/模块名/控制名/操作名/var/value/var/value
 *          例如：http://localhost/test/index.php/home/index/index/name/aaa/pwd/111
 *          注意：模块、控制器、操作不区分大小写
 *          var/value表示传递的其它数据，可以有多对
 *          pathinfo也支持?传递数据模式，例如
 *          http://localhost:8080/test/index.php/home/index/index?name=aaa&pwd=111
 *          pathinfo的url分隔符可以定制，在配置文件中加入如下配置即可： ‘URL_PATHINFO_DEPR’=>‘-’，
 *          此时访问为http://localhost/test/index.php/home-index-index-name-aaa
 *        3、REWRITE模式：
 *          REWRITE模式是在PATHINFO模式的基础上添加了重写规则的支持，可以去掉URL地址里面的入口文件index.php，
 *          但是需要额外配置WEB服务器的重写规则
 *          实现步骤：
 *          A、打开apache的配置文件httpd.conf，完成如下修改
 *          #LoadModule rewrite_module modules/mod_rewrite.so
 *          去掉前面的#改为：
 *          LoadModule rewrite_module modules/mod_rewrite.so
 *          将“AllowOverride none ”改为“AllowOverride All”，有三处，然后重新启动apache服务
 *          B、在Application下的Common下的Conf下的config.php中添加配置：’URL_MODEL’=>2
 *          C、在项目根目录下添加文件”.htaccess”，写法如下：
 *          <IfModule mod_rewrite.c>
 *              RewriteEngine on
 *              RewriteCond %{REQUEST_FILENAME} !-d
 *              RewriteCond %{REQUEST_FILENAME} !-f
 *              RewriteRule ^(.*)$ index.php?s=/$1 [QSA,PT,L]
 *          </IfModule>
 *          D、配置完成按如下格式访问：
 *              http://主机ip/项目名/模块名/控制器名/操作名/var/value
 *              例如：http://localhost:8080/test/admin/index/index
 *              注意：如果在入口文件中绑定了模块，此时url写法应为：
 *              http://localhost:8080/test/index/index
 *        4、兼容模式：兼容模式是用于不支持PATHINFO的特殊环境***
 *          兼容模式的URL写法格式为：
 *          http://主机ip/项目名/入口文件?s=/模块名/控制器名/操作名/var/value
 *          例如：http://localhost:8080/test/index.php?s=/home/index/index/name/aaa
 *          可以通过在配置文件中添加如下项配置更改兼容变量名称和参数分隔符：
 *          ‘VAR_PATHINFO’=>’path’     将兼容变量s改为path
 *          ‘URL_PATHINFO_DEPR’=>’-’   将参数分隔符”/”改为”-”
 *          修改后URL写为：
 *          http://localhost:8080/test/index.php?path=/home-index-index-name-aaa
 *          兼容模式可以与rewrite模式配合，通过”.htaccess”文件可以按如下方式访问：http://localhost:8080/test/home/index/index/name/aaa
 * 二、ThinkPHP路由
 *  1、ThinkPHP路由简介
 *      指为了简化URL地址，而按照特定规则设置的URL，通过配置文件中的配置由ThinkPHP将简化后的URL映射到真实的控制器和操作（方法名称）。我们将这种简化URL称为路由。
 *      路由的分类？
 *          按作用范围分：全局路由   模块路由
 *          按写法分：规则路由  正则表达式路由
 *      使用路由功能的前提条件？
 *          url必须支持PATHINFO模式或者兼容模式
 *          在应用配置文件中开启路由：URL_ROUTER_ON=>true
 *          全局路由：在Common模块的配置文件中配置
 *          模块路由：在对应模块的配置文件中配置    
 *      路由规则：
 *          指简化URL与真实地址之间的映射关系
 *          在配置文件中通过’URL_ROUTE_RULES’设置
 *          其值为一个数组，其每个元素都是一个路由规则    
 *  2、全局路由和模块路由
 *  3、规则路由
 *  4、正则表达式路由
 *  5、静态路由        
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 *          
 */