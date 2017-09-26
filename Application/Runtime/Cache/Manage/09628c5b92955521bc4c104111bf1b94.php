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
<title>动态列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 动态列表 <span class="c-gray en">&gt;</span>动态列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"> </span> <span class="r">共有数据：<strong><?php echo ($rowscount); ?></strong> 条</span> </div>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="width:100%;height:auto;">
	<div style="width:100%;height:50px">
		<div style="float:left;height:50px;margin-right:10px"><img src="http://www.517xc.com/Upload/small/<?php echo ($v['user']['img']); ?>" width='50' style="border-radius:50%" /></div><div style="float:left;line-height:50px"><?php echo ($v['user']['nickname']); ?>　<?php echo ($v["time"]); ?>　　<a href="<?php echo U('delnews?newsid='.$v['newsid'].'&p='.$p);?>" style="color:red" onclick="if(confirm('确定删除?')==false)return false;">删除</a></div>
	</div><br>
	<div style="width:100%;height:20px">
			<?php echo ($v["content"]); ?>
	</div><br>
	<div style="width:100%;height:auto">
	<?php if(is_array($v["img"])): $i = 0; $__LIST__ = $v["img"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><img src="http://www.517xc.com/<?php echo ($vv['imgname']); ?>" style="margin-right:10px"/><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
</div><br><hr><br><?php endforeach; endif; else: echo "" ;endif; ?>
<?php echo ($page); ?>
</body>
</html>