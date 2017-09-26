<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/Public/517/favicon.ico" >
<LINK rel="Shortcut Icon" href="/Public/517/favicon.ico" />
<link href="/Public/517/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/Hui-iconfont/1.0.6/iconfont.css" rel="stylesheet" type="text/css" />
<script src="/Public/517/js/jquery-1.12.0.min.js"></script>
<script>
$("document").ready(function(){
	$('#sub').click(function(){
		if($("input[name='permissname']").val()==''||$("input[name='description']").val()==''){
			alert("提交不能为空");return false;
		}
	});
	$("#b1").click(function(){
		$.ajax({
			url:"<?php echo U('permissupdate');?>",
		    type:"POST",
	        data:$('#form1').serialize(),
	        success: function(data) {
	           if(data==1){
	        	   alert("更新成功");
	        	   location.reload();
	           }else{
	        	   alert("更新失败");
	           }
	        }
		});
	});
});
</script>
<title>用户权限</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 权限管理 <span class="c-gray en">&gt;</span> 用户权限 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l">
		当前被操作用户:<?php echo ($user['username']); ?>&nbsp;&nbsp;&nbsp;&nbsp;<button id="b1">保存更新</button>
	</span> <span class="r">共有数据：<strong><?php echo ($count); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="11">权限</th>
			</tr>
			<tr class="text-c">
				<th width="40">编号</th>
				<th width="70">勾选</th>
				<th width="200">权限</th>
			</tr>
		</thead>
		<tbody>
		<form id="form1">
		   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
				<td><?php echo ($vv["id"]); ?></td>
				<td><input type="checkbox" name="<?php echo ($vv['id']); ?>" id="" value="<?php echo ($vv['id']); ?>" <?php if(in_array(($vv['id']), is_array($permiss)?$permiss:explode(',',$permiss))): ?>checked<?php endif; ?> /></td>
				<td><?php echo ($vv["premissname"]); ?></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<input type="hidden" name='p' value="<?php echo ($p); ?>"/>
			<input type="hidden" name='account' value="<?php echo ($account); ?>"/>
		</form>
		</tbody>
		
	</table>
</div>

</body>
</html>