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
			$("#b1").click(function(){
				var land=$("#newtrain").val()+"&'<?php echo ($cityid); ?>'";
				$.ajax({
					url:"<?php echo U('addcitytrain');?>",
					type:"get",
					data:land,
					success: function(data) {
					   switch(data){
						case '0':alert('添加失败');break;
						default:  alert("添加成功");location.reload();
						break;
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
<form action="" method="post" class="form form-horizontal" id="form1">
		<div class="row cl">
			<label class="form-label col-2">当前城市：</label>
			<div class="formControls col-4" style="width:60%">
		    	<input type="text"  class="input-text" value="<?php echo ($cityname); ?>" style="width:200px" readonly/>
		    </div>
		</div>
		<div class="row cl">
			<label class="form-label col-2">目前所有基地：</label>
			<div class="formControls col-4" style="width:62%">
		    	<?php if(is_array($train)): $i = 0; $__LIST__ = $train;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; echo ($v['trname']); ?><a href="<?php echo U('delcitytrain?id='.$v['id'].'&p='.$p.'&cityid='.$cityid);?>" onclick="if(confirm('确定删除?')==false)return false;"><i class="Hui-iconfont"  style="color:red" >&#xe6a6;</i></a>&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
		    </div>
		</div>
		<div class="row cl">
			<label class="form-label col-2">添加新基地(多个基地以英文逗号隔开)：</label>
			<div class="formControls col-4" style="width:62%">
		    	<input type="text"  class="input-text"  style="width:200px" id='newtrain'/>　<button class="btn btn-secondary radius" type="button" id='b1'><i class="Hui-iconfont">&#xe632;</i> 添加基地</button>
		    <a href="<?php echo U('trainList?p='.$p);?>"><b class="btn btn-secondary radius" ><i class="Hui-iconfont">&#xe632;</i> 返回列表</b></a>
		    </div>
		</div>
</form>
</div>

</body>
</html>