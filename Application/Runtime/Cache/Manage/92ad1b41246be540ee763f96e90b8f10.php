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
<script  src="/Public/517/js/My97DatePicker/WdatePicker.js"></script>
	<script>
	</script>

<title>驾校列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 日志列表 <span class="c-gray en">&gt;</span>日志列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div style="float:left;margin-left:100px;">
<form action="<?php echo U('adminlog?type=1');?>" method="post">
	<br >
	起始日期：<input type="text" name="startdate"  onClick="WdatePicker()"/>至：<input type="text" name="enddate"  onClick="WdatePicker()"/>
输入用户名：<input type="text" name="account" />
	 <input type="submit" value='筛选' />
</form></div><br>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"> </span> <span class="r">共有数据：<strong><?php echo ($count); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="16">系统日志列表</th>
			</tr>
			<tr class="text-c">
				<th width="40">编号</th>
				<th width="50">账号</th>
				<th width="1000">日志信息</th>
				<th width="120">时间</th>
				<th width="100">主机ip</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
		   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo ($vv["account"]); ?></td>
				<td><?php echo ($vv["info"]); ?></td>
				<td><?php echo (date("Y-m-d H:i:s",$vv['ntime'])); ?></td>
				<td><?php echo (long2ip($vv['nip'])); ?></td>
			    <td class="td-manage">
			    <a title="删除" href="<?php echo U('deladminlog?id='.$vv['id'].'&p='.$p);?>" onclick="if(confirm('确定删除?')==false)return false;" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<tr>
				<th colspan="16" >
				<?php echo ($page); ?>
				</th>
			</tr>
		</tbody>
	</table>
</div>
</body>
</html>