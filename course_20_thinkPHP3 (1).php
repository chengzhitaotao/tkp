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
 *      4、多级控制器
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
 *              概念：Action参数绑定是通过直接绑定URL地址中的变量作为操作方法的参数，Action参数绑定功能默认是开启的，其原理是把URL中的参数（不包括模块、控制器和操作名）和操作方法中的参数进行绑定。
 *              参数绑定设置：'URL_PARAMS_BIND' => true
 *              参数绑定的方式：按照变量名绑定和按照变量顺序绑定两种方式。
 *            7.1、按变量名称绑定：默认的Action参数绑定方式
 *              注意：
 *              1、按照变量名进行参数绑定的参数必须和URL中传入的变量名称一致，但是参数顺序不需要一致
 *              2、变量名绑定不一定由访问URL决定，路由地址也能起到相同的作用
 *              3、如果用户访问的URL地址没有提供对应的绑定数据，那么操作方法会报错，所以为了防止错误，建议给Action方法的参数提供默认值
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
 *          11、AJAX返回*********
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
 *                  I(“path.1”)  获取2016 
 *                  备注：path.1是从入口文件后第四个/后的数据开始计算数字，以后每个/数字加1
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
 *      1、模型的定义
 *          模型类通常继承Think\Model或者其子类，例如：
 *          class  TestModel  extends Model{   …    }
 *          模型的作用：对数据库表进行操作
 *          模型的命名规范：除去前缀的表名+模型层名称(Model)
 *          如果数据库表名与thinkphp默认命名规则不一致，则需要在模型类中设置数据表名称属性。
 *          tablePrefix 定义模型对应表的前缀，如果未定义则获取配置文件中的DB_PREFIX参数。
 *          tableName   不包含数据表前缀的表名称，一般情况下与模型类名称相同，只有当你的表名称和当前模型类名称不同时才需要定义。  
 *          trueTableName包含前缀的数据表实际名称，该属性不需要设置，只有当上面的规则都不使用的情况下才需要设置。
 *          dbName      定义模型当前对应的数据库名称，只有当你当前模型类要访问的数据库名称与配置文件中的不同时才需要定义。   
 *          注意：
 *            如果只是实现简单的增删改查操作（CURD），则毋须创建自定义的类去继承Model类，直接创建Model类的对象即可。
 *      2、模型实例化
 *          2.1、数据库连接信息
 *              ThinkPHP中Model支持三种格式的数据库连接信息：
 *                  a、字符串格式(可以写在配置文件中，也可以写在类中，入口文件中)
 *                      数据库类型://用户名:密码@数据库主机名或者IP:数据库端口/数据库名#字符集
 *                      mysql://root:lovo1234@localhost:3306/sys#utf8
 *                      注意：要求创建Model对象时必须传入三个参数，第一个表示表名称，第二个表示表前缀（一般情况下写空字符串即可），
 *                      第三个参数是数据库连接信息字符串。
 *                  b、数组格式(在配置文件或者模型类中定义)
 *                      $connection = array(
 *                          'db_type'   =>  'mysql',
 *                          'db_host'   =>  'localhost',
 *                          'db_user'   =>  'root',
 *                          'db_pwd'    =>  'lovo1234',
 *                          'db_port'   =>  3306,
 *                          'db_name'   =>  'sys',
 *                          'db_charset'=>  'utf8'
 *                      );
 *                  c、配置格式（推荐使用！在全局配置或者模块配置文件中定义）
 *                      'DB_TYPE'   =>  'mysql',
 *                      'DB_HOST'   =>  'localhost',
 *                      'DB_USER'   =>  'root',
 *                      'DB_PWD'    =>  'lovo1234',
 *                      'DB_PORT'   =>  3306,
 *                      'DB_NAME'   =>  'sys',
 *                      'DB_DSN'    =>  'mysql:host=localhost;dbname=sys',
 *                      'DB_CHARSET'=>  'utf-8',
 *                      'DB_PARAMS' =>  array(
 *                          \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION
 *                      )
 *                      'DB_CONFIG2'=>"mysql://root:lovo1234@localhost:3306/sys#utf8"
 *          2.2、D函数和M函数
 *          2.3、测试各种配置方式
 *      3、连贯操作（链式操作）    
 *          3.1、where()*****
 *              where()函数的用法是ThinkPHP查询语言的精髓，可以完成包括普通查询、表达式查询、
 *              快捷查询、区间查询、组合查询在内的查询操作。where方法的参数支持字符串和数组，
 *              其负责生成SQL语句中的where部分内容。
 *              a、使用字符串作为查询条件
 *                  $userModel->where("cid>1")
 *              b、使用字符串作为查询条件，并通过预处理机制增加安全性(推荐使用)
 *                  $userModel->where(“usex='%s' and uage=%d”,array(“男”,18))->select();
 *                  $userModel->where(“usex='%s' and uage=%d”,”男”,18)->select();
 *                  预处理语句中的占位符格式：
 *                      '%s'    --表示填入字符串（注意要用单引号括起来）
 *                      %d      --表示填入整数
 *                      %f      --表示填入浮点数
 *                  $userModel->where("name=:name")->bind(':name','张三丰')->select();
 *              c、使用数组作为查询条件
 *                  $param["cid"] = array("GT",1);  //相当于"cid>1"
 *                  $param["name"] = array("EQ","u20");//相当于"name='u20'"
 *                  $classModel->where($param)->select();
 *          3.2、table()*****
 *              用于生成SQL语句中from部分的表名
 *              a、指定当前表名
 *                  $userModel->table(“user”)->select();
 *                  如果当前$user就是user表的模型对象，那么可以省略table()
 *              b、在当前模型指定另一个表的查询
 *                  $userModel->table(“student”)->select();
 *              c、指定表别名
 *                  $userModel->table(“student s”)->select();
 *              d、多表连接查询中使用(注意：有列名称重复的情况下必须要写列别名)
 *                  $userModel->field("u.trueName,p.name pname,c.name cname")
 *                  ->table("tb_user u,province p,city c")
 *                  ->where("u.pid=p.pid and u.cid=c.cid")
 *                  ->select();
 *          3.3、field()*****
 *              用于在查询或增加或修改中指定要操作的字段，用于生成SQL语句中select和 from之间的部分
 *              a、查询指定字段：
 *                  $userModel->field(“uid,userName”)->select();
 *              b、查询指定字段并使用别名
 *                  $userModel->field(“uid,userName uname”)->select();
 *              c、查询指定字段使用聚合函数
 *                  $userModel->field(“count(userName)”)->select();
 *              d、以数组的方式指定字段
 *                  $userModel->field(array(“uid”,”uname”))->select();
 *                  $userModel->field(array(“uid”=>”ID”,”uname”=>”NAME”))->select();
 *                  $userModel->field(array(“count(*)”=>”nums”))->select();
 *              e、查询全部字段数据：
 *                  $userModel->select();
 *                  $userModel->field()->select();
 *                  $userModel->field(“*”)->select();
 *                  $userModel->field(true)->select();
 *              f、查询排除指定字段
 *                  $userModel->field(“uid”,true)->select();
 *                  $userModel->field(“uid,uname”,true)->select();
 *                  $userModel->field(array(“uid”,”uname”),true)->select();
 *              g、与create()函数配置过滤post请求提取指定的合法表单字段值（新增和修改）
 *                  $user->field(“uname”,”upwd”)->create();
 *                  //表示从$_POST请求中获取name为uname和upwd的数据创建数据对象，
 *                  其余表单提交的数据将会被忽略。
 *              h、与add()和save()组合过滤字段（新增和修改）
 *                  $user->field(“uid,uname”)->add();
 *                  表示只增加uid,uname的数据值
 *          3.4、order()函数：*****
 *              用于对查询进行排序(默认升序)，生成SQL语句对应order部分。
 *              a、对一个字段排序
 *                  $userModel->order(“uid”)->select();
 *                  $userModel->order(“uid desc”)->select();
 *              b、对多个字段排序
 *                  $userModel->order(“uid,uname desc”)->select();
 *                  $userModel->order(array(“uid”,”uname”=>”desc”))->select();
 *          3.5、limit()函数:*****
 *              主要用于指定查询和操作的数量（分页查询）。
 *              a、限定更新的数据量
 *                  $userModel->where(“uage=18”)->limit(3)->save(array(“upwd”=>”111”));
 *              b、返回查询的前N条数据
 *                  $userModel->limit(5)->select();
 *              c、分页查询
 *                  $userModel->limit(0,10)->select();
 *                  $userModel->limit("0,10")->select();
 *          3.6、page()函数:*******
 *              是完全为分页查询而诞生的一个人性化操作方法。
 *              分页查询
 *              注意：page(第几页，页大小)，page()函数不需要指定每页提取数据的起始位置，函数本身会自动计算
 *              $userModel->page(1,10)->select();//查询第一页的十条用户信息
 *              $userModel->page(2,10)->select();//查询第二页的十条用户信息
 *              等价于
 *              $userModel->page(“1,10”)->select();//查询第一页的十条用户信息
 *              $userModel->page(“2,10”)->select();//查询第二页的十条用户信息
 *          3.7、group()函数：
 *              通常用于结合合计函数，根据一个或多个列对结果集进行分组 。
 *              group方法只有一个参数，并且只能使用字符串。
 *              其生成SQL语句的group部分。
 *              用法：
 *              a、单个字段的分组
 *              //查询男，女用户的数量
 *              $userModel->field(“usex,count(*)  num”)->group(“usex”)->select();
 *              b、多个字段的分组
 *              //查询男，女用户每个年龄段的人数
 *              $userModel->field(“usex,uage,count(*) num”)->group(“usex,uage”)->select();
 *          3.8、having()函数:
 *              用于配合group方法完成从分组的结果中筛选（通常是聚合条件）数据。
 *              having方法只有一个参数，并且只能使用字符串，用于生成SQL语句的having部分。
 *              用法：
 *              a、对分组后的数据过滤
 *              //按年龄分组，显示超过2人以上的信息
 *              $userModel->field(“uage,count(*) num”)
 *              ->group(“uage”)->having(“count(*)>2”)->select();
 *          3.9、distinct()函数:
 *              用于返回不重复的唯一值，其参数为布尔值(true表示排除重复，false表示不排除重复)，
 *              作用是生成SQL语句中的distinct关键字。
 *              用法：
 *              1、排除重复
 *              //查询不重复的用户名
 *              $userModel->field(“uname”)->distinct(true)->select();
 *          3.10、union()函数：
 *              用于合并两个或多个 SELECT 语句的结果集。相当于union查询(表联合)
 *              a、排除重复
 *              $userModel->union(子查询)->union(子查询)->select();
 *              $userModel->union(array(子查询,子查询))->select();
 *              b、不排除重复
 *              $userModel->union(子查询,true)->union(子查询,true)->select();
 *              $userModel->union(array(子查询,子查询),true)->select();
 *          3.11、join()函数：
 *              用于根据两个或多个表中的列之间的关系，从这些表中查询数据。
 *              a、使用字符串参数表连接：
 *              //将用户表和指定表按条件连接查询，默认内联
 *              $user->join(“表名 on 连接条件”)->select();
 *              //将用户表和指定表按条件和类型进行连接查询，连接类型支持INNER,LEFT,RIGHT,FULL
 *              $user->join(“表名  on  连接条件”,”连接类型”)->select();
 *              注意：join()使用字符串参数可以调用多次，表示多个表进行连接
 *              mysql不支持full连接，连接类型不区分大小写。
 *              b、使用数组参数表连接：
 *              //将用户表和学生表按照id进行左外联然后查询
 *              $user->table(“user u”)->join(array(“left join student s on u.uid=s.sid”))->select();
 *              注意：
 *              a、join使用数组参数只能调用一次，且不能和字符串参数混合使用
 *              b、数组参数必须在元素中指明连接的类型，否则按inner处理
 *          3.12、index()函数：
 *              方法用于数据集的强制索引操作
 *              示例： $Model->index('user')->select();
 *              对查询强制使用user索引，user必须是数据表实际创建的索引名称
 *          3.13、fetchSql()函数：
 *              用于直接返回SQL而不是执行查询，适用于任何的CURD操作方法。
 *              示例： $result = M('User')->fetchSql(true)->find(1);
 *              输出result结果为： SELECT * FROM think_user where id = 1
 *      4、模型的CURD操作
 *          ThinkPHP提供了灵活和方便的数据操作方法，对数据库操作的四个基本操作（CURD）：
 *          创建、更新、读取和删除,CURD操作均支持连贯操作，具体参考附录。
 *          4.1、C(数据创建操作)：
 *              数据创建操作（C）使用模型类的create()或data()方法完成，其返回值为bool
 *              create()或data()方法的区别:
 *              a、从$_POST中提取表单数据创建数据对象
 *                  $userModel->create();
 *              b、通过自定义数组创建数据对象
 *                  $data = array(“uname”=>”aaa”,”upwd”=>”111”);
 *                  $userModel->create($data);
 *              c、通过stdClass对象创建数据对象
 *                  $data = new \StdClass(); 
 *                  $data->uname=“bbb”; 
 *                  $data->upwd=22; 
 *                  $userModel->create($data);
 *              注意：C操作创建的数据对象只是保存在内存中，没有调用add()或save()方法之前
 *              不会对数据库表作出真实改变，故此也可以在调用add()或save()方法之前对已经创建
 *              的数据对象作出改变。
 *          4.2、数据的A操作(增加记录)
 *              实现：通过调用模型对象的add()方法完成
 *              a、数据对象已经通过create()方法创建，可直接调用add()方法*****
 *                  $result = $userModel->add();
 *                  $result表示新插入数据的自动递增的主键值。
 *              b、如果在add方法之前调用field方法，则表示只允许写入指定的字段数据，
 *              其他非法字段将会被过滤。
 *                  $result = $userModel->field("userName","userPass")->add();
 *              c、通过filter方法可以对数据的值进行过滤处理
 *                  $result = $userModel->filter("strip_tags")->add();
 *              d、批量写入
 *                  $dataList[0] = array("userName"=>"bbb","userPass"=>"123321");    
 *                  $dataList[1] = array("userName"=>"ccc","userPass"=>"123321");
 *                  $userModel->addAll($dataList);
 *              e、如果写入了数据表中不存在的字段数据，则会被直接过滤
 *                  $data = array("userName"=>"bbb","userPass"=>"123321","aaa"=>123);
 *                  $userModel->data($data)->add();
 *          4.3、数据的U操作(数据的更新)
 *              实现：通过模型的save()方法完成，返回值为受影响行数，如果失败返回false
 *              a、基础方式：*****
 *                  //使用关联数组
 *                  $data = array("trueName"=>"张三丰");
 *                  $userModel->where("uid=2")->save($data);
 *                  //使用Model对象
 *                  $userModel->trueName = "张无忌";
 *                  $userModel->where("uid=2")->save();
 *              b、create()或data()之后的更新*****
 *                  $data = array("trueName"=>"张三丰");
 *                  $userModel->data($data)->where("uid=2")->save();
 *                  $userModel->create();
 *                  $userModel->where("uid=2")->save();
 *              c、使用field()方法过滤字段和filter方法过滤数据
 *                  $data = array("trueName"=>"张三丰","email"=>"<b>9999999@qq.com</b>");
 *                  $userModel->field("email")->filter("strip_tags")->save($data);
 *                  $userModel->data($data)->field("email")->filter("strip_tags")->save();
 *              d、通过字段更新(不需要调用save方法)
 *                  $userModel->setField("trueName","张三丰")->where("uid=2");
 *                  $data = array("trueName"=>"张三丰","email"=>"9999999@qq.com");
 *                  $userModel->setField($data)->where("uid=2");
 *              e、统计字段(一般整型或浮点型字段)更新（需与where()配合完成,不需要调用save方法）
 *                  $userModel->where("uid=1")->setInc("workYear");//工作年限加1
 *                  $userModel->where("uid=1")->setInc("workYear",2);//工作年限加2
 *                  $userModel->where("uid=1")->setDec("workYear");//工作年限减1
 *                  $userModel->where("uid=1")->setDec("workYear",2);//工作年限减2
 *          4.4、数据的D操作(数据的删除)*****
 *              实现：通过模型的delete()方法完成
 *              语法：
 *              //通过id删除一条
 *              $user->delete(“1”);
 *              //通过id删除多条
 *              $user->delete(“1,2,3”);
 *              //通过条件限定删除
 *              $user->where(“条件”)->delete();
 *              //通过order()和limit()删除
 *              $user->where(条件)->order(“排序字段”)->limit(限定行数)->delete();
 *              注意：delete()函数返回删除的行数，如果是0表示没有删除，false表示SQL语句错误
 *          4.5、数据的R操作(数据的查询操作)
 *              实现：
 *              1、使用find()函数查询一行数据*****
 *                  a、不带条件查询：
 *                  $user->find();  //等于select  * from user limit 0,1;
 *                  b、带条件查询：
 *                  $user->where(“usex=‘男’“)->find();//等于select * from user where usex=‘男’ limit 0,1
 *              2、使用select()函数查询多行数据*****
 *                  a、不带条件的查询
 *                  $user->select(); //等于select  * from user;
 *                  b、带条件的查询
 *                  $user->where(条件)->select();
 *              3、读取字段的数据：
 *                  读取字段值其实就是获取数据表中的某个列的多个或者单个数据，
 *                  最常用的方法是 getField()。
 *                  a、查询单个字段的值
 *                      $user->getField(“uid”);
 *                      此方法默认返回该字段第一行的数据值，如果想返回该字段的多行数据，
 *                      则：$user->getField(“uid”,true);
 *                  b、查询两个字段的值
 *                      $user->getField(“uid,uname”);
 *                      此方法返回一个关联数组，以uid字段的值作为key,以uname字段的值作为value
 *                  c、查询两个以上字段的值
 *                      $user->getField(“uid,uname,usex”);
 *                      此方法返回一个关联二维数组，以第一个字段值作为key,
 *                      以三个字段的值放入一个array中作为value
 *                  d、查询两个以上的字段，并指定分隔符号
 *                      $user->getField(“uid,uname,usex”,”:”);
 *                      此方法返回一个关联数组，以uid字段的值为key,以uname字段值:usex字段值拼接为一个字符串作为value
 *                  e、查询字段(不限定个数)返回限定行
 *                      $user->getField(“uid”,2);
 *                      $user->getField(“uid,uname”,2);
 *              4.6、原生SQL语句的使用*****
 *                  a、对查询的操作：函数：query()
 *                      语法：$result = $model->query(“sql查询语句”);
 *                      $result为false表示查询语句错误，成功返回结果集(与select()的返回结果相同)
 *                      示例：$user->query(“select * from user”);
 *                  b、对增删改的操作：函数：execute()
 *                      语法：$result = $model->execute(“sql增删改语句”);
 *                      $result为false表示语句错误，成功返回受影响行数
 *                      示例：$user->execute(“update user set uname=‘aa’ where usex=‘男’”)；
 *                  c、query()和execute()支持sql的预处理机制，可以防止SQL注入攻击
 *                      示例：
 *                      $model->query('select * from user where id=%d and status=%d',$id,$status); 
 *                      //或者
 *                      $model->query('select * from user where id=%d and status=%d',array($id,$status));
 */


/**
 *  三、ThinkPHP的视图使用
 *      1、定义
 *          thinkPHP的视图采用模板(不是Smarty模板，是thinkPHP自定义的模板)，每个模块的模板都是单独定义的，
 *          为了对模板文件进行有效的管理，thinkphp规定了默认的模板文件目录结构：
 *          视图目录/[模板主题/]控制器名/操作名+模板后缀
 *          Home/view/Class/loadAllClasses.html
 *          视图目录：thinkPHP默认的视图目录是当前模块的view目录，可以通过在配置文件中设置：
 *          ‘DEFAULT_V_LAYER’ => ‘自定义视图目录名'
 *          
 *          
 *          模板主题：用于一个模块需要多套不同的模板文件时使用该功能，默认thinkPHP没有开启模板主题功能：
 *          可能在今后的大型项目中用到!
 *          a、开启模板主题：在配置文件中配置：‘DEFAULT_THEME’ => ‘自定义模板主题名称‘(此处定义的是默认模板主题)
 *          b、采用模板主题必须要在视图目录下创建对应主题名称的目录(手动创建)
 *          c、在视图渲染输出之前，我们可以通过动态设置来改变需要使用的模板主题：
 *          $this->theme(‘blue’)->display(‘add’);，不加theme表示使用默认模板主题
 *          1.1、控制器名：
 *              为当前控制器类名称(不要Controller)，且大小写保持一致，
 *              如控制器类名称UserController，则此文件夹名称应该为User
 *              此目录需要手动在视图目录下创建(如果开启了视图模式，就在视图模式目录下创建)。
 *          1.2、操作名：就是当前执行渲染视图的控制器方法名（按此规则设置的是当前操作方法访问的默认视图模板名称，
 *              可以自定义改变，不过改变后要在display中传入参数指明要使用的模板文件名称）。
 *          1.3、模板后缀：thinkPHP默认为”.html”，可以通过配置文件中设置：
 *              ‘TMPL_TEMPLATE_SUFFIX’=>‘.自定义后缀名‘进行修改
 *              如果觉得目录结构太深，可以通过设置 TMPL_FILE_DEPR 参数来配置简化模板的目录层次，例如设置：
 *              'TMPL_FILE_DEPR‘=>‘_’，默认的模板文件就变成了：./Application/Home/View/User_add.html
 *          1.4、在控制器操作方法中调用display()方法注意问题：
 *              1.4.1、语法： display('[模板文件]'[,'字符编码'][,'输出类型']) 
 *              1.4.2、如果使用thinkPHP默认的视图模板文件定义规则：
 *              
 *                  a.假设访问的是Home/Index/index，操作完成后想打开Home/View/Index/index.html视图模板，则可以如下调用：
 *                  $this->display()；$this->display(“”);$this->display(“index”)
 *                  
 *                  b.假设访问的是Home/Index/index，操作完成后想打开Home/View/Index/other.html，则可以如下调用：
 *                  $this->display(“other”);
 *                  
 *                  c.假设访问的是Home/Index/index，操作完成后想打开Home/View/Test/test.html，则可以如下调用：
 *                  $this->display(“Test/test”)  $this->display(“Test:test”);
 *                  
 *                  d、假设访问的是Home/Index/index，操作完成后想打开Defalut模板主题下的模板文件，则可以如下调用：
 *                  $this->theme(“Default”)->display();//display()写法参照之前
 *                  
 *          1.5、改变所有模块的模板文件目录：
 *              可以通过设置TMPL_PATH常量(入口文件中设置)来改变所有模块的模板目录所在，例如：
 *              define(‘TMPL_PATH’,‘./Template/’)，那么原来的./Application/Home/View/User/add.html
 *              变成了./Template/Home/User/add.html
 *          1.6、改变某一个模块的模板文件目录
 *              可以在模块配置文件中设置VIEW_PATH参数单独定义某个模块的视图目录，例如：
 *              ‘VIEW_PATH’=>‘./Theme/’，那么原来的./Application/Home/View/User/add.html
 *              变成了./Theme/User/add.html
 *          1.7、获取模板的地址
 *              为了更方便的输出模板文件，thinkPHP封装了一个T函数用于生成模板文件名
 *              用法： T([资源://][模块@][主题/][控制器/]操作,[视图分层])
 *                  T("Public/menu")        //返回 当前模块/View/Public/menu.html
 *                  T("blue/Public/menu")   //返回 当前模块/View/blue/Public/menu.html
 *                  T("Public/menu","Tpl")  //返回 当前模块/Tpl/Public/menu.html
 *                  T("Public/menu")        //如果TMPL_FILE_DEPR为_，返回 当前模块/view/Public_menu.html
 *                  T("Public/menu")        //如果TMPL_TEMPLATE_SUFFIX为.tpl，当前模块/view/Public/menu.tpl
 *                  T("Admin@Public/menu")  //返回 Admin/View/Public/menu.html
 *              在display方法中直接使用T函数：
 *                  $this->display(T('Admin@Public/menu'));
 *          1.8、获取模板文件的内容
 *              如果需要获取渲染模板的输出内容而不是直接输出，可以使用fetch方法。
 *              fetch方法的用法和display基本一致（只是不需要指定输出编码和输出类型），例如： 
 *              $content = $this->fetch('Member:edit');
 *              获取模板内容后，如果要输出，需要调用show()方法， show方法的调用格式：
 *              show('渲染内容'[,'字符编码'][,'输出类型'])例如，$this->show($content);
 *              也可以指定编码和类型： $this->show($content, 'utf-8', 'text/xml');
 *          1.9、thinkPHP的模板引擎：
 *              thinkPHP默认的模板引擎名称为$think
 *              thinkPHP默认的模板变量定界符是”{}”,可以通过在配置文件中配置如下两项改变默认的定界符： 
 *              'TMPL_L_DELIM'=>'<{', 'TMPL_R_DELIM'=>'}>'
 *      2、thinkPHP模板引擎的使用：
 *          2.1、变量输出：
 *              a、普通变量：{$变量名}
 *              b、数组变量：索引数组和关联数组均可 {$变量名.索引名}  {$变量名[索引名]}
 *              c、对象变量：{$变量名:属性名} {$变量名->属性名}
 *          2.2、系统变量使用：{$Think.系统变量名.元素索引名}
 *              示例： {$Think.server.script_name} // 输出$_SERVER['SCRIPT_NAME']变量
 *              支持输出 $_SERVER、$_ENV、 $_POST、 $_GET、 $_REQUEST、$_SESSION和 $_COOKIE变量
 *          2.3、使用函数：支持系统函数和自定义函数
 *              语法：
 *              {$变量名|函数名}  {$str|md5}
 *              {$变量名|函数名=参数1,…,参数n}
 *              {$str|date=‘Y-m-d’,###} ###代表str的值，是date的第二个参数需要的值
 *              {$变量名|函数名1|函数名2}
 *              {$str|md5|substr=0,3} 或{$str|md5|substr=###,0,3} 
 *              {:函数名($变量名)}
 *              {:substr($str,0,3)}
 *          2.4、默认值输出 {$变量名|default=“默认值”}
 *          2.5、使用运算符
 *              我们可以对模板输出使用运算符，包括对+ - * / % ++ --的支持。
 *          2.6、三目运算符
 *              {$status==1?'正常':'不正常'}
 *              {$info['status']==1?$info['msg']:$info['error']}
 *          2.7、标签库：
 *              内置标签库标签的使用：
 *              A、volist标签，用于循环遍历数组(一般多是查询的结果集)
 *                  注意:：name属性填控制器方法中保存的二维数组名称，不加$符号
 *                  id属性是每次循环的数组元素的变量名称，也不加$符号，但在标签之间使用时要加$符号
 *                a、遍历所有数据*****
 *                  <volist name=“数组名” id=“循环变量名”> … </volist>
 *                b、遍历部分数据*****
 *                  <volist name=“” id=“” offset=“起始索引” length=“长度”> ... </volist>
 *                c、控制输出的记录*****
 *                  mod属性值为任意整数，表示以该值和数组的索引去做模运算，在循环中可以通过{$mod}提取运算结果，从而通过该结果执行某些操作
 *                  <volist name=“” id=“”  mod=“2”></volist>
 *                d、数组为空时的提示输出*****
 *                  <volist name=“” id=“” empty=“提示信息”>…</volist>
 *                  注：如果提示信息中包含html标记内容，请先将提示信息放入模板变量中，然后在empty=“$变量名”
 *                e、输出循环当前次数
 *                  <volist name=“” id=“” key=“自定义统计循环次数变量名，默认为i”></volist>
 *                f、输出索引
 *                  <volist name=“” id=“” >   {$key} </volist>
 *                  {$key}对索引数组就是数字(从0开始),对关联数组就是字符串
 *              B、foreach标签：简单快速循环数组
 *                  <foreach name=“数组名” item=“循环变量名” [key=“数组索引变量名”]>
 *                  </foreach>
 *              C、for标签：用于确定次数的循环
 *                  <for start=“起始值” end=“结束值” [comparison=“循环条件比较符号，默认lt(<)，可以改为gt(>)”]
 *                  [step=“步长，取值整数”] [name=“循环变量，默认是i”]>
 *                  </for>
 *              D、switch标签：多重等值判断
 *                  <switch name=“变量”>
 *                      <case value=“比较的值” [break=“0”]></case>
 *                      <default />default…
 *                  </switch>
 *                  变量：可以支持系统变量或函数处理的变量值
 *                  比较的值支持多个，以”|”连接，例如value=“5|6|7”表示只要有一个满足即可
 *                  break=“0”表示不生成break关键字
 *                  default表示默认操作  
 *              E、比较标签：用于简单的判断，条件成立执行内容
 *                  <比较标签名 name=“变量名” value=“值”>内容</比较标签名>
 *                  比较标签名如下：
 *                      eq或者equal       相等          ==
 *                      neq或者notequal   不等          !=
 *                      gt               大于         >
 *                      egt              大于等于   >=
 *                      lt               小于         <
 *                      elt              小于等于   <=
 *                      heq              恒等于      ===
 *                      nheq             不恒等于   !==
  *             F、if标签：做分支判断(可实现单分支，双分支和多分支)
  *                 <if condition=“条件”> 内容  </if>
  *                 
  *                 <if condition=“条件”> 
  *                     内容1 
  *                 <else/>
  *                     内容2
  *                 </if>
  *                 
  *                 <if condition=“条件”>
  *                     内容1 
  *                 <elseif condition=“条件”/>
  *                     内容2
  *                 <else/>
  *                     内容3
  *                 </if>
  *     3、thinkPHP修改默认模板为Smarty模板(不建议)
  *         1、下载最新的smarty模板
  *         2、将下载后的压缩文件解压缩，然后将目录下的libs中的全部内容复制到ThinkPHP/Library/Vendor/Smarty目录下
  *         3、在Application/Common/Conf/config.php下完成如下配置：
  *             'TMPL_ENGINE_TYPE'=>'Smarty',
  *             //以下配置如果不需要更改，则可以不需要配置。
  *             'TMPL_ENGINE_CONFIG'=>array(
  *                 'caching'=>false,       //是否启用缓存
  *                 'cache_lifetime'=>60*60,//缓存时间 单位秒
  *                 'left_delimiter'=>'<{', //设置左边界符
  *                 'right_delimiter'=>'<{' //设置右边界符
  *             )
  */