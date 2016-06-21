<?php
/**
 * 第二十章：ThinkPHP&MVC
 *  一、ThinkPHP的控制器使用
 *      1、Controller的定义
 *      thinkPHP的一般控制器定义：
 *          语法：
 *          class  控制器类名  extends  Controller{
 *              //操作方法：即控制器处理的一个业务流程
 *              public function  xxx(){  … }   
 *          }
 *          注意问题：
 *          1、Controller类的命名空间必须是Think\Controller
 *          2、控制器命名以Controller为后缀
 *          3、控制器文件命名规范为”控制器名称.class.php”
 *          4、控制器可以有多个操作行为(除非控制器类和操作绑定)，所有的操作行为必须是公共的
 *          5、控制器方法名应避免与系统方法名重名，如果确实有重名，可以在配置文件中通过‘ACTION_SUFFIX’ => ‘Action‘配置设置控制器方法名后缀，此时要求所有控制器方法名必须以”Action”结束
 *      2、自定义控制器基类(父类)
 *          A、在Application->Common下创建目录Controller
 *          B、在Controller目录下创建自定义控制器父类
 *          namespace  Common\Controller;
 *          use Think\Controller;
 *          class BaseController  extends Controller{
 *              public function __construct(){
 *                  parent::__construct();
 *              }
 *          }
 *          自定义控制器父类可以任意命名，但注意以Controller为后缀
 *          构造函数中一定要调用父类(Think\Controller)的构造函数
 *          类文件命名必须以”控制器类名.class.php”命名
 *          C、在任意模块的Controller目录下创建控制器类继承自定义控制器父类，
 *          本例以Home模块的IndexController为例
 *          namespace  Home\Controller;
 *          use Common\Controller\BaseController;
 *          class IndexController extends BaseController{
 *              public function __construct(){
 *                  parent::__construct();
 *              }
 *          }
 *          BaseController必须用use Common\Controller\BaseController;引用
 *          构造函数要调用父类构造函数
 *          类文件命名必须以”控制器类名.class.php”命名
 *      3、thinkPHP支持多层控制器，可以将控制器分为两层：
 *          访问控制器(Controller)：用于与外部交互，通过URL访问的
 *          事件控制器(Event)：负责内部事件响应，只能在内部调用
 *          事件控制器常用于多个控制器操作都会用到的公有接口方法的定义
 *          事件控制器或其它自定义层次的控制器，可以不需要继承Controller类(通常需要输出内容到模板才继承Controller)
 *      4、多级控制器*****
 *          概念：多级控制器是指控制器可以通过子目录把某个控制器层分组存放，例如Controller目录下控制器过多，
 *          我们可以把一些相关的控制器放在Controller目录的子目录中，从而分级管理。
 *          多级控制器的实现：
 *          1、在配置文件中设置控制器的分级层次，例如，我们设置2级目录的控制器层，例如：'CONTROLLER_LEVEL'=>2
 *          2、控制器文件的存放结构：
 *              |-Controller
 *              | |-User
 *              | | |--UserController
 *              | | |--UserTypeController
 *              | | |...
 *              | |-Admin
 *              | | |--UserController
 *              | | |--ConfigController
 *              | | |...
 *          3、多级控制器中的命名空间的定义：
 *              namespace Home\Controller\Admin;
 *              use Think\Controller;
 *              class UserController extends Controller{
 *                  public function test(){
 *                      echo "二级Controller测试";
 *                  }
 *              }
 *          4、多级控制器的访问：
 *              http://localhost:8000/tkp/index.php/Home/Admin/User/test
 *              其中Home表示模块名称，Admin表示Home里面Controller里面的子目录名称，User表示控制器类名称，test表示方法名称
 *          5、实例化控制器
 *              控制器实例化一般由框架自动完成，如果开发人员需要自己实例化控制器，可以使用以下方法：
 *              1、使用对象创建的基本语法：
 *              例如： $user = new \Home\Controller\UserController();
 *              2、使用框架提供的简化方法A()
 *              例如：
 *              $userController = A(‘Home/User’);等同于 $userController = new \Home\UserController();
 *              $blogController = A(‘Admin/Blog’);等同于$blogController = new \Admin\BlogController();
 *              A()默认是实例化Controller目录下的控制器，如果需要实例化其它分层控制器，需要按如下语法：
 *              $userController = A('Home/User','Event');
 *          6、前置操作和后置操作
 *              概念：指执行某个操作方法之前或之后会自动调用的方法。
 *              前置操作和后置操作只对访问控制器有效。
 *            前置操作定义语法：
 *              public  function  _before_操作方法名(){  …  } 
 *            后置操作定义语法：
 *              public  function  _after_操作方法名(){  …  }
 *            注意问题：
 *              1、前置方法和后置方法一般情况下不做任何输出
 *              2、有些操作方法里如果调用了exit等方法，会导致后置方法无法执行
 *          7、Action参数绑定
 *              概念：Action参数绑定是通过直接绑定URL地址中的变量作为操作方法的参数，
 *              Action参数绑定功能默认是开启的，其原理是把URL中的参数（不包括模块、控制器和操作名）
 *              和操作方法中的参数进行绑定。
 *              参数绑定设置：'URL_PARAMS_BIND' => true
 *              参数绑定的方式：按照变量名绑定和按照变量顺序绑定两种方式。
 *            7.1、按变量名称绑定：默认的Action参数绑定方式
 *              注意：
 *              1、按照变量名进行参数绑定的参数必须和URL中传入的变量名称一致，但是参数顺序不需要一致
 *              2、变量名绑定不一定由访问URL决定，路由地址也能起到相同的作用
 *              3、如果用户访问的URL地址没有提供对应的绑定数据，那么操作方法会报错，所以为了防止错误，
 *              建议给Action方法的参数提供默认值
 *              注意：参数绑定对post请求依然有效
 *              示例：见Home\Controller\UserController
 *                  public function test($id=-1,$name=null){
 *                      echo $id."----".$name;
 *                  }
 *                  正确的访问格式：
 *                  URL_MODEL为1时，pathinfo格式：
 *                    http://localhost:8000/tkp/index.php/Home/User/test/id/3/name/aaa
 *                    http://localhost:8000/tkp/index.php/Home/User/test/name/aaa/id/3
 *                  URL_MODEL为0时，普通格式：
 *                    http://localhost:8000/tkp/index.php?m=Home&c=User&a=test&id=3&name=aaa
 *                    http://localhost:8000/tkp/index.php?m=Home&c=User&a=test&name=aaa&id=3
 *                  URL_MODEL为3时，兼容格式：
 *                    http://localhost:8000/tkp/index.php?s=/Home/User/test/id/3/name/aaa
 *                    http://localhost:8000/tkp/index.php?s=/Home/User/test/name/aaa/id/3
 *            7.2、按变量顺序绑定
 *              必须先在配置文件中设置：'URL_PARAMS_BIND_TYPE' => 1
 *              此种方式对URL中的变量顺序要求必须和操作方法参数的顺序保持一致。
 *              此种方式对pathinfo和兼容方式有效。
 *              注意要点：
 *              1、按参数顺序绑定，url的写法应为：
 *                  http://localhost:8080/test/home/user/login/jack/123
 *                  jack代表操作login()的第一个参数uname
 *                  123代表操作login()的第二个参数upwd
 *              2、按顺序绑定参数对post提交依然有效，但此时要注意表单控件的顺序要与操作方法参数的顺序保持一致
 *          8、伪静态URL
 *              概念：为了满足SEO(搜索引擎优化)从而对控制器访问的URL设置虚拟的文件名以及特定后缀名，让控制访问的URL类似于普通的静态网页URL，这种设置称为伪静态URL。
 *              例如：
 *              http://localhost:8080/xxx/home/index/index  //原始控制器URL
 *              http://localhost:8080/xxx/home/index/index.html //伪静态
 *              伪静态URL的设置：
 *              ‘URL_HTML_SUFFIX’=>‘html‘  //设置URL以html为后置结束
 *              ‘URL_HTML_SUFFIX’=>‘‘ //默认设置，表示任何后缀均可
 *              //设置支持多种后缀，不是支持范围的后缀访问将报系统错误
 *              'URL_HTML_SUFFIX' => 'html|shtml|xml‘
 *              //设置禁止访问后缀，以这些后缀访问会返回404响应码
 *              'URL_DENY_SUFFIX' => 'pdf|ico|png|gif|jpg‘
 *              注意：设置URL_HTML_SUFFIX参数只针对访问操作方法，不传递任何额外数据有效，例如访问home/index/index.html，此时设置的后缀有效，如果附加了额外传递的数据，例如home/index/index/uname/aaa.html,此时后缀无效，此时如果想让后缀名有效，应采用路由设置
 *          9、URL大小写问题
 *              thinkPHP对控制器访问的URL在windows系统下不区分大小写，但在Linux系统下是区分大小写的。
 *              thinkPHP通过如下配置可以忽略URL大小写：
 *              'URL_CASE_INSENSITIVE' =>true
 *              URL忽略大小写注意问题（建议不要忽略大小写）：
 *              1、一旦开启了不区分URL大小写后，如果我们要访问类似UserTypeController的控制器，那么正确的URL访问应该是
 *                  http://localhost:8000/tkp/index.php/home/user_type/test
 *              2、URL不区分大小写并不会改变系统的命名规范，并且只有按照系统的命名规范后才能正确的实现URL不区分大小写
 *              3、建议使用U方法生成适应当前操作系统的URL
 *          10、URL生成
 *              U()函数的参数
 *              U方法的第二个参数支持数组和字符串两种定义方式，如果只是字符串方式的参数可以在第一个参数中定义
 *              示例：
 *                  U('User/test',array('id'=>1,'name'=>'aaa'));
 *                  U('User/test','id=1&name=aaa');
 *                  U('User/test?id=1&name=aaa');
 *              注意，以下设置为错误:
 *                  U('User/test/id/1/name/aaa');
 *              U()函数的伪静态
 *              U函数会自动识别当前配置的伪静态后缀，如果你需要指定后缀生成URL地址的话，可以显式传入，例如：
 *                  U('User/test','id=1&name=aaa','html');
 *              注意：如果在配置文件中配置了后缀限定，那么不指定第三个参数U函数也会生成对应的后缀
 *              在模板中调用U函数写法为：{:U('参数1', '参数2'…)} 
 *          11、AJAX返回 *****
 *              概念：指通过ajax请求访问控制器后返回数据给客户端的方式，我们称为ajax返回
 *              thinkPHP通过Controller类的ajaxReturn()方法返回ajax数据
 *              ajaxReturn()支持四种数据格式：json(默认格式),jsonp,xml和eval,并且可以通过配置扩展返回数据的格式
 *              ajaxReturn()调用
 *              $this->ajaxReturn($data);
 *              $this->ajaxReturn($data,”返回格式”);
 *              $this表示控制器对象
 *              $data表示要返回的数据，支持字符串，数字，数组和对象
 *              返回格式为四种，其中”json”和”jsonp”会将$data转为json字符串，”xml”会将$data转为xml字符串，”eval”就是用普通文本形式返回
 *              如何改变thinkPHP默认的ajaxreturn返回的数据类型？
 *              通过在配置文件中DEFAULT_AJAX_RETURN设置
 *          12、请求跳转
 *              在应用开发中，经常会遇到一些带有提示信息的跳转页面，例如操作成功或者操作错误页面，并且自动跳转到另外一个目标页面。系统的\Think\Controller类内置了两个跳转方法success和error，用于页面跳转提示，而且可以支持ajax提交。
 *              跳转的语法：
 *              $this->success(“提示信息”,”跳转地址”[,跳转时间]);
 *              $this->error(“提示信息”[,”跳转地址”,跳转时间]);
 *              注意：跳转地址要使用U函数生成，否则地址会错误
 *              success默认跳转时间为1秒，error默认3秒
 *              success是使用自动刷新跳转，error使用javascript:history.back(-1)，所以一般error不需要指定跳转地址
 *              success和error的视图模板
 *              success和error方法都可以对应的模板，默认的设置是两个方法对应的模板是:
 *                  'TMPL_ACTION_ERROR'=>THINK_PATH.'Tpl/dispatch_jump.tpl'
 *                  'TMPL_ACTION_SUCCESS'=>THINK_PATH.'Tpl/dispatch_jump.tpl'
 *              可自定义修改。
 *              模板中可使用如下模板变量：
 *                  $message    页面提示消息
 *                  $error      页面错误提示消息
 *                  $waitSecond 跳转等待时间 单位为秒
 *                  $jumpUrl    跳转页面地址
 *              success和error方法会自动判断当前请求是否属于Ajax请求，如果属于Ajax请求则会调用ajaxReturn方法返回信息。 
 *              ajax方式下面，success和error方法会封装下面的数据返回：
 *                  $data['info'] = $message; //提示消息内容
 *                  $data['status'] = $status;//状态 1表示success 0表示error
 *                  $data['url'] = $jumpUrl;  //跳转地址
 *          13、重定向
 *              Controller类提供了redirect()方法用于重定向。
 *              redirect()方法语法：
 *              $this->redirect(“地址表达式”,array(参数列表),跳转时间,”描述信息”);
 *              redirect()函数
 *              语法：redirect(“url地址”,秒数,”描述信息”);
 *              区别：
 *              控制器的redirect方法和redirect函数的区别在于前者是用URL规则定义跳转地址(即URL地址是动态生成的)，后者是一个纯粹的URL地址。
 *          14、输入变量（表单参数）
 *              概念：指获取通过请求发送等数据信息
 *              thinkPHP支持通过传统的全局数组获取数据，例如$_GET等。
 *              thinkPHP推荐使用I()函数获取请求数据信息。
 *              I()函数语法：
 *                  I('变量类型.变量名/修饰符',['默认值'],['过滤方法或正则'],['额外数据源'])
 *              变量类型指请求方式或输入类型，有：
 *                  get     获取GET参数
 *                  post    获取POST参数
 *                  param   自动判断请求类型获取GET、POST参数
 *                  request 获取请求参数
 *                  put     获取PUT参数
 *                  session 获取$_SESSION中保存的数据
 *                  cookie  获取$_COOKIE中保存的数据
 *                  server  获取$_SERVER中的数据
 *                  globals 获取$_GLOBALS这的数据
 *                  path    获取pathinfo模式的URL参数
 *                  data    获取其他类型的参数，需要配合额外的数据源参数
 *              注意：变量类型不区分大小写，变量名严格区分大小写。
 *              输入变量的用法：
 *              1、提取数据
 *                  I(“get.id”)  ==>  $_GET[“id”]
 *              2、提取数据，数据不存在赋予默认值
 *                  I(“get.id”,0) ==> 如果get中有id，则返回id值，否则返回0
 *              3、提取数据，使用方法对数据值过滤(处理)
 *                  I('get.name','','htmlspecialchars')==>htmlspecialchars($_GET[‘name’])
 *              4、获取输入变量的全部数据
 *                  I(“get.”)  ==>   $_GET
 *              5、param变量类型的使用
 *                  I(“param.age”)  此种用法会自动判断当前请求类型，并从对应的全局数组中获取数据
 *                  I(“age”)  param是I()函数的默认类型，所以此句等同于I(“param.age”)
 *              6、获取pathinfo模式下数据
 *                  http://localhost:8080/tkp/index.php/home/index/index/2016/4/5
 *                  I(“path.2”)  获取2016 
 *                  备注：path.1是从入口文件后第三个/后的数据开始计算数字，以后每个/数字加1
 *              输入变量的数据过滤：
 *                  Thinkphp默认的输入变量函数设置为： 'DEFAULT_FILTER' => 'htmlspecialchars'
 *                  所有通过I()函数提取的输入变量数据都会使用htmlspecialchars()函数处理。
 *                  可以通过应用配置文件重新指定过滤函数，也可以使用多个函数对输入变量数据过滤，例如： ‘DEFAULT_FILTER’ => ‘strip_tags,htmlspecialchars‘ ，此时执行I(“get.name”)等同于htmlspecialchars(strip_tags($_GET[“name”]))
 *                  调用I()函数忽略默认过滤方法：I(“get.name”,””,”函数名”);
 *                  调用I()函数不使用任何过滤函数：I(“get.name”,””,””)或I(“get.name”,””,false);
 *                  调用I()函数使用filter_var()函数过滤：
 *                  I(“get.email”,”wrong”, FILTER_VALIDATE_EMAIL)，验证成功返回email值，否则返回wrong,此处FILTER_VALIDATE_EMAIL表示按照email格式调用filter_var()函数验证$_GET[“email”],更多常量参看filter_var()
 *                  以上方法的简化写法：I(“get.email”,”wrong”,”email”);此处第三个参数必须是filter_list()函数支持的数组元素值
 *                  I()函数也支持正则表达式，语法：I(“get.name”,””,”/^\w{3,5}$/”);如果正则表达式不能通过，返回第二个参数的默认值
 *                  I()还支持变量修饰符，语法：
 *                      I('变量类型.变量名/修饰符')
 *                  修饰符如下：
 *                      s   表示强制转换为字符串
 *                      d   表示强制转换为整数类型
 *                      b   表示强制转换为布尔类型
 *                      a   表示强制转换为数组类型
 *                      f   表示强制转换为浮点数类型
 *          15、请求类型
 *              thinkPHP通过提供一些常量用于判断当前请求的类型，这些常量有:
 *              IS_GET      判断是否为get方式提交
 *              IS_POST     判断是否为post方式提交
 *              IS_PUT      判断是否为put方式提交
 *              IS_DELETE   判断是否为delete方式提交
 *              IS_AJAX     判断是否为ajax提交
 *              REQUEST_METHOD  获取当前请求提交类型
 *          16、空操作
 *              概念：空操作是指系统在找不到请求的操作方法的时候，会定位到空操作（_empty）方法来执行，利用这个机制我们可以用来定制错误页面和进行URL的优化。
 *              实现：在Controller控制器中提供_empty()方法
 *              public  function _empty([形参列表]){
 *                  方法体;
 *              }
 *              _empty()只对继承Controller类的控制器有效，其余控制器需要自己提供__call()方法实现。
 *          17、空控制器
 *              概念：空控制器的概念是指当系统找不到请求的控制器名称的时候，系统会尝试定位空控制器(EmptyController)，利用这个机制我们可以用来定制错误页面和进行URL的优化。
 *              实现：
 *              1、需要自定义EmptyController类
 *              class EmptyController extends Controller{
 *              }
 *              2、在EmptyController类中提供默认操作index()
 *              3、在index()方法中完成定制处理
 *              4、通过URL中使用不存在控制器访问EmptyController
 */
/**
 *  二、ThinkPHP的模型使用
 *      thinkphp中Model支持三种格式的数据库连接信息
 *      
 *      连贯操作（链式操作）
 *          where()函数的用法是ThinkPHP查询语言的精髓，可以完成包括普通查询、表达式查询、快捷查询、区间查询、组合查询
 *          在内的查询操作。where方法的参数支持字符串和数组。
 *          负责生成SQL语句中的where部分内容。
 *              a、使用字符串作为查询条件
 *              $userModel->where("cid>1")
 *              b、使用字符串作为查询条件，并通过预处理机制增加安全性*****(推荐使用)
 *              $userModel->where("usex='%s' and uage=%d",array("男",18))->select();
 *              $userModel->where("usex='%s' and uage=%d","男",18)->select();
 *              预处理语句中的占位符格式：
 *                  '%s'    --表示填入字符串(必须用引号括起来)
 *                  %d      --表示填入整数
 *                  %f      --表示填入浮点数
 *              $userModel->where("name=:name")->bind(':name','黄飞')->select();
 *              c、使用数组作为查询条件（只能来判断等值）
 *              $param["cid"] = 1;
 *              $classModel->where($param)->select(); 
 *          table()
 *              用于生成SQL语句中from部分的表名
 *              a、指定当前表名
 *                  $userModel->table("user")->select();
 *                  如果当前$user就是user表的模型对象，那么可以省略select();
 *              b、在当前模型指定另一个表的查询
 *                  $userModel->table("student")->select();
 *              c、指定表别名
 *                  $userModel->table("student s")->select();
 *              d、多表连接查询使用(注意：有列名称重复的情况下必须要写列别名)
 *                  $classModel->field("u.trueName,p.name pname,c.name cname")                 
 *                  ->table("tb_user u,province p,city c")
 *                  ->where("u.pid=p.pid and u.cid=c.cid")
 *                  ->select();
 *          field()用于在查询或增加或修改中指定要操作的字段，用于生成SQL语句中select和from之间的部分
 *              a、查询指定字段：
 *                  $userModel->field("uid,userName")->select();
 *              b、查询指定字段并使用别名
 *                  $userModel->field("uid,userName uname")->select();
 *              c、查询指定字段使用聚合函数
 *                  $userModel->field("count(userName)")->select();
 *              d、以数组的方式指定字段
 *                  $userModel->field(array("uid","uname"))->select();
 *                  $userModel->field(array("uid"=>"ID","uname"=>"NAME"))->select();
 *                  $userModel->field(array(count(*)=>"nums"))->select();
 *              e、查询全部字段数据
 *                  $userModel->select();
 *                  $userModel->field()->select();
 *                  $userModel->field("*")->select();
 *                  $userModel->field(true)->select();      
 *              f、查询排除指定字段
 *                  $userModel->field("uid",true)->select();
 *                  $userModel->field("uid,uname",true)->select();
 *                  $userModel->field(array("uid","uname"),true)->select();
 *              g、与create()函数配置过滤post请求提取指定的合法表单字段值(新增和修改)
 *                  $user->field("uname","upwd")->create();
 *                  //表示从$_POST请求中获取name和uname和upwd的数量，其余表达提交的数据将会被忽略
 *              h、与add()和save()组合过滤字段（新增和修改）
 *                  $user->field("uid,unmae")->add();
 *                  表示只增加uid、uname的数据值。
 *          order()函数
 *              用于对查询进行排序（默认升序），生成SQL语句对应order部分。
 *              a、对一个字段排序
 *                  $userModel->order("uid")->select();
 *                  $userModel->order("uid desc")->select();
 *              b、对多个字段排序              
 *                  $userModel->order("uid,uname desc")->select();
 *                  $userModel->order(array("uid","uname"=>"desc"))->select();
 *          limit()函数
 *              主要用于指定查询和操作的数量（分页查询）。
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
/**
 *  三、ThinkPHP的视图使用
 */