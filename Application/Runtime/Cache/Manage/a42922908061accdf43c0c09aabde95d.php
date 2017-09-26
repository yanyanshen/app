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
<link href="/Public/517/js/fancybox1.3.4/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" />
<script src="/Public/517/js/jquery-1.12.0.min.js"></script>
<script src="/Public/517/js/jquery.fancybox-1.3.1.pack.js"></script>
<title>课程列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 驾校列表 <span class="c-gray en">&gt;</span> 课程列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="<?php echo U('addjxclass?userid='.$userid);?>" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6fc2;</i> 添加课程信息</a>　<a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>　<a href="<?php echo U('schoolList?p='.$pp);?>"><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回</button></a></span> <span class="r">共有数据：<strong><?php echo ($rowscount); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="17" style="text-align:center"><?php echo ($nickname); ?>课程列表</th>
			</tr>
			<tr class="text-c">
				<th width="25"><input type="checkbox" name="" value=""></th>
				<th width="40">编号</th>
				<th width="50">课程编号</th>
				<th width="150">课程名</th>
				<th width="90">车型</th>
				<th width="100">官方价</th>
				<th width="100">517全款价</th>
				<th width="100">517预付费价</th>
				<th width="100">定金</th>
				<th width="100">等待时间</th>
				<th width="100">班别</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
		   <?php if(is_array($trainlist)): $i = 0; $__LIST__ = $trainlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
			    <th width="25"><input type="checkbox" name="" value=""></th>
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo ($vv["tcid"]); ?></td>
				<td><?php echo ($vv["name"]); ?></td>
				<td><?php echo ($vv["carname"]); ?></td>
				<td><?php echo ($vv["officialprice"]); ?></td>
				<td><?php echo ($vv["whole517price"]); ?></td>
				<td><?php echo ($vv["prepay517price"]); ?></td>
				<td><?php echo ($vv["prepay517deposit"]); ?></td>
				<td><?php echo ($vv["waittime"]); ?></td>
				<td><?php echo ($vv["classtime"]); ?></td>
			    <td class="td-manage"><a title="编辑" href="<?php echo U('updatejxclass?userid='.$userid.'&p='.$p.'&classid='.$vv['id']);?>"><i class="Hui-iconfont">&#xe6ff;</i></a>
			    <a title="删除" href="<?php echo U('deljxclass?id='.$vv['id'].'&p='.$p.'&userid='.$userid);?>" class="ml-5" style="text-decoration:none" onclick="if(confirm('确定删除?')==false)return false;"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<tr>
				<th colspan="17" >
				<?php echo ($page); ?>
				</th>
			</tr>
		</tbody>
	</table>
</div>
</body>
</html>