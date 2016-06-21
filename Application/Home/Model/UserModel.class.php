<?php
namespace Home\Model;
use Home\util\DBUtil;
/**
 * 专注于访问menu表
 * @author j
 *
 */
class UserModel{
    
     private $dbUtil;
     
     public function __construct(){
         $this->dbUtil = new DBUtil();
     }
     /**
      * 登录验证
      * @param unknown $userNmae  用户名
      * @param unknown $userPass  密码
      * @return number
      */
     public function login($userName,$userPass){
         $sql = "select * from tb_user where userName=?";
         $datas = $this->dbUtil->executeQuery($sql,array($userName));
         if (count($datas) == 1){
             
             if($userPass == $datas[0][2]){
                 return 1;
             }else{
                 return 3;
             }
         }else{
             return 2;
         }
     }
     
     /**
      * 
      * @param unknown $userNmae
      * @return mixed|NULL
      */
     public function loginUserByName($userName){
         $sql = "select * from tb_user where userName=?";
         $datas = $this->dbUtil->executeQuery($sql,array($userName));
         if (count($datas) == 1){
              return $datas[0];
            
         }else{
             return null;
         }
     }
     
     
     
     
     
}

?>