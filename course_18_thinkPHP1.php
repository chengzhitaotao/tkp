<?php
/**
 * 第十八章：ThinkPHP下载和配置
 *  1、什么是ThinkPHP?
 *    ThinkPHP是一个快速、兼容而且简单的轻量级国产PHP开发框架，诞生于2006年初，原名FCS，
 *      2007年元旦正式更名为ThinkPHP，遵循Apache2开源协议发布，从Struts结构移植过来并做了改进和完善，
 *      同时也借鉴了国外很多优秀的框架和模式，使用面向对象的开发结构和MVC模式，融合了Struts的思想和TagLib（标签库）、
 *      RoR的ORM映射和ActiveRecord模式。
 *    RoR:RoR是Ruby on Rails的缩写。Ruby on Rails是一个用于编写网络应用程序的框架，
 *      它基于计算机软件语言Ruby，给程序开发人员提供强大的框架支持。
 *  2、ThinkPHP下载和安装
 *    地址：   http://www.thinkphp.cn/down/framework.html
 *    thinkPHP在web项目中的安装
 *      将下载后的thinkPHP压缩包中的内容全部复制到项目的根目录下。
 *    目录介绍：
 *      Application  -》 我们写的应用程序代码就放这里面
 *      Public       -》 公共资源目录
 *      ThinkPHP     -》 ThinkPHP框架目录
 *          ThinkPHP/Common -》核心公共函数目录
 *          ThinkPHP/Conf   -》核心配置目录
 *          ThinkPHP/Lang   -》核心语言包目录
 *          ThinkPHP/Library-》核心类库目录
 *              ThinkPHP/Library/Behavior   -》行为类库目录
 *              ThinkPHP/Library/Org        -》org类库目录
 *              ThinkPHP/Library/Think      -》核心类库目录
 *              ThinkPHP/Library/Vendor     -》第三方类库目录
 *          ThinkPHP/Mode   -》框架应用模式目录
 *          ThinkPHP/Tpl    -》系统模版目录
 *          LICENSE.txt     -》授权文件
 *          logo.png        -》logo图标
 *          ThinkPHP.php    -》框架入口文件
 *      index.php    -》 项目入口文件
 *      .htaccess    -》 URL_MODEL之REWRITE模式的配置
 *      composer.json-》 可删除
 *      README.md    -》 可删除
 *    thinkPHP采用单一入口的模式进行部署和访问，一个应用应有统一(但不是唯一的)入口，所有的应用都是从入口开始。
 *    thinkPHP应用入口文件(默认是项目下的index.php)的作用：
 *      =》定义框架路径、项目路径（可选）
 *      =》定义调试模式和应用模式（可选）
 *      =》定义系统相关常量（可选）
 *      =》载入框架入口文件ThinkPHP目录下的ThinkPHP.php（必须）
 *    建议将相对路径改为绝对路径，这样可以提高thinkPHP的加载效率。
 *  3、自动生成
 *      第一次访问thinkPHP的入口文件，thinkPHP会自动生成默认应用模块home
 *      在Application/下自动生成Common、Home、Runtime三个文件夹
 *      Application/Common      -》应用公共模块
 *          Application/Common/Common   -》应用公共函数目录
 *          Application/Common/Conf     -》应用公共配置文件目录
 *          Application/Common/index.html -》目录安全文件，空白
 *      Application/Home        -》默认生成的Home模块
 *          Application/Home/Common -》模块公共函数目录
 *          Application/Home/Conf   -》模块配置文件目录
 *          Application/Home/Controller -》模块控制器目录
 *          Application/Home/Model  -》模块模型层目录
 *          Application/Home/View   -》模块视图层目录
 *          Application/Home/index.html -》目录安全文件，空白
 *      Application/Runtime     -》运行时目录
 *          Application/Runtime/Cache   -》缓存目录
 *          Application/Runtime/Data    -》数据目录
 *          Application/Runtime/Logs    -》日志目录
 *          Application/Runtime/Temp    -》临时目录
 *      3.1、目录安全文件
 *          3.1.1、什么是目录安全文件？
 *          在thinkPHP自动生成目录中，我们看到每个目录下有一个空白的index.html文件，此文件即为目录安全文件。生成此文件的目的是避免某些服务器开启了目录浏览权限后可以直接在浏览器输入URL地址查看目录。thinkPHP默认是开启目录安全文件机制。
 *          3.1.2、如何更改目录安全文件生成的名称？
 *          在入口文件index.php中增加如下配置：
 *          define(“DIR_SECURE_FILENAME”,”自定义文件名.html”);
 *          3.1.3、如何关闭目录安全文件机制？
 *          在入口文件index.php中增加如下配置：
 *          define(“BUILD_DIR_SECURE”,false);
 *      3.2、模块
 *          3.2.1、什么是模块？
 *          thinkPHP采用模块化设计架构，模块代表web应用中的一组相关完整功能的集合，每个模块都支持MVC模式，且可以方便的部署或卸载。
 *          thinkPHP的所有模块都放在Application目录下(此目录可以改名)
 *          thinkPHP会自动生成Home模块和Common目录
 *          Home：可定义为web的前台模块(前台指用户使用的系统功能部分)
 *          Common：公共模块，不能直接访问的模块
 *          注意：由于thinkPHP采用多层MVC机制，所以每个模块下除Conf和Common目录外，其它目录结构可以自由灵活设置。
 *      3.3、控制器
 *          MVC模式组成之一，用于定义业务流程控制。
 *          thinkPHP的控制器由两部分组成：
 *            核心控制器：负责调度控制(即将请求交给那个业务控制器)
 *              此控制器由ThinkPHP->Library->Think->App.class.php定义
 *            业务控制器：开发人员提供的负责业务过程控制的类
 *              此控制器放在Application->模块->Controller目录下
 *            业务控制器的定义：
 *              控制器类名命名规范：
 *                  控制器名称(每个单词首字母大写)+Controller
 *              控制器文件命名规范：
 *                  控制器类名+.class.php
 *              控制器类命名空间定义规范：
 *                  控制器文件所在的目录结构即为其命名空间，其目录结构格式为：模块名\Controller
 *  4、ThinkPHP开发规范
 *      4.1、命名规范：
 *          1、类文件以”.class.php”为后缀
 *          2、类的命名空间与所在模块的路径保持一致，例如：Home->Controller->TestController.class.php，那么这个控制器TestController的命名空间应为Home\Controller
 *          3、类名和文件名保持一致
 *          4、函数文件、配置文件的后缀”.php”
 *          5、函数命名使用小写字母和下划线的形式，例如get_ip
 *          6、类的方法命名使用驼峰命名法，首字符使用小写字母或下划线(私有方法命名使用)，例如getUserName(),_parseType()
 *          7、属性的命名规范等同于方法的命名规范，属性一般以名词开头
 *          8、常量以大写字母和下划线命名，例如HAS_ONE
 *          9、配置文件中的参数以大写字母和下划线命名，例如HTML_CACHE_ON
 *          10、语言变量以大写字母和下划线命名，例如MY_LANG，以下划线打头的语言变量通常用于系统语言变量，例如 _CLASS_NOT_EXIST_
 *          11、对变量的命名没有强制的规范，可以根据团队规范来进行
 *          12、ThinkPHP的模板文件默认是以.html 为后缀（可以通过配置修改）
 *          13、数据表和字段采用小写加下划线方式命名，并注意字段名不要以下划线开头，例如 think_user 表和 user_name字段是正确写法
 *      4.2、开发建议：
 *          遵循框架的命名规范和目录规范；
 *              开发过程中尽量开启调试模式，及早发现问题；
 *              多看看日志文件，查找隐患问题；
 *              养成使用I函数获取输入变量的好习惯；
 *              更新或者环境改变后遇到问题首要问题是清空Runtime目录
 *          ThinkPHP配置：
 *              ThinkPHP提供了灵活的全局配置功能，采用最有效率的PHP返回数组方式定义，支持惯例配置、公共配置、模块配置、调试配置和动态配置。
 *              对于有些简单的应用，你无需配置任何配置文件，而对于复杂的要求，你还可以增加动态配置文件。
 *              系统的配置参数是通过静态变量全局存取的，存取方式简单高效
 *              ThinkPHP的配置文件中可以写框架预定义的配置，也可以写开发人员自定义的配置信息
 *          ThinkPHP配置格式：
 *              thinkPHP默认采用数组的配置模式,配置文件默认后缀”.php”，但同时也支持xml,json,yaml,ini以及自定义配置
 *              (请注意，不论何种配置模式最终都会解析成php管关联数组，键不区分大小写，但是建议写成大写)，如下：
 *              return array(
 *                  "DEFAULT_MODE"          =>  "Index",
 *                  "URL_MODE"              =>  "2",
 *                  "SESSION_AUTO_START"    =>  true,
 *                  "USER_CONFIG"           =>  array(
 *                      "USER_AUTH" => true,
 *                      "USER_TYPE" => "2"
 *                  )
 *              );
 *          ThinkPHP的配置文件自动加载，加载顺序为：
 *              惯例配置->应用配置->模式配置->调试配置->状态配置->模块配置->扩展配置->动态配置
 *              1、惯例配置：
 *              作用：用于定义框架最一般的配置信息(此配置文件由框架提供)
 *              位置：ThinkPHP/Conf/convention.php
 *              2、应用配置：
 *              作用：表示在调用模块之前加载的配置信息
 *              位置：Application/Common/Conf/config.php
 *              3、模式配置：
 *              作用：表示使用不同请求模式(请求的写法格式)访问web项目所采用的配置信息
 *              位置：Application/Common/Conf/config_应用模式名称.php
 *              注意：此种配置只有在指定的模式下才能生效，模式配置在入口文件中可以通过URL_MODEL设置
 *              4、调试配置
 *              作用：开启调试模式时自动加载的配置信息
 *              位置：
 *              惯例调试配置文件： ThinkPHP/Conf/debug.php
 *              应用调试配置文件： Application/Common/Conf/debug.php(需要自定义添加)
 *              5、状态配置
 *              作用：在不同的应用场景中加载的配置信息，例如项目期间需要经常在单位和家里完成项目，两个地点的数据库运行环境不同，此时就可以针对数据库运行环境进行状态配置
 *              位置： Application/Common/Conf/自定义状态配置文件名.php
 *              注意：使用状态配置需要在应用入口文件中加入如下设置：
 *              define(“APP_STATUS”,”要使用的状态配置文件名称”);
 *              6、模块配置
 *              作用：在模块使用前自动加载的配置文件信息
 *              位置： Application/当前模块名/Conf/config.php
 *              注意：模块也可以支持单独对本模块设置的状态配置信息文件，其位置和命名为： Application/当前模块名/Conf/应用状态.php，使用与状态配置一样，区别在于状态配置时对所有模块都其作用，而模块状态配置只对该模块起作用
 *              7、动态配置
 *              作用：在运行过程中修改配置文件中的配置信息或者增加新的配置信息
 *              注意：此种配置只对当前请求有效，不会真正改变配置文件的信息
 *              方法：C(“配置参数名称”,配置参数值)
 *              C(“配置参数名称.二级参数名称”,二级参数值);//此种方式为配置参数本身是一个数组的情况下使用
 *              8、扩展配置
 *              作用：在基础配置之上附加的配置信息，一般用于在应用中或模块中可选择的不同配置信息在基础配置上进行附加。
 *              使用：
 *              A、在Application/Common/Conf/config.php中使用
 *              “LOAD_EXT_CONFIG”=>”附加配置文件名称1,…,附加配置文件名称n”
 *              此语法表示将Conf目录下的指定名称的配置文件信息加入到基础配置文件config.php中，此时附加配置文件中的信息都作为一级配置参数加入到config.php中，提取这些信息使用C(“配置参数名称”)即可。
 *              “LOAD_EXT_CONFIG”=>array(“key”=>”附加配置文件名称1”,…,”key”=>”附加配置文件名称n”)
 *              此语法表示将Conf目录下指定名称的配置文件信息以二级参数的形式加入到config.php中，这时提取这些信息就需要用如下形式：C(“key.附加配置文件中的参数名”)
 *              B、在Application/模块名/Conf/config.php中使用方式与上相同
 *  5、C()函数在thinkPHP配置中的作用：
 *      5.1、读取配置信息
 *          //获取配置参数信息，返回字符串，如果不存在，返回null
 *          C(“配置参数”)；
 *          //检查配置参数是否为null，如果为null则动态设置配置参数，并返回第三个参数的值
 *          C(“配置参数”,null,”设置值”);
 *          //获取二级配置参数的值，此用法针对配置文件中某个参数值为数组的情况
 *          C(“配置一级参数.配置二级参数”);
 *      5.2、动态设置配置信息
 *          C(“参数名”,”参数值”);
 *          C(“一级参数名.二级参数名”,参数值)；
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */