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
 		$("#button1").click(function(){
			$.ajax({
				url:"<?php echo U('addclass');?>",
			    type:"POST",
		        data:$('#form1').serialize(),
		        success: function(data) {
		           if(data!=1 && data!=0){
		        	   alert("添加成功");
		        	   location.href="<?php echo U('trainclassList');?>?userid="+data;
		           }else if(data==0){
		        	   alert("添加失败");
		           }else{
		        	   alert("提交内容不能有空值");
		           }
		        }
			});
		});
	});
	</script>
<title>添加驾校课程信息</title>
</head>
<body style="background:#9CC">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 驾校列表 <span class="c-gray en">&gt;</span> 添加驾校课程信息 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="pd-20">
	<form action="" method="post" class="form form-horizontal" id="form1" name='form1'>
		<div class="row cl">
		<input type="hidden" name="masterid" value="<?php echo ($userid); ?>"/>
			<label class="form-label col-2"><span class="c-red">*</span>驾校：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" name="account" style="width:500px;" value="<?php echo ($nickname); ?>" readonly>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>课程名：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" name="name" style="width:500px;">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>选择车型：</label>
			<select name="carname" id="">
				<option value="普桑">普桑</option>
				<option value="夏利">夏利</option>
				<option value="捷达">捷达</option>
				<option value="桑塔纳">桑塔纳</option>
				<option value="奇瑞">奇瑞</option>
				<option value="富康">富康</option>
				<option value="爱丽舍">爱丽舍</option>
				<option value="比亚大">比亚大</option>
				<option value="斯柯达">斯柯达</option>
				<option value="伊兰">伊兰</option>
				<option value="旗云">旗云</option>
				<option value="宝来">宝来</option>
				<option value="双环">双环</option>
				<option value="万丰">万丰</option>
				<option value="羚羊">羚羊</option>
				<option value="东风">东风</option>
				<option value="解放">解放</option>
				<option value="江淮">江淮</option>
				<option value="江铃">江铃</option>
				<option value="庆岭">庆岭</option>
				<option value="福田">福田</option>
				<option value="时代">时代</option>
				<option value="东风标致">东风标致</option>
				<option value="雪铁龙">雪铁龙</option>
				<option value="长城皮卡">长城皮卡</option>
				<option value="中兴皮卡">中兴皮卡</option>
				<option value="货车">货车</option>
				<option value="客车">客车</option>
				<option value="公交车">公交车</option>
			</select>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>官方价：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" name="officialprice" style="width:500px;">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>517全款价：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" name="whole517price" style="width:500px;">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>517预付费价：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" name="prepay517price" style="width:500px;">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>定金：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" name="prepay517deposit" style="width:500px;">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>等待时间：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" name="waittime" style="width:500px;">天
			</div>
		</div>
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<b  class="btn btn-primary radius" id="button1" onclick='myCheck()'><i class="Hui-iconfont">&#xe632;</i> 添加驾校</b>
				<a href="<?php echo U('schoolList');?>"><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回列表</button></a>
				<button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
</form>
</div>
</div>
</body>
</html>