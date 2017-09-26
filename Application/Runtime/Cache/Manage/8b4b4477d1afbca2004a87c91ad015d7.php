<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
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
});
</script>
<title>组列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 权限管理 <span class="c-gray en">&gt;</span> 权限列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l">
	<form action="<?php echo U('permissadd');?>" method='post'>
		<input type="hidden" value="<?php echo ($p); ?>" name="p"/>
		添加新权限：<input type="text" class="input-text" style="width:100px;" name="permissname"/>&nbsp;&nbsp;权限描述：<input type="text" class="input-text" name="description"  style="width:700px;"/>&nbsp;&nbsp;<input type="submit" class="btn btn-danger radius" value="添加权限" id='sub'/>
	</form>
	</span> <span class="r">共有数据：<strong><?php echo ($count); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="11">权限</th>
			</tr>
			<tr class="text-c">
				<th width="40">编号</th>
				<th width="70">权限</th>
				<th width="200">描述</th>
				<th width="300">拥有该权限的用户</th>
				<th width="30">操作</th>
			</tr>
		</thead>
		<tbody>
		   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo ($vv["premissname"]); ?></td>
				<td><?php echo ($vv["description"]); ?></td>
				<td>
					<?php if(is_array($vv['user'])): $i = 0; $__LIST__ = $vv['user'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; echo ($v['username']); ?>,<?php endforeach; endif; else: echo "" ;endif; ?>
				</td>
				<td><a title="删除" href="<?php echo U('delpremiss?id='.$vv['id'].'&p='.$p);?>" onclick="if(confirm('确定删除该权限吗?')==false)return false;" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<tr>
				<td colspan="11">
				<?php echo ($page); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

</body>
</html>