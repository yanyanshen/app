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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 试题列表 <span class="c-gray en">&gt;</span>做题进度列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l">
		<form action="" method='post'>
			输入题目：<input type="text" name='question' style="width:700px;height:30px;"/><input type="submit" value="查找"/>
		</form>
	</span><span class="l"><a href="<?php echo U('addTheory');?>" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6fc2;</i> 添加试题</a>　</span> <span class="r">共有数据：<strong><?php echo ($rowscount); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="18">所有试题列表</th>
			</tr>
			<tr class="text-c">
				<th width="30">编号</th>
				<th width="130">题目</th>
				<th width="120">a</th>
				<th width="120">b</th>
				<th width="10">c</th>
				<th width="120">d</th>
				<th width="40">章节</th>
				<th width="40">类型</th>
				<th width="40">科目</th>
				<th width="100">图片链接</th>
				<th width="100">答案</th>
				<th width="40">解析</th>
				<th width="100">修改</th>
			</tr>
		</thead>
		<tbody>
		   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo (mb_substr($vv["question"],0,50)); ?>...</td>
				<td><?php echo (mb_substr($vv["a"],0,20)); ?>...</td>
				<td><?php echo (mb_substr($vv["b"],0,20)); ?>...</td>
				<td><?php echo (mb_substr($vv["c"],0,20)); ?>...</td>
				<td><?php echo (mb_substr($vv["d"],0,20)); ?>...</td>
				<td><?php echo ($vv["chapterid"]); ?></td>
				<td><?php echo ($vv["classid"]); ?></td>
				<td><?php echo ($vv["subjects"]); ?></td>
				<td><?php echo ($vv["imgurl"]); ?></td>
				<td><?php echo ($vv["answer"]); ?></td>
				<td><a href="<?php echo U('reply?tid='.$vv['id'].'&p='.$p);?>">解析</a></td>
				<td><a href="<?php echo U('updatetheory?id='.$vv['id'].'&p='.$p);?>">修改</a></td>
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