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
				url:'http://localhost:8080/tkp/index.php/Home/Class/loadClassByPage?pageNo=1&pageSize=10',
				pagination:true,
				rownumbers:true,
				frozenColumns:[[
	                {field:'xxx',checkbox:true}
				]],
				columns:[[
		            {field:'cid',hidden:true},
		            {field:'name',title:'班级名称',width:200,align:'center'},
		            {field:'classtype',title:'班级类型',width:200,align:'center',formatter:function(classtype){
						if(classtype==1){
							return "常规班";
							}else if(classtype==2){
							return "快速班";
							}else if(classtype==3){
							return "flash班";
							}else if(classtype==4){
							return "php班";
							}
			            }},
		            {field:'status',title:'班级状态',width:200,align:'center',formatter:function(status){
						if(status==1){
							return "正常";
							}else if(status==2){
							return "被合并";
							}else if(status==3){
							return "已结业";
							}else if(status==4){
							return "已废除";
							}
			            }},
		            {field:'createtime',title:'创建时间',width:200,align:'center'},
		            {field:'begintime',title:'开班时间',width:200,align:'center'},
		            {field:'endtime',title:'收班时间',width:200,align:'center'},
		            {field:'headername',title:'班主任',width:200,align:'center'},
		            {field:'managername',title:'项目经理',width:200,align:'center'},
		            {field:'stucount',title:'学生人数',width:200,align:'center'},
		            {field:'remark',title:'备注',width:200,align:'center'},
				]],
				toolbar:'#tb'
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
			$.getJSON("http://localhost:8080/tkp/index.php/Home/Class/loadClassByPage?pageNo="+pageNumber+"&pageSize="+pageSize,{},function(result){
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
		
		//搜索班级
		function searchClass(){
			$.post("http://localhost:8080/tkp/index.php/Home/Class/loadClassByPage",{
				'pageNo'		:1,
				'pageSize'		:10,
				'className'		:$("#search-className").val(),
				'createtime1'	:$("#search-createtime1").combo("getValue"),
				'createtime2'	:$("#search-createtime2").combo("getValue"),
				'headerName'	:$("#search-headerName").val(),
				'benintime1'	:$("#search-benintime1").combo("getValue"),
				'benintime2'	:$("#search-benintime2").combo("getValue"),
				'managerName'	:$("#search-managerName").val(),
				'endtime1'		:$("#search-endtime1").combo("getValue"),
				'endtime2'		:$("#search-endtime2").combo("getValue"),
				'status'		:$("#search-status").combo("getValue")
			},function(result){
				$("#bg").datagrid('loadData',{
					rows:result.rows,
					total:result.total
				})
			},"json");
			
		}
		
		
		//班级合并
		/*
		*至少选两个班级
		所选班级状态正常
		所选班级不能有考试
		*/
		function combineClass(){
			var selectedRows = $("#bg").datagrid("getSelections");
			if(selectedRows.length < 2){
				alert("至少选中两个班级！");
				return;
			}
			var b = true;
			for( var i=0;i<selectedRows.length;i++){
				if(selectedRows[i].status != 1){
					b = false;
					break;
				}
			}
			if(!b){
				alret("所选班级状态必须全部为正常！");
				return;
			}
			//获取已选中的班级ID
			var cids = new Array();
			var options  = new Array();
			//var options = "<option value='-1'>请指定合并后班级名称</option>";
			options.push({"name":"请指定合并后班级名称","cid":"-1"});
			for( var i=0;i<selectedRows.length;i++){
				cids.push(selectedRows[i].cid);
				options.push({"name":selectedRows[i].name,"cid":selectedRows[i].cid});
			}
			$.post("http://localhost:8080/tkp/index.php/Home/Class/checkExamToday",{"cids":cids.join(",")},function(data){
				if( data == "ok"){
					$("#combinedClassid").combobox({
						valueField	:'cid',
						textField	:'name',
						data		:options,
						value		:'-1'
					});
					//ajax载入班主任选项
					$('#combinedHeaderid').combobox({
						url			:'http://localhost:8080/tkp/index.php/Home/User/loadAllHeader',
						valueField	:'uid',
						textField	:'truename',
						value		:'-1'
					});
					//ajax载入项目经理选项
					$('#combinedManagerid').combobox({
						url			:'http://localhost:8080/tkp/index.php/Home/User/loadAllManage',
						valueField	:'uid',
						textField	:'truename',
						value		:'-1'
					});
					//打开合并的窗口表单界面
					$('#win').window('open');
				}else{
					alert(data);
				}
			},"text");
		}
		
		function hebingClasses(){
			//获取已选中的班级ID
			var selectedRows = $("#bg").datagrid("getSelections");
			var cids = new Array();
			for( var i=0;i<selectedRows.length;i++){
				cids.push(selectedRows[i].cid);
			}
			$.post("http://localhost:8080/tkp/index.php/Home/Class/hebingClasses",{
				"cids"				:cids.join(","),
				"combinedClassid"	:$("#combinedClassid").combo("getValue"),
				"combinedHeaderid"	:$("#combinedHeaderid").combo("getValue"),
				"combinedManagerid"	:$("#combinedManagerid").combo("getValue")
			},function(result){
				$('#win').window('close');
				alert("班级合并成功!");
				$("#bg").datagrid('loadData',{
					rows:result.rows,
					total:result.total
				});
			},"json")
		}
		
		
		</script>
	</head>
	<body>
		<table id="bg"></table>
		<div id="tb">
			<form action="" id="searchFrom">
				<label>班级名称</label>
				<input type="text" class="easyui-validatebox in" placeholder="班级名称查询" id="search-className"/>
				<label>班主任名称</label>
				<input type="text" class="easyui-validatebox in" placeholder="班主任名称查询" id="search-headerName"/>
				<label>项目经理名称</label>
				<input type="text" class="easyui-validatebox in" placeholder="项目经理名称查询" id="search-managerName"/>
				<label>状态</label>
				<select class="easyui-combobox" id="search-status">
					<option value="-1">状态搜索</option>
					<option value="1">正常</option>
					<option value="2">被合并</option>
					<option value="3">已结业</option>
					<option value="4">已废除</option>
				</select>
				<label>创建时间</label>
				<input type="text" class="easyui-datebox in" id="search-createtime1" date-options="ed"/>
				<label>至</label>
				<input type="text" class="easyui-datebox in" id="search-createtime2"/>
				<label>开班时间</label>
				<input type="text" class="easyui-datebox in" id="search-benintime1"/>
				<label>至</label>
				<input type="text" class="easyui-datebox in" id="search-benintime2"/>
				<label>结业时间</label>
				<input type="text" class="easyui-datebox in" id="search-endtime1"/>
				<label>至</label>
				<input type="text" class="easyui-datebox in" id="search-endtime2"/>
				<a href="javaScript:searchClass();" class="easyui-linkbutton in" data-options="iconCls:'icon-search',plain:true">搜索</a>
				<a href="javaScript:combineClass();" class="easyui-linkbutton in" data-options="iconCls:'icon-combine'">合并</a>
			</form>
		</div>
		<div id="win" class="easyui-window" title="合并班级" style="width:600px;height:400px;"   
                data-options="iconCls:'icon-combine',modal:true,collapsible:false,minimizable:false,maximizable:false">   
            <form id="ff" method="post">
            	<table style="width:60%;margin:auto;" id="formtable">
            		<tr>
            			<td><label for="combinedClassid">合并后班级名称:</label></td>
            			<td>
            				<select id="combinedClassid" class="easyui-combobox"  style="width:150px;"></select>
            			</td>
            		</tr>
            		<tr>
            			<td><label for="combinedHeaderid">合并后班主任名称:</label></td>
            			<td>
            				<select id="combinedHeaderid" class="easyui-combobox"  style="width:150px;"></select>
            			</td>
            		</tr>
            		<tr>
            			<td><label for="combinedManagerid">合并后项目经理名称:</label></td>
            			<td>
            				<select id="combinedManagerid" class="easyui-combobox" style="width:150px;"></select>
            			</td>
            		</tr>
            		<tr>
            			<td align="center"  colspan="2">
            				<a id="btn" href="javascript:hebingClasses();" class="easyui-linkbutton" data-options="iconCls:'icon-submit'">合并班级</a>  
            			</td>
            		</tr>
            	</table>  
            </form>        
        </div> 
	</body>
</html>