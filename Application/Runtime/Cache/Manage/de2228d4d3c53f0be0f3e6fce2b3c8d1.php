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
<script  src="/Public/517/js/My97DatePicker/WdatePicker.js"></script>
	<script>
	$("document").ready(function(){		
 		$("#button1").click(function(){
			$.ajax({
				url:"<?php echo U('xyinfoupdate');?>",
			    type:"POST",
		        data:$('#form1').serialize(),
		        success: function(data) {
		           if(data==1){
		        	   alert("更新成功");
		        	   location.reload();
		           }else if(data==0){
		        	   alert("更新失败");
		           }
		        }
			});
		});
	});
 		$("document").ready(function(){
 			$("#cityid").change(function(){
 				$.post(
 					"<?php echo U('returnCoach');?>",
 					{
 						cityid:$("#cityid option:selected").val(),
 					},function(data,status){
 						data1=eval("("+data+")");
 						//循环前先清空
 						$("#school").html("");
 						$("#coach").html("");
 						$("#guider").html("");
 						for(i=0;i<data1.school.length;i++){
 							$("#school").append("<option value="+data1.school[i].userid+">"+data1.school[i].nickname+"</option>");//在后面追加
 						}
 						for(i=0;i<data1.coach.length;i++){
 							$("#coach").append("<option value="+data1.coach[i].userid+">"+data1.coach[i].nickname+"</option>");//在后面追加
 						}
 						for(i=0;i<data1.guider.length;i++){
 							$("#guider").append("<option value="+data1.guider[i].userid+">"+data1.guider[i].nickname+"</option>");//在后面追加
 						}
 					});
 				});
 		});
	</script>
<title>编辑学员</title>
</head>
<body style="background:#9CC">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 学员列表 <span class="c-gray en">&gt;</span> 编辑学员 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="pd-20">
	<form action="" method="post" class="form form-horizontal" id="form1" name='form1'>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>学员账号：</label>
			<div class="formControls col-4" style="width:360px;">
			<input type="hidden" value="<?php echo ($info["userid"]); ?>"  name="userid">
				<input type="text" class="input-text" name="account" style="width:300px;" value="<?php echo ($info["account"]); ?>">
			</div>
			<label class="form-label col-2" style="width:100px;">姓名：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" name="nickname" style="width:300px;" value="<?php echo ($info["nickname"]); ?>">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>出生日期：</label>
			<div class="formControls col-4" style="width:360px;" >
				<input type="text" class="input-text" name="birthday" onClick="WdatePicker()" style="width:300px;" value="<?php echo ($info["birthday"]); ?>">
			</div>
			<label class="form-label col-2" style="width:100px;">性别：</label>
			<div class="formControls col-4">
				<lable><input type="radio" name="sex" value=1 <?php echo ($info['sex']==1?'checked':''); ?>>男</labl>　	<label><input type="radio" name="sex" value=2  <?php echo ($info['sex']==2?'checked':''); ?>>女</label>　	<label><input type="radio"  name="sex" value=0  <?php echo ($info['sex']==0?'checked':''); ?> >保密</label>　
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>联系方式：</label>
			<div class="formControls col-4" style="width:360px;">
				<input type="text" class="input-text" name="phone" style="width:300px;" value="<?php echo ($info["phone"]); ?>">
			</div>
			<label class="form-label col-2" style="width:100px;">驾照类型：</label>
			<div class="formControls col-4">
				<select name="jtype" id="">
					<option value="C1" <?php echo ($info['jtype']=='C1'?'selected':''); ?>>C1</option>
					<option value="C2" <?php echo ($info['jtype']=='C2'?'selected':''); ?>>C2</option>
					<option value="C3" <?php echo ($info['jtype']=='C3'?'selected':''); ?>>C3</option>
					<option value="C4" <?php echo ($info['jtype']=='C4'?'selected':''); ?>>C4</option>
					<option value="A1" <?php echo ($info['jtype']=='A1'?'selected':''); ?>>A1</option>
					<option value="A2" <?php echo ($info['jtype']=='A2'?'selected':''); ?>>A2</option>
					<option value="A3" <?php echo ($info['jtype']=='A3'?'selected':''); ?>>A3</option>
					<option value="B1" <?php echo ($info['jtype']=='B1'?'selected':''); ?>>B1</option>
					<option value="B2" <?php echo ($info['jtype']=='B2'?'selected':''); ?>>B2</option>
					<option value="D" <?php echo ($info['jtype']=='D'?'selected':''); ?>>D</option>
					<option value="E" <?php echo ($info['jtype']=='E'?'selected':''); ?>>E</option>
					<option value="F" <?php echo ($info['jtype']=='F'?'selected':''); ?>>F</option>
					<option value="M" <?php echo ($info['jtype']=='M'?'selected':''); ?>>M</option>
					<option value="N" <?php echo ($info['jtype']=='N'?'selected':''); ?>>N</option>
				</select>
			当前科目
			<select name="subjects">
				<option value="0" <?php echo ($info['subjects']=='0'?'selected':''); ?>>科目一</option>
				<option value="2" <?php echo ($info['subjects']=='1'?'selected':''); ?>>科目二</option>
				<option value="3" <?php echo ($info['subjects']=='2'?'selected':''); ?>>科目三</option>
				<option value="1" <?php echo ($info['subjects']=='3'?'selected':''); ?>>科目四</option>
			</select></div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>学员住址：</label>
			<div class="formControls col-4" style="width:360px;">
				<input type="text" class="input-text" name="address" style="width:500px;" value="<?php echo ($info["address"]); ?>">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>所在城市：</label>
			<select name="cityid" id="cityid">
			<option value=" ">请选择</option>
					<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>　　　
			当前驾校:<select name="myschoolid" id="school">
				      <option value="<?php echo ($info['drisch']['myschoolid']); ?>"><?php echo ($info['drisch']['schoolnickname']); ?></option>
			       </select>　　　　
			当前教练:<select name="mycoachid" id="coach">
				<option value="<?php echo ($info['drisch']['mycoachid']); ?>"><?php echo ($info['drisch']['coachnickname']); ?></option>
			</select>　　　　
			当前指导员:<select name="myguiderid" id="guider">
				<option value="<?php echo ($info['drisch']['myguiderid']); ?>"><?php echo ($info['drisch']['guidernickname']); ?></option>
			</select>　
		</div>
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<b  class="btn btn-primary radius" id="button1" onclick='myCheck()'><i class="Hui-iconfont">&#xe632;</i> 保存更新</b>
				<a href="<?php echo U('userList');?>"><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回列表</button></a>
				<button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
</form>
</div>
</body>
</html>