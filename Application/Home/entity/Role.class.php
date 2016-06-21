<?php
namespace Home\entity;

class Role{
    public $rid;
    public $name;
    
    public static function getInstance($rid,$name){
        $r = new Role();
        $r->rid = $rid;
        $r->name = $name;
        return $r;
        
       
        
    }
}

?>