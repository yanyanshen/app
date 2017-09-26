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


<title>指导员列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 指导员列表 <span class="c-gray en">&gt;</span> 指导员列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" id="sx"><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div style="float:left;margin-left:100px;">
<form action="<?php echo U('guiderList');?>" method="post">
	<br>请输入城市：<input type="text" name="city" />
	请输入教练名：<input type="text" name="nickname" />
	 <input type="submit" value='筛选' />
</form></div><br>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="<?php echo U('addzdy');?>" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6fc2;</i> 添加指导员</a>　<a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a href="<?php echo U('qx?t=qx');?>" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6fc2;</i>全部显示</a></span> <span class="r">共有数据：<strong><?php echo ($rowscount); ?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="17">指导员列表</th>
			</tr>
			<tr class="text-c">
				<th width="25"><input type="checkbox" name="" value=""></th>
				<th width="30">编号</th>
				<th width="50">账号</th>
				<th width="100">头像</th>
				<th width="100">教练名</th>
				<th width="25">性别</th>
				<th width="80">联系方式</th>
				<th width="80">课程</th>
				<th width="80">关注</th>
				<th width="30">动态</th>
				<th width="80">动态</th>
				<th width="60">计时培训</th>
				<th width="170">地址</th>
				<th width="60">城市</th>
				<th width="70">车牌号</th>
				<th width="40">证件</th>
				<th width="100">认证</th>
				<th width="60">最后操作人</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
		   <?php if(is_array($coachlist)): $i = 0; $__LIST__ = $coachlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
			    <th width="25"><input type="checkbox" name="" value=""></th>
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo ($vv["account"]); ?></td>
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
					<?php if($vv['img']==null): ?><a href="http://www.517xc.com/Upload/big/xy.png" id='<?php echo ($vv["userid"]); ?>'><img src="http://www.517xc.com/Upload/big/xy.png" alt="" style="border-radius:50%" width="50" height="50" /></a>
	                <?php else: ?>
	                   <a href="http://www.517xc.com/Upload/big/<?php echo ($vv['img']); ?>" id='<?php echo ($vv["userid"]); ?>'><img src="http://www.517xc.com/Upload/small/<?php echo ($vv['img']); ?>" alt="" style="width:50;height:50px;border-radius:50%"/></a><?php endif; ?>
				</td>
				<td><?php echo ($vv["nickname"]); ?></td>
				<td><?php echo ($vv['sex']==0?'保密':($vv['sex']==1?'男':'女')); ?></td>
				<td><?php echo ($vv["phone"]); ?></td>
				<td><?php echo ($vv["classcount"]); ?>个课程</td>
				<td><?php echo ($vv["attcount"]); ?>条关注</td>
				<td><?php echo ($vv["stu"]); ?></td>
				<td><?php echo ($vv["newscount"]); ?>条动态</td>
				<td><?php echo ($vv['timeflag']==0?'不支持':'支持'); ?></td>
			    <td><?php echo ($vv["address"]); ?></td> <td><?php echo ($vv["cityname"]); ?></td><td><?php echo ($vv["carNumber"]); ?></td>
			    <td><a href="<?php echo U('School/zhengjian?userid='.$vv['userid'].'&p='.$p.'&type=zdy');?>"><b>证件</b></a></td>
				<td><?php echo ($vv['verify']==1?'未通过':($vv['verify']==2?'正在认证':'已通过')); ?>
						&nbsp;
						<a href="<?php echo U('verify?verify='.$vv['verify'].'&userid='.$vv['userid'].'&p='.$p);?>"><?php echo ($vv['verify']==3?"<font color='red'>未通过</font>":"<font color='green'>已通过</font>"); ?></a>
				</td>
					<td><?php echo ($vv["lastupdate"]); ?></td>
			    <td class="td-manage">
			     <a title="图片管理" href="<?php echo U('School/imgmanage?userid='.$vv['userid'].'&type=zdy&p='.$p);?>"><i class="Hui-iconfont">&#xe638;</i></a>
			    <a title="编辑" href="<?php echo U('zdyinfo?userid='.$vv['userid'].'&p='.$p);?>"><i class="Hui-iconfont">&#xe62c;</i></a>
			    <a title="删除" href="<?php echo U('delguider?userid='.$vv['userid'].'&p='.$p);?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
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