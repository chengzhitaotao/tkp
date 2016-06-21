<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>reg模板</p>
		<?php echo ($ttt); ?><br/>
		<?php echo ($arr["0"]); ?>---<?php echo ($arr[1]); ?><br />
		<?php echo ($arr2["aa"]); ?>---<?php echo ($arr2['bb']); ?><br />
		<?php echo ($data["0"]["rid"]); ?>---<?php echo ($data[0][name]); ?><br />
		<?php echo ($r->rid); ?>--<?php echo ($r->name); ?><br />
		<?php echo ($_SERVER['HTTP_USER_AGENT']); ?><br />
		<?php echo ($_GET['rid']); ?>--<?php echo ($_GET['name']); ?><br />
		<?php echo ($host); ?><br />
		<?php echo (md5($str)); ?><br />
		<?php echo (substr($str,0,3)); ?>--<?php echo (substr($str,0,5)); ?><br />
		<?php echo substr($str,0,4);?><br />
		<?php echo ((isset($str) && ($str !== ""))?($str):"你好!"); ?><br />
		<?php echo ($i+$j); ?>--<?php echo ($i-$j); ?>--<?php echo ($i/$j); ?>--<?php echo ($i*$j); ?>--<?php echo ($i%$j); ?>--<?php echo ($j++); ?>--<?php echo ++$j;?><br />
		<?php echo ($i==4?"正确":"错误"); ?><br />
		
		<table border=1 cellspacing="0" bordercolor="blue">
			<tr>
				<td>编号</td>
				<td>名称</td>
			</tr>
			
			<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "$emp" ;else: foreach($__LIST__ as $key=>$role): $mod = ($i % 2 );++$i; if(($mod) == "0"): ?><tr style="background-color: gray;">
						<td>
							<?php if($role["rid"] == 1): ?>编号1
								<?php elseif($role["rid"] == 2): ?>编号2
								<?php elseif($role["rid"] == 3): ?>编号3
								<?php else: ?>编号4<?php endif; ?>
						</td>
						<td><?php echo ($role["name"]); ?></td>
					</tr><?php endif; ?>
				<?php if(($mod) == "1"): ?><tr style="background-color: orange;">
						<td>
							<?php if($role["rid"] == 1): ?>编号1
								<?php elseif($role["rid"] == 2): ?>编号2
								<?php elseif($role["rid"] == 3): ?>编号3
								<?php else: ?>编号4<?php endif; ?>
						</td>
						<td><?php echo ($role["name"]); ?></td>
					</tr><?php endif; endforeach; endif; else: echo "$emp" ;endif; ?>
			
			<!-- 
			<?php if(is_array($data)): foreach($data as $i=>$role): if(($i%2) == "0"): ?><tr style="background-color: gray;">
						<td><?php echo ($role["rid"]); ?></td>
						<td><?php echo ($role["name"]); ?></td>
					</tr><?php endif; ?>
				<?php if(($i%2) == "1"): ?><tr style="background-color: green;">
						<td><?php echo ($role["rid"]); ?></td>
						<td><?php echo ($role["name"]); ?></td>
					</tr><?php endif; endforeach; endif; ?>-->
      		  
      		 <!--  <?php $__FOR_START_28269__=0;$__FOR_END_28269__=$arrayLenth;for($i=$__FOR_START_28269__;$i < $__FOR_END_28269__;$i+=1){ ?>-->
      		<!-- 反过来执行 -->
      		<!--  <for start="$arrayLenth-1" end="0" comparison="egt" step="-1" name="i">-->
      		<!-- 
      			 <?php if(($i%2) == "0"): ?><tr style="background-color: gray;">
						<td><?php echo ($data["$i"]["rid"]); ?></td>
						<td><?php echo ($data["$i"]["name"]); ?></td>
					</tr><?php endif; ?>
				<?php if(($i%2) == "1"): ?><tr style="background-color: green;">
						<td><?php echo ($data["$i"]["rid"]); ?></td>
						<td><?php echo ($data["$i"]["name"]); ?></td>
					</tr><?php endif; } ?>
			 -->
			
		</table>
		<?php switch($i): case "1": ?>中<?php break;?>
            <?php case "2": ?>华<?php break;?>
            <?php case "3": ?>人<?php break;?>
            <?php case "4": ?>民<?php break;?>
            <?php case "5": ?>共<?php break;?>
            <?php case "6": ?>和<?php break;?>
            <?php case "7": ?>国<?php break;?>
            <default>藩夷</default><?php endswitch;?><br />
        
        <?php if($j == 4): ?>你在开么子玩笑哦!<?php endif; ?><br />
        <?php if($i < 3): ?>你在开么子玩笑哦!<?php else: ?>这还差不多!<?php endif; ?><br />
        <?php if($j <= 2): ?>对咯<elseif condition="$j gt 5">你在开么子玩笑哦!<?php else: ?>牙刷儿.....<?php endif; ?>
        
        
        
	</body>
</html>