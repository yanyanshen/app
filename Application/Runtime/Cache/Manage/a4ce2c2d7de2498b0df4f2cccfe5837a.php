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
<title>做题进度列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 做题进度列表 <span class="c-gray en">&gt;</span>做题进度列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"></span> <span class="r">共有数据：<strong><?php echo ($rowscount); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="18">做题进度列表</th>
			</tr>
			<tr class="text-c">
				<th width="30">编号</th>
				<th width="130">最新做题时间</th>
				<th width="130">用户</th>
				<th width="500">题目</th>
				<th width="100">科目</th>
				<th width="60">章节</th>
				<th width="100">当前</th>
			</tr>
		</thead>
		<tbody>
		   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo (date("Y-m-d H:i:s",$vv['dotime'])); ?></td>
				<td><?php echo ($vv["nickname"]); ?></td>
				<td><?php echo ($vv["tid"]); ?></td>
				<td><?php echo ($vv["subjectid"]); ?></td>
				<td><?php echo ($vv["chapterid"]); ?></td>
				<td><?php echo ($vv["indexs"]); ?></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<tr>
				<th colspan="18" >
				<?php echo ($page); ?>
				</th>
			</tr>
		</tbody>
	</table>
</div>
</body>
</html>