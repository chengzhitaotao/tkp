<?php
namespace Home\Model;

use Home\util\DBUtil;
/**
 * 专注于访问menu表
 * @author j
 *
 */
class MenuModel{
     private $dbUtil;
     
     
     public function __construct(){
         $this->dbUtil = new DBUtil();
     }
     
     
     /**
      * 首页左侧
      */
     public function loadTreeMenu($uid){
         $m2s = array();
         $sql = "select m.* from userrole ur,rolemenu rm,menu m where ur.rid= rm.rid and rm.menuid=m.menuid and m.isshow =1 and ur.uid=? and m.parentid=?";
         //查询一级菜单
         $menus = $this->dbUtil->executeQuery($sql,array($uid,-1));
         if (count($menus) >0 ){         
             $menu1 = $menus[0];
             
             //查询二级菜单  通过一级菜单的主键ID去查询
             $menu2 = $this->dbUtil->executeQuery($sql,array($uid,$menu1[0]));
             
             //循环一个二级菜单  通过每一个二级菜单的主键ID去查询它下面的二级菜单
             foreach ($menu2 as $second){
                 $m2 = array();
                 array_push($m2, $second[0],$second[1],$second[2],$second[3],$second[4]);
                 $menu3 = $this->dbUtil->executeQuery($sql,array($uid,$second[0]));
                 //设置二级菜单的children属性
//                  $second->setChildren($menu3);
                array_push($m2, $menu3);
                array_push($m2s, $m2);
                
             }
         }
         return $m2s;
     }
     
     /**
      * 分页查询菜单列表
      * @param unknown $pageNo
      * @param unknown $pageSize
      * @return 
      */
     public function loadMenuByPage($pageNo,$pageSize){
         //$sql,$pageNo,$pageSize,array $params=null,$fetchStyle=\PDO::FETCH_NUM,$className=null
//          $sql = "select * from menu limit $pageNo,$pageSize";
         $begin = ($pageNo-1)*$pageSize;
         $sql = "select m.menuid,m.name,m.url,(select m2.name from menu m2 where m.parentid=m2.menuid) as parentName,m.isshow from menu m limit $begin,$pageSize";
         $sql2 = "select count(*) from menu";
         $page = $this->dbUtil->executePageSubQuery($sql,$sql2, $pageNo, $pageSize,null,\PDO::FETCH_ASSOC);
         return $page;
     }
     
     
     public function load12Menu(){
         $sql = "select * from menu where parentid=?";
         $fsmenu =array();
         $menus = $this->dbUtil->executeQuery($sql,array(-1),\PDO::FETCH_OBJ,'Home\entity\Menu');
         $menu1 =$menus[0];
         foreach ($menus as $m){
             $m->setName("一级—".$m->getName());
             array_push($fsmenu, $m);
         }
         //查询二级菜单  通过一级菜单的主键ID去查询
         $menu2 = $this->dbUtil->executeQuery($sql,array($menu1->getMenuid()),\PDO::FETCH_OBJ,'Home\entity\Menu');
         foreach ($menu2 as $m2){
             $m2->setName("二级—".$m2->getName());
             array_push($fsmenu, $m2);
         }
         return $fsmenu;
     }
     
     
     public function loadByIdMenu($menuid){
         $sql = "select * from menu where menuid=?";
         $menu2 = $this->dbUtil->executeQuery($sql,array($menuid),\PDO::FETCH_OBJ,'Home\entity\Menu');
         $menu1 = $menu2[0];
         return $menu1;
     }
     
     /**
      * 增加与修改
      * 
      * @param unknown $name
      * @param unknown $url
      * @param unknown $parentid
      * @param unknown $isshow
      * @param unknown $menuid
      */
     public function addOrUpdateMenu($name,$url,$parentid,$isshow,$menuid){
         if ($menuid == 0){
             $sql = "insert into menu(name,url,parentid,isshow) values(?,?,?,?)";
             $menu2 = $this->dbUtil->executeDML($sql,array($name,$url,$parentid,$isshow));
             return $menu2;
         }else if($menuid > 0){
             $sql = "update menu set name=?,url=?,parentid=?,isshow=? where menuid=?";
             $menu2 = $this->dbUtil->executeDML($sql,array($name,$url,$parentid,$isshow,$menuid));
             return $menu2;
         }
        
     }
     /**
      * 删除菜单
      */
    public function deleteMenu($menuids){
        $sql = "delete from menu where menuid in($menuids)";
        $menu1 = $this->dbUtil->executeDML($sql);
    } 
     
    
}

?>