<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
//         echo $_GET["name"]."--".$_GET["pwd"];
//         echo ".    路由测试！".$_GET["userName"]."---".$_GET["userPass"];
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>^_^</h1><p>欢迎韬哥大驾光临 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    
    public function login(){
        $userName = $_POST["userName"];
        $userPass = $_POST["userPass"];
        echo $userName."--->".$userPass;
                 $i = $this->userModel->login($userName,$userPass);
                 if ($i == 1) {
                     $user = $this->userModel->loginUserByName($userName,$userPass);
                     session_start();
                     $_SESSION["loginUser"] = $user;
         
                     //
                     $uid = $_SESSION["loginUser"][0];
                     $secondMenu = $this->menuModel->loadTreeMenu($uid);
                     $_SESSION["secondMenu"] = $secondMenu;
         
                     header("location:http://localhost:8080/mytkp/welcome.php");
                 }else if ($i == 2) {
                     $_SESSION["loginError"] = "用户名不存在！";
                     header("location:http://localhost:8080/mytkp/login.php");
                 }else{
                     $_SESSION["loginError"] = "密码错误！";
                     header("location:http://localhost:8080/mytkp/login.php");
                }
         
    }
    
    public function test($year,$month,$day){

//         echo I("request.year")."/".I("request.month")."/".I("request.day");
//         echo I("param.year")."--".I("param.month")."--".I("param.day");
//         echo I("get.year")."/".I("get.month")."/".I("get.day");
//         echo $_GET["year"]."--".$_GET["month"]."--".$_GET["day"];
//         echo I("path.1")."/".I("path.2")."/".I("path.3");
//         $this->redirect(U("bbb"));
//         redirect("http://www.baidu.com");
    }
}