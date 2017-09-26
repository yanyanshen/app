<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link href="/Public/517/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/icheck/icheck.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/Hui-iconfont/1.0.6/iconfont.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/js/fancybox1.3.4/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" />
<script src="/Public/517/js/jquery-1.12.0.min.js"></script>
<script src="/Public/517/js/jquery.fancybox-1.3.1.pack.js"></script>
<title>认证信息管理</title>
</head>
<body style="background:#9CC">
<div class="pd-20">
	<form action="<?php echo U('imgupload?t=2&userid='.$userid.'&type='.$type.'&p='.$p);?>" method="post" class="form form-horizontal" id="form2" name='form2' enctype="multipart/form-data">
		<label class="form-label col-2">认证资料--<?php echo ($types); ?>--<?php echo ($nickname); ?>：</label>
		<div class="row cl" style="width:80%;margin:0 auto">
		<div style="width:auto">
				<?php if(is_array($zj)): $k = 0; $__LIST__ = $zj;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><div style="float:left;margin-right:10px;margin-bottom:10px;width:auto;height:auto;text-align:center;line-height:220px" >
					<img src="<?php echo URL; echo ($v['imgurl']); ?>"/>&nbsp;
					<font color="red"><?php echo ($v['paperstype']=='jsz'?'驾驶证':($v['paperstype']=='jlz'?'教练证':($v['paperstype']=='yyzz'?'营业执照':'身份证'))); ?></font>
					<a href="<?php echo U('delzj?id='.$v['id'].'&userid='.$userid.'&type='.$type.'&p='.$p);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		</div>
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<a href='<?php echo U("$url?p=".$p);?>'><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回列表</button></a>
				<button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
</div>
</div>
</body>
</html>