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
<script type="text/javascript" src="/Public/517/js/jquery-1.12.0.min.js"></script>
	<script>
	$("document").ready(function(){
		$("#button2").click(function(){
			var landmarkid=$("input[type='checkbox']:checked").serialize()+'&userid=<?php echo ($userid); ?>';
			$.ajax({
			    url:"<?php echo U('addjxtrain');?>",
			    type:"get",
		        data:landmarkid,
		        success: function(data) {
		           if(data==1){
		        	   alert("添加成功");
		        	   location.reload();
		           }else{
		        	   alert("添加失败");
		           }
		        }
			});
		});
	});
	</script>
<title>编辑基地</title>
</head>
<body style="background:#9CC">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 驾校列表 <span class="c-gray en">&gt;</span> 添加基地 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="pd-20">
<form action="" method="post" class="form form-horizontal" id="form2">
		<div class="row cl">
				<input type="hidden" value="<?php echo ($info["userid"]); ?>"  name="userid">
			<label class="form-label col-2">进驻基地：</label>
			<div class="formControls col-4" style="width:60%">
		    	<table border="1px" cellspacing=0 style="width:100%">
		    		<tr>
		    			<td align="center" colspan=2><?php echo ($nickname); ?>　已进驻基地</td>
		    		</tr>
		    		<tr>
		    			<td align="center" width="150">城市</td>
		    			<td align="center">基地</td>
		    		</tr>

			    		<tr>
			    			<td width="150" align="center"><?php echo ($city); ?></td>

			    			<td><?php if(is_array($schooltrain)): foreach($schooltrain as $k=>$v): ?><a href="<?php echo U('trainaddcoach?userid='.$userid.'&trainid='.$v['id']);?>"><?php echo ($v['trname']); ?></a><a href="<?php echo U('?sid='.$v['sid'].'&userid='.$userid.'&cityid='.$cityid);?>"><i class="Hui-iconfont"  style="color:red">&#xe6a6;</i></a>&nbsp;<?php endforeach; endif; ?>
			    			</td>
			    		</tr>
		    	</table><br>
		    	<table border="1px" cellspacing=0 style="width:100%">
		    		<tr>
		    			<td align="center" width="150" colspan='3'>添加基地</td>

		    		</tr>
		    		<tr>
		    			<td align="center" width="150">城市</td>
		    			<td align="center">还未添加的基地</td>
		    		</tr>
		    		<tr>
		    			<td align="center" width="150">
		    				<?php echo ($city); ?>
		    			</td>
		    			<td id='aa'>
		    			<?php if(is_array($train)): $i = 0; $__LIST__ = $train;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><label><input type='checkbox' name="<?php echo ($c['id']); ?>" value="<?php echo ($c['id']); ?>"/><?php echo ($c['trname']); ?>　</label><?php endforeach; endif; else: echo "" ;endif; ?>
		    			</td>
		    		</tr>
		    	</table>
		    </div>
		</div>
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<b class="btn btn-primary radius" id="button2"><i class="Hui-iconfont">&#xe632;</i> 添加基地</b>
			<a href="<?php echo U('schoolList?p='.$p);?>"><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回列表</button></a>
				<button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
	</form>
</div>
</div>
</body>
</html>