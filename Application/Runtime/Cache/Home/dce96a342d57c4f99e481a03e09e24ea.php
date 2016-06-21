<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>菜单管理界面</title>
		<meta charset="utf-8">
		<link type="text/css" rel="stylesheet" href="http://localhost:8080/tkp/Public/easyui/themes/bootstrap/easyui.css">
		<link type="text/css" rel="stylesheet" href="http://localhost:8080/tkp/Public/easyui/themes/icon.css">
		<script type="text/javascript" src="http://localhost:8080/tkp/Public/easyui/jquery.min.js"></script>
		<script type="text/javascript" src="http://localhost:8080/tkp/Public/easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript" src="http://localhost:8080/tkp/Public/easyui/locale/easyui-lang-zh_CN.js"></script>
		<script type="text/javascript">
		$(function(){
			$('#win').window('close');  // close a window  

			$('#bg').datagrid({
				striped:true,
				method:"GET",
				url:'http://localhost:8080/tkp/index.php/Home/Menu/loadMenuByPage?pageNo=1&pageSize=10',
				pagination:true,
				rownumbers:true,
				frozenColumns:[[
	                {field:'xxx',checkbox:true}
				]],
				columns:[[
		            {field:'menuid',hidden:true},
		            {field:'name',title:'菜单名称',width:200,align:'center'},
		            {field:'url',title:'路径',width:200,align:'center'},
		            {field:'parentid',title:'父级菜单ID',width:200,align:'center'},
		            {field:'isshow',title:'是否显示',width:200,align:'center',formatter:function(isshow){
						if(isshow==1){
							return "展示";}else{return "不展示";}
			            }}
				]],
				toolbar: [{
					iconCls: 'icon-adduser',
					text:'添加菜单',
					handler: function(){
						//加载一二级菜单列表  
						$('#parentid').combobox({  
						    url:'http://localhost:8080/tkp/index.php/Home/Menu/load12Menu',    
						    valueField:'menuid',    
						    textField:'name'   
						}); 
						$('#win').window('open');  // open a window    
					}
				},'-',{
					iconCls: 'icon-delete',
					text:'删除菜单',
					handler: function(){
						var selectedRows = $("#bg").datagrid("getSelections");
						if(selectedRows.length == 0){
							alert("请先选中，再进行删除！");
							return;
						}
						if(window.confirm("你真的想删除这些数据吗？")){
							var menuids = new Array();
							for(var i=0;i<selectedRows.length;i++){
								menuids.push(selectedRows[i].menuid);
							}
							$.post("http://localhost:8080/tkp/index.php/Home/Menu/deleteMenu",{
								"menuids":menuids.join(",")
							},function(data){
								refreshData(1,10);
							},"text");
						}
					}
				},'-',{
					iconCls: 'icon-modify',
					text:'修改',
					handler: function(){
						var selectedRows = $("#bg").datagrid("getSelections");
						if(selectedRows.length == 0){
							alert("请先选中，再进行修改！");
							return;
						}
						if(selectedRows.length > 1){
							alert("你只能选中一项进行修改！");
							return;
						}
						//每次打开窗口前加载1 2级菜单作为父菜单下拉列表的选项
						$('#parentid').combobox({
							url:'http://localhost:8080/tkp/index.php/Home/Menu/load12Menu',
							valueField:'menuid',
							textField:'name'
						});
						$('#parentid').combobox("setValue",-1);
						$('#ff').form('reset');
						//获取当前选项的数据
						var row = selectedRows[0];
						//回填数据
						$.getJSON("http://localhost:8080/tkp/index.php/Home/Menu/loadByIdMenu?menuid="+row.menuid,{},function(data){
							$("#menuid").val(data.menuid);
							$("#name").val(data.name);
							$("#url").val(data.url);
							$("#parentid").combobox("setValue",data.parentid);
							$("#isshow").combobox("setValue",data.isshow);
						});
						$("#win").window('open');
					}
				},'-',{ 
					iconCls: 'icon-reload',
					text:'刷新菜单',
					handler: function(){
						refreshData(1,10);
					}
				}]
			});

			//设置翻页功能
			var pager = $("#bg").datagrid("getPager");
			pager.pagination({
				onSelectPage:function(pageNumber, pageSize){
					refreshData(pageNumber,pageSize);
				}
			});
			
		});

		//增加与修改
		function addOrUpdateMenu(){
			var name = $("#name").val();
			var url = $("#url").val();
			var menuid = $("#menuid").val();
			var parentid = $("#parentid").combo('getValue');
			var isshow = $("#isshow").combo('getValue');
			$.post("http://localhost:8080/tkp/index.php/Home/Menu/addOrUpdateMenu",{
				"menuid"	:menuid,
				"name"		:name,
				"url"		:url,
				"parentid"	:parentid,
				"isshow"	:isshow
			},function(data){
				if(data == "insertok"){
					$.messager.alert('我的消息','添加成功！','info');
					refreshData(1,10);
					$('#win').window('close');
				}else if(data == "updateok"){
					$.messager.alert('我的消息','修改成功！','info');
					refreshData(1,10);
					$('#win').window('close');
				}
			},"text");
		 }

// 		function updateMenu(){
// 			var name = $("#name").val();
// 			var url = $("#url").val();
// 			var parentid = $("#parentid").combo('getValue');
// 			var isshow = $("#isshow").combo('getValue');
// 			var menuid = $("#menuid").val();
// 		}
		
		//刷新数据
		function refreshData(pageNumber,pageSize){
			$("#bg").datagrid('loading');
			$.getJSON("http://localhost:8080/tkp/index.php/Home/Menu/loadMenuByPage?pageNo="+pageNumber+"&pageSize="+pageSize,{},function(result){
				$("#bg").datagrid('loadData',{
					rows:result.rows,
					total:result.total
				});
				var pager = $("#bg").datagrid("getPager");
				pager.pagination({
					pageSize:pageSize,
					pageNumber:pageNumber
				});
				$("#bg").datagrid('loaded');
			});
		}
		</script>
	</head>
	<body>
		<table id="bg"></table>
		<div id="win" class="easyui-window" title="添加菜单" style="width:600px;height:400px;"   
                data-options="iconCls:'icon-adduser',modal:true,collapsible:false,minimizable:false,maximizable:false">   
            <form id="ff" method="post">
            <input type="hidden" id="menuid"> 
            	<table style="width:60%;margin:auto;" id="formtable">
            		<tr>
            			<td><label for="name">菜单名称</label> </td>
            			<td><input class="easyui-validatebox in" type="text" id="name" name="name" data-options="required:true" /> </td>
            		</tr>
            		<tr>
            			<td><label for="url">菜单URL:</label></td>
            			<td><input class="easyui-validatebox in" type="text" id="url" name="url" data-options="" placeholder="若添加非最低级菜单，此项可不填！"/> </td>
            		</tr>
            		<tr>
            			<td><label for="url">父级菜单:</label></td>
            			<td>
            				<select id="parentid" class="easyui-combobox in" name="parentid" style="width:150px;"></select>
            			</td>
            		</tr>
            		<tr>
            			<td><label for="isshow">是否展示:</label></td>
            			<td>
            				<select id="isshow" class="easyui-combobox in" name="isshow" style="width:150px;">
            					<option value="1">展示</option>
            					<option value="0">不展示</option>
            				</select>
            			</td>
            		</tr>
            		<tr>
            			<td align="center"  colspan="2">
            				<a id="btn" href="javascript:addOrUpdateMenu();" class="easyui-linkbutton" data-options="iconCls:'icon-submit'">确认提交</a>  
            			</td>
            		</tr>
            	</table>  
            </form>        
        </div> 
	</body>
</html>