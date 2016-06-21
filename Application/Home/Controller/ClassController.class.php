<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Home\entity\Role;



class ClassController extends Controller{
    private $classModel;
    
    public function __construct(){
        parent::__construct();
        //直接实例化Model类，用于执行简单的增删改操作
//         $this->classModel = new Model("province","",C("THINKPHP_DSN"));
        $this->classModel = M("class");// new Model("province");
    }
    
    
    public function classManage(){
        $this->display();
    }
    
    public function loadClassByPage($pageNo=1,$pageSize=10,$className=null, $createtime1=null,
        $createtime2=null,$headerName=null,$benintime1=null,$benintime2=null,$managerName=null,
        $endtime1=null,$endtime2=null,$status=-1){
        
        $sql=" from class c,tb_user u1,tb_user u2 where c.headerid=u1.uid and u2.uid=c.managerid";
      
        if (null != $className){
            $sql .= " and c.name like '%$className%'";
        }
        if(null != $createtime1){
            $sql .= " and c.createTime >= '".$createtime1."'";
        }
        if(null != $createtime2){
            $sql .= " and c.createTime <= '".$createtime2."'";
        }
        
        
        if (null != $headerName){
            $sql .= " and u1.trueName like '%$headerName%'";
        }
        if(null != $benintime1){
            $sql .= " and c.beginTime >= '".$benintime1."'";
        }
        if(null != $benintime2){
            $sql .= " and c.beginTime <= '".$benintime2."'";
        }
        
        if (null != $managerName){
            $sql .= " and u2.trueName like '%$managerName%'";
        }
        if(null != $endtime1){
            $sql .= " and c.endTime >= '".$endtime1."'";
        }
        if(null != $endtime2){
            $sql .= " and c.endTime <= '".$endtime2."'";
        }
        
        //状态
        if($status > 0){
            $sql .= " and c.status = $status";
        }
        
        
        
        
        $count = $this->classModel->query("select count(*) as cc".$sql)[0]["cc"];
        $page["total"] = $count;
        
        $begin = ($pageNo-1)*$pageSize;
        $rows = $this->classModel->query("select c.cid,c.name,c.classtype,c.status,
            c.createtime,c.begintime,c.endtime,u1.truename headername,
            u2.trueName managername,c.stucount,c.remark".$sql." order by cid limit $begin,$pageSize");
        $page["rows"] = $rows;
        
        $this->ajaxReturn($page);
    }
    /**
     * 检查所选班级今天是否有考试
     * @param unknown $cids 参数绑定 格式为1,2,3
     */
    public function checkExamToday($cids=null){
        $d = date("Y-m-d");
        $db = $d." 00:00:00";
        $de = $d." 23:59:59";
        $data = $this->classModel->table("exam")->where("classid in($cids) and beginTime between '$db' and '$de'")->select();
//         $sql ="select * from exam where cid in($cids) and beginTime between $db and $de";
        if(count($data) > 0){
            //获取今天又考试的班级ID，用于提示那些班级有考试
            $classid = array();
            foreach ($data as $exam){
                array_push($classid, $exam["classid"]);
            }
            $str = implode(",",$classid);
            //查询今天有考试的班级
            $cnames = $this->classModel->field("name")->where("cid in($str)")->select();
            //存放今天有考试的班级
            $names = array();
            foreach ($cnames as $n){
                array_push($names, $n["name"]);
            }
            $this->ajaxReturn("对不起，".implode(",",$names)."今天有考试不能参加班级合并！","EVAL");
        }else{
            $this->ajaxReturn("ok","EVAL");
        }
    }
    /**
     * 合并班级
     * @param unknown $cids 要合并的班级ID 格式为1,2,3
     * @param unknown $combinedClassid 合并后保留的班级ID
     * @param unknown $combinedHeaderid 合并后该班级的班主任ID
     * @param unknown $combinedManagerid 合并后该班级的项目经理ID
     */
    public function hebingClasses($cids=null,$combinedClassid=-1,$combinedHeaderid=-1,$combinedManagerid=-1){
        try {
            
            $this->classModel->setProperty(\PDO::ATTR_AUTOCOMMIT, false);
            $this->classModel->startTrans();//开启事务
            $classes = $this->classModel->table("class")->where("cid in($cids)")->select();
            $totalCount = 0;
            foreach ($classes as $c){
                if ($c["cid"] == $combinedClassid){
                    //要保留的班级
                    
                }else{
                    //不保留的班级
                    $totalCount += $c["stucount"];
                    $c["stucount"] = 0;//被合并之后人数清零
                    $c["status"] = 2;//被合并
                    $this->classModel->save($c);
                    $sql = "update tb_user set classid=%d where classid=%d";
                    $this->classModel->execute($sql,$combinedClassid,$c["cid"]);
                    
                }
            }
            
            //查询后要保留的班级信息
            $combinedClass = $this->classModel->table("class")->where("cid=%d",$combinedClassid)->find();
            $combinedClass["headerid"] = $combinedHeaderid;
            $combinedClass["managerid"] = $combinedManagerid;
            $combinedClass["stucount"] += $totalCount;
            $this->classModel->save($combinedClass);
            
//             $str = implode(",", $str);
//             $sql = "update tb_user set classid=%d where classid in($str)";
            
            $this->classModel->commit();//提交事务
        } catch (\Exception $e) {
            $this->classModel->rollback();//事务回滚
        }
        $this->loadClassByPage();
    }
    
    
//     public function loadAllClasses(){
// //         $data = $this->classModel->field("name")->select();
// //         print_r($data);
//     }
    
//     public function reg(){
//         //保存索引数组
//         $arr = array(11,22,33);
//         $this->assign("arr",$arr);
//         //保存关联数组
//         $arr2 = array("aa"=>"nhao","bb"=>"你");
//         $this->assign("arr2",$arr2);
//         //数据库 保存二维数组
//         $data = $this->classModel->select();
//         $this->assign("data",$data);
//         $this->assign("emp","<h1>未查询到数据</h1>");
//         $this->assign("arrayLenth",count($data));
//         //保存一个对象
// //         $menu = Menu::getInstance(111,"那","aaa.html",1,1);
// //         $this->assign("menu",$menu);
//         $r = Role::getInstance(111, "那么");
//         $this->assign("r", $r);
        
        
//         $host = $_SERVER["HTTP_HOST"];
//         $this->assign("host",$host);
        
//         //函数
//         $this->assign("str","abcdef");
        
//         //演示运算
//         $this->assign("i",10);
//         $this->assign("j",2);
        
//         $this->assign("ttt","中国!");
//         $this->display();//查找默认
// //         $this->display("index");//查找指定
// //         $this->display("User/user");//跨目录查找
//     }
}

?>