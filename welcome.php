<?php
session_start();
// use model\MenuModel;

// require_once 'Auto.php';
// $menuModel = new MenuModel();
// $uid = $_SESSION["loginUser"][0];
// $secondMenu = $menuModel->loadTreeMenu($uid);

?>
<!DOCTYPE html>
<html>
	<head>
		<title>操作页面</title>
		<meta charset=utf-8>
		<link type="text/css" rel="stylesheet" href="./Public/easyui/themes/bootstrap/easyui.css">
		<link type="text/css" rel="stylesheet" href="./Public/easyui/themes/icon.css">
		<script type="text/javascript" src="./Public/easyui/jquery.min.js"></script>
		<script type="text/javascript" src="./Public/easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript" src="./Public/easyui/locale/easyui-lang-zh_CN.js"></script>
		<script type="text/javascript">
		function addTabs(url,name){
			if($('#tabs').tabs("exists",name)){
				//如果选项卡已经存在，那就直接选中它
				$('#tabs').tabs("select",name);
			}else{
				// 添加一个未选中状态的选项卡面板
				$('#tabs').tabs('add',{
					title: name,
					selected: true,
					closable:true,
					content:"<iframe name='"+name+"' src='"+url+"' width='100%' height='100%' frameborder='0' scrolling='yes'></iframe> "
				});
			}
		}
		</script>
	</head>
	<body class="easyui-layout">  
        <div data-options="region:'north',split:true" style="height:100px;">
        	<div id="top_left"></div>
        	<div id="top_right">
        	<img src="Public/images/dianzx.png" style="width:450px;height:100px;margin-left:100px"/> 
        		<p style="margin-left:1300px;margin-top:-80px">
        			<?php 
        			 if (isset($_SESSION["loginUser"])){
        			     echo "欢迎你！";
        			     echo "  ";
        			     if ($_SESSION["loginUser"][3] == 1){
        			         echo "学生";
        			     }elseif ($_SESSION["loginUser"][3] == 2){
        			         echo "校长";
        			     }elseif ($_SESSION["loginUser"][3] == 3){
        			         echo "班主任";
        			     }elseif ($_SESSION["loginUser"][3] == 4) {
        			         echo "项目经理";
        			     }
        			     echo "  ";
        			     echo $_SESSION["loginUser"][4];
        			 }
        			?>
        			<a href="index.php">退出</a>
        		</p>
        	</div>
        </div>   
        <div data-options="region:'west',title:'菜单',split:true" style="width:200px;">
        	<ul id="tt" class="easyui-tree">   
                <?php 
                if (array_key_exists("secondMenu", $_SESSION)){
                    $secondMenu = $_SESSION["secondMenu"];
                    foreach ($secondMenu as $menu2){
                        echo "<li><span>{$menu2[1]}</span><ul>";
                        foreach ($menu2[5] as $menu3){
                            echo "<li><span><a href = \"javascript:addTabs('{$menu3[2]}','{$menu3[1]}');\">{$menu3[1]}</a></span></li>";
                        }
                        echo "</ul></li>";
                    }
                }
                
                ?> 
            </ul>  
        </div>   
        <div data-options="region:'center'" style="padding:5px;background:#eee;">
        	<div id="tabs" class="easyui-tabs" data-options="fit:true">   
                <div title="欢迎" style="padding:20px;">  
                	<img src="Public/images/sss.png" style="width:100%;height:100%;"/> 
                </div>   
            </div> 
        </div>   
    </body>  
</html>