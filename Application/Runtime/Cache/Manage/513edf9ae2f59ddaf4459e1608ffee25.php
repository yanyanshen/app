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
<title>图片信息管理</title>
</head>
<body style="background:#9CC">
<div class="pd-20">
	<form action="<?php echo U('imgupload?t=1&userid='.$userid.'&type='.$type.'&p='.$p);?>" method="post" class="form form-horizontal" id="form1" name='form1' enctype="multipart/form-data">
		<div class="row cl">
		<input type="hidden" name="masterid" value="<?php echo ($userid); ?>"/>
			<label class="form-label col-2"><?php echo ($nickname['nickname']); ?>：</label>
			<div class="formControls col-4">
			<script>
   					 $(function(){
					 $('#img').fancybox({
						'transitionIn'  : 'elastic',
						'transitionOut'  : 'elastic',
						"hideOnOverlayClick":true,
						'centerOnScroll' : true,
						"titlePosition":"over",
						"title":"<?php echo ($nickname['img']); ?>"
   						});
					});
   				 </script>
				<?php if($nickname['img']==null): ?><a href="<?php echo URL ;?>/Upload/big/xy.png" id='img'><img src="<?php echo URL ;?>/Upload/small/xy.png" alt="" style="border-radius:50%" width="100" /></a>
					  　　　　　　　　　　上传头像:
	              <?php else: ?>
	                  <a href="<?php echo URL ;?>/Upload/big/<?php echo ($nickname['img']); ?>" id='img'><img src="<?php echo URL ;?>/Upload/small/<?php echo ($nickname['img']); ?>" alt="" style="border-radius:50%" width="100" height="100" /></a>
	                  　　　　　　　　　修改头像:<?php endif; ?><input type="file" name="ImageFiled"/><input type="submit" value="点击上传"/>
			</div>
		</div>
</form><br>
	<form action="<?php echo U('imgupload?t=2&userid='.$userid.'&type='.$type.'&p='.$p);?>" method="post" class="form form-horizontal" id="form2" name='form2' enctype="multipart/form-data">
		<label class="form-label col-2">教学环境：</label><?php echo ($i); ?>
		<div class="row cl" style="width:80%;margin:0 auto">
		<div style="background:red;width:700px">
				<?php if(is_array($img)): $k = 0; $__LIST__ = $img;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><div style="float:left;margin-right:30px;margin-bottom:10px;width:200px;height:220px;text-align:center;line-height:220px" >
					<script>
   					 $(function(){
					 $('#<?php echo ($v["id"]); ?>').fancybox({
						'transitionIn'  : 'elastic',
						'transitionOut'  : 'elastic',
						"hideOnOverlayClick":true,
						'centerOnScroll' : true,
						"titlePosition":"over",
						"title":"<?php echo ($v["name"]); ?>"
   						});
					});
   				 </script>
					<a href="<?php echo URL ; echo ($bigurl); echo ($v['name']); ?>" id='<?php echo ($v["id"]); ?>'  ><img src="<?php echo URL ; echo ($v['imgname']); ?>"/></a>&nbsp;<a href="<?php echo U('?id='.$v['id'].'&userid='.$userid.'&type='.$type.'&p='.$p);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a></div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		</div>
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<input type="file" name="file[]" multiple/><input type="submit" value="点击上传"/></form>

				<a href="<?php echo U($list);?>"><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回列表</button></a>
				<button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>

</div>
</div>
</body>
</html>