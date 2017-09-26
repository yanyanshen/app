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
<title>管理员列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="<?php echo U('useradd');?>" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6fc2;</i> 添加用户</a></span> <span class="r">共有数据：<strong><?php echo ($count); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="11">用户</th>
			</tr>
			<tr class="text-c">
				<th width="40">编号</th>
				<th width="150">用户名</th>
				<th width="90">真实姓名</th>
				<th width="80">联系方式</th>
				<th width="100">所属部门</th>
				<th width="100">角色</th>
				<th width="100">创建时间</th>
				<th width="250">备注</th>
				<th width="100">状态</th>
				<th width="50">权限分配</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
		   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo ($vv["account"]); ?></td>
				<td><?php echo ($vv["username"]); ?></td>
				<td><?php echo ($vv["phone"]); ?></td>
				<td><?php echo ($vv['masgroup']==1?'管理组':($vv['masgroup']==2?'技术组':($vv['masgroup']==3?'销售组':($vv['masgroup']==4?'客服组':'市场组')))); ?></td>
				<td><?php echo ($vv['role']==1?'普通用户':'管理员'); ?></td>
				<td><?php echo (date("Y-m-d H:i:s",$vv["ntime"])); ?></td>
				<td><?php echo ($vv["note"]); ?></td>
				<td><?php echo ($vv['flag']=='n'?'<font color="red">已禁用</font>':'<font color="green">已启用</font>'); ?> <a href="<?php echo U('updateflag?account='.$vv['account'].'&flag='.$vv['flag'].'&p=$p');?>"><?php echo ($vv['flag']=='n'?'<font color="green">启用</font>':'<font color="red">禁用</font>'); ?></font></a></td>
				<td><a href="<?php echo U('assignpermiss?account='.$vv['account'].'&p='.$p);?>">去分配</a></td>
			    <td class="td-manage"><a title="编辑" href="<?php echo U('userinfo?account='.$vv['account']);?>"><i class="Hui-iconfont">&#xe62c;</i></a>&nbsp;&nbsp;
			     <a title="删除" href="<?php echo U('deluser?account='.$vv['account'].'&p='.$p);?>" onclick="if(confirm('确定删除该用户吗?')==false)return false;" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
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