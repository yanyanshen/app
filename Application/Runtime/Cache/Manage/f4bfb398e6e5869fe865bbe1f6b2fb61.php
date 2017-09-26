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


<title>学员列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 学员列表 <span class="c-gray en">&gt;</span> 学员列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="<?php echo U('addxy');?>" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6fc2;</i> 添加学员</a>　<a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> </span> <span class="r">共有数据：<strong><?php echo ($rowscount); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="16">学员列表</th>
			</tr>
			<tr class="text-c">
				<th width="25"><input type="checkbox" name="" value=""></th>
				<th width="40">编号</th>
				<th width="40">账号</th>
				<th width="100">头像</th>
				<th width="100">学员姓名</th>
				<th width="25">性别</th>
				<th width="120">注册时间</th>
				<th width="80">联系方式</th>
				<th width="80">驾照类型</th>
				<th width="80">当前科目</th>
				<th width="100">订单个数</th>
				<th width="100">预约个数</th>
				<th width="200">地址</th>
				<th width="60">状态</th>
				<th width="100">最后操作人</th>
				<th width="50">操作</th>
			</tr>
		</thead>
		<tbody>
		   <?php if(is_array($userlist)): $i = 0; $__LIST__ = $userlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
			    <th width="25"><input type="checkbox" name="" value=""></th>
				<td width="50"><?php echo ($vv["id"]); ?></td><td><?php echo ($vv["account"]); ?></td>
				<td width=100>
				<script>
   					 $(function(){
					//$('.aaa').fancybox();
					 $('#<?php echo ($vv["userid"]); ?>').fancybox({
						'transitionIn'  : 'elastic',
						'transitionOut'  : 'elastic',
						"hideOnOverlayClick":true,
						'centerOnScroll' : true,
						"titlePosition":"over",
						"title":"<?php echo ($vv["nickname"]); ?>"
   						});
					});
   				 </script>
				<?php if($vv['img']==null): ?><a href="http://www.517xc.com/Upload//big/xy.png" id='<?php echo ($vv["userid"]); ?>'><img src="http://www.517xc.com/Upload//big/xy.png" alt="" style="border-radius:50%" width="50" height="50" /></a>
	                <?php else: ?>
	                   <a href="http://www.517xc.com/Upload//big/<?php echo ($vv['img']); ?>" id='<?php echo ($vv["userid"]); ?>'><img src="http://www.517xc.com/Upload//small/<?php echo ($vv['img']); ?>" alt="" style="width:50;height:50px;border-radius:50%"/></a><?php endif; ?>
				</td>
				<td><?php echo ($vv["nickname"]); ?></td>
				<td><?php echo ($vv['sex']==0?'保密':($vv['sex']==1?'男':'女')); ?></td>
				<td><?php echo ($vv['ntime']); ?></td>
				<td><?php echo ($vv["phone"]); ?></td>
				<td><?php echo ($vv["jtype"]); ?></td>
				<td>科目<?php echo ($vv['subjects']==0?'一':($vv['subjects']==1?'四':($vv['subjects']==2?'二':'三'))); ?></td>
			   	<td><a href="<?php echo U('List/orderList_u?userid='.$vv['userid'].'&p='.$p);?>"><?php echo ($vv["countlist"]); ?>个订单</a></td>
			   	<td><a href=""><?php echo ($vv["countreser"]); ?>个预约</a></td>
			    <td><?php echo ($vv["address"]); ?></td>
			    <td><a href="<?php echo U('userverify?verify='.$vv['verify'].'&userid='.$vv['userid'].'&p='.$p);?>"><?php echo ($vv['verify']==1?"<font style='color:green'>启用</font>":"<font style='color:red'>禁用</font>"); ?></a></td>
					<td><?php echo ($vv["lastupdate"]); ?></td>
			    <td class="td-manage">
			     <a title="编辑" href="<?php echo U('xyinfo?userid='.$vv['userid'].'&p='.$p);?>"><i class="Hui-iconfont">&#xe62c;</i></a>
			    <a title="删除" href="<?php echo U('deluser?userid='.$vv['userid'].'&p='.$p);?>" onclick="if(confirm('确定删除?')==false)return false;" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
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