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
<title>城市基地列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 城市基地列表 <span class="c-gray en">&gt;</span> 基地列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div style="float:left;margin-left:100px;">
<form action="" method="post">
	<br>请输入城市：<input type="text" name="cityname" />
	 <input type="submit" value='　筛选　' />
</form></div><br>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> </span> <span class="r">共有数据：<strong><?php echo ($rowscount); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="15">基地列表</th>
			</tr>
			<tr class="text-c">
				<th width="15"><input type="checkbox" name="" value=""></th>
				<th width="30">编号</th>
				<th width="60">城市</th>
				<th width="500">基地</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
		   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
			    <th width="25"><input type="checkbox" name="" value=""></th>
				<td width="10"><?php echo ($vv["id"]); ?></td>
				<td width=50><?php echo ($vv["cityname"]); ?>(<?php echo ($vv['traincount']); ?>)</td>
				<?php if($vv['traincount'] < 20 ): ?><td><?php if(is_array($vv['train'])): $i = 0; $__LIST__ = $vv['train'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo U('coachstrain?trainid='.$v['tid'].'&p='.$p.'&cityid='.$vv['id']);?>"><?php echo ($v["trname"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?></td>
 					<?php else: ?>
 						<td><?php if(is_array($vv['train'])): $i = 0; $__LIST__ = array_slice($vv['train'],0,20,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo U('coachstrain?trainid='.$v['tid'].'&p='.$p.'&cityid='.$vv['id']);?>"><?php echo ($v["trname"]); ?></a>&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>...</td><?php endif; ?>

			    <td class="td-manage">
			    <a title="编辑添加" href="<?php echo U('train?cityid='.$vv['id'].'&p='.$p);?>"><i class="Hui-iconfont">&#xe62c;</i></a>
			</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<tr>
				<th colspan="15" >
				<?php echo ($page); ?>
				</th>
			</tr>
		</tbody>
	</table>
</div>
</body>
</html>