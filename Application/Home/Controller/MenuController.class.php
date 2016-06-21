<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\MenuModel;
 
class MenuController extends Controller{
    
    private $menuModel;
    
    public function __construct(){
        parent::__construct();
        $this->menuModel = new MenuModel();
    }
    
    public function menuManage(){
        $this->assign("root",ROOT);
        $this->display();
    }
    
    /**
     * 页面加载菜单
     */
    public function loadMenuByPage($pageNo=1,$pageSize=10){
        $page = $this->menuModel->loadMenuByPage($pageNo,$pageSize);
        $this->ajaxReturn($page);
    }
    
    /**
     * 显示12级菜单
     */
    public  function load12Menu(){
        $data =  $this->menuModel->load12Menu();
        $this->ajaxReturn($data);
    }
    
    /**
     * 回填数据
     */
    public function loadByIdMenu($menuid){
        $data =  $this->menuModel->loadByIdMenu($menuid);
        $this->ajaxReturn($data);
    }
    
    /**
     * 添加修改
     */
    public function addOrUpdateMenu($name,$url,$parentid,$isshow,$menuid){
        if ($menuid == ""){
            $this->menuModel->addOrUpdateMenu($name,$url,$parentid,$isshow,0);
            $this->ajaxReturn("insertok","eval");
        }else{
            $this->menuModel->addOrUpdateMenu($name,$url,$parentid,$isshow,(int)$menuid);
            $this->ajaxReturn("updateok","eval");
        }
    }
    
    /**
     * 删除菜单
     */
    public function deleteMenu($menuids){
        $this->menuModel->deleteMenu($menuids);
        $this->ajaxReturn("删除成功！","eval");
    }
    
    
}

?>