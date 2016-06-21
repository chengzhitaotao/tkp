<?php
namespace Home\entity;
class User{
//     --人员表
//     --userType 所属角色 1表示学员 2 表示校长管理员 3表示班主任 4表示项目经理
//     --trueName 真实姓名
//     --birthDay出生日期
//     --workYear  工作了多少年
//     --regTime 注册时间
//     --education学历 1表示初中 2 表示高中 3高职 4专科 5本科 6硕士以上
//     --status 状态  1表示正常 2表示休假 3表示停职 4表示被合并 5表示结业
//     --address 地址  市级以下地区如某县某村镇
    public $userName;
    public $userPass;
    public $userType;
    public $sex;
    public $birthDay;
    public $phone;
    public $shool;
    public $classid;
    public $education;
    public $workYear;
    public $regTime;
    public $status;
    public $pid;
    public $cid;
    public $address;
    //如果此菜单是二级菜单，则它的所有子菜单放在$children数组中
    //如果此菜单是最低级的菜单，则$children为null
    private $children;
    /**
     * @return $userName
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return $userPass
     */
    public function getUserPass()
    {
        return $this->userPass;
    }

    /**
     * @return $userType
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @return $sex
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @return $birthDay
     */
    public function getBirthDay()
    {
        return $this->birthDay;
    }

    /**
     * @return $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return $shool
     */
    public function getShool()
    {
        return $this->shool;
    }

    /**
     * @return $classid
     */
    public function getClassid()
    {
        return $this->classid;
    }

    /**
     * @return $education
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @return $workYear
     */
    public function getWorkYear()
    {
        return $this->workYear;
    }

    /**
     * @return $regTime
     */
    public function getRegTime()
    {
        return $this->regTime;
    }

    /**
     * @return $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return $pid
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @return $cid
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * @return $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return $children
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setUserPass($userPass)
    {
        $this->userPass = $userPass;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setShool($shool)
    {
        $this->shool = $shool;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setClassid($classid)
    {
        $this->classid = $classid;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setEducation($education)
    {
        $this->education = $education;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setWorkYear($workYear)
    {
        $this->workYear = $workYear;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setRegTime($regTime)
    {
        $this->regTime = $regTime;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setCid($cid)
    {
        $this->cid = $cid;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}

?>