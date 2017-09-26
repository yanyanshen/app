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
				url:"<?php echo U('jlinfoupdate');?>",
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
 		$("#button2").click(function(){
			var landmarkid=$("input[type='checkbox']:checked").serialize()+'&userid=<?php echo ($userid); ?>';
			$.ajax({
			    url:"<?php echo U('addjlland');?>",
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
	$("document").ready(function(){
		$("#selectcity").change(function(){
			$.post(
				"<?php echo U('user/county');?>",
				{
					type:$("#selectcity option:selected").val(),
				},function(data,status){
					data1=eval("("+data+")");
					//循环前先清空
					$("#select").html("");
					for(i=0;i<data1.length;i++){
						$("#select").append("<option value="+data1[i].id+">"+data1[i].countyname+"</option>");//在后面追加
					}
				});
			});
	});
	$("document").ready(function(){
		$("#team").change(function(){
			$.post(
				"<?php echo U('team');?>",
				{
					type:$("#team option:selected").val(),
				},function(data,status){
					data1=eval("("+data+")");
					//循环前先清空
					$("#masterid").html("");
					for(i=0;i<data1.length;i++){
						$("#masterid").append("<option value="+data1[i].userid+">"+data1[i].nickname+"</option>");//在后面追加
					}
				});
			});
	});
	$("document").ready(function(){
		$("#select").change(function(){
			$.post(
				"<?php echo U('school/landmark');?>",
				{
					type:$("#select option:selected").val(),
					userid:"<?php echo ($userid); ?>"
				},function(data,status){
					data2=eval("("+data+")");
					//循环前先清空
					$("#aa").html('');
					for(i=0;i<data2.length;i++){
						id=data2[i]['id'];
						$("#aa").append("<label><input type='checkbox' name='"+id+"' value='"+id+"'  />"+data2[i]['landname']+"　<label>");
					}
				});

			});
			$("#jxname").keyup(function(){
				$.post(
					"<?php echo U('returnjx');?>",
					{
						 jxname:$("#jxname").val(),
					},function(data,status){
						data1=eval("("+data+")");
						//循环前先清空
						$("#masterid").html('');
						for(i=0;i<data1.length;i++){
							$("#masterid").append("<option value="+data1[i].userid+">"+data1[i].nickname+"</option>");//在后面追加
							$("#masterid").css("display",'block');
						}
				});
			});
			$("#jlname").keyup(function(){
				$.post(
					"<?php echo U('returnjl');?>",
					{
						 jxname:$("#jlname").val(),
					},function(data,status){
						data1=eval("("+data+")");
						//循环前先清空
						$("#boss").html('');
						for(i=0;i<data1.length;i++){
							$("#boss").append("<option value="+data1[i].userid+">"+data1[i].nickname+"</option>");//在后面追加
							$("#boss").css("display",'block');
						}
				});
			});
			$("#masterid").change(function(){
				$("#jxname").val($("#masterid option:selected").text());
				$(this).hide();
			});
			$("#boss").change(function(){
				$("#jlname").val($("#boss option:selected").text());
				$(this).hide();
			});

				// checkAll全选/全不选
         $("#checkall").bind("click", function () {
               if ($(this).is(":checked")) {
                    $("input[type='checkbox']").prop("checked", true);
                } else {
                    $("input[type='checkbox']").prop("checked", false);
                }
         });
	});
	$(document).click(function(){
			$("#citys").hide();
			$("#masterid").hide();
			$("#boss").hide();
		   });
	</script>
		<style>
		#masterid{position:absolute;background:white;z-index:1000}
		#boss{position:absolute;background:white;z-index:1000}
	</style>
<title>编辑教练</title>
</head>
<body style="background:#9CC">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 教练列表 <span class="c-gray en">&gt;</span> 教练详情 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<form action="" method="post" class="form form-horizontal" id="form1" name='form1'>
		<div class="row cl">
			<input type="hidden" value="<?php echo ($info["userid"]); ?>"  name="userid">
			<label class="form-label col-2"><span class="c-red">*</span>教练账号：</label>
			<div class="formControls col-4" style="width:360px;">
				<input type="text" class="input-text" name="account" style="width:300px;" value="<?php echo ($info["account"]); ?>">
			</div>
			<label class="form-label col-2" style="width:100px;">姓名：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" name="nickname" style="width:300px;" value="<?php echo ($info["nickname"]); ?>">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>评分：</label>
			<div class="formControls col-4" style="width:360px;">
				<input type="text" class="input-text"  name="score" style="width:300px;" value="<?php echo ($info["score"]); ?>">
			</div>
			<label class="form-label col-2" style="width:100px;"><span class="c-red">*</span>联系方式：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text"  name="phone" style="width:300px;" value="<?php echo ($info["phone"]); ?>">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2">接送范围：</label>
			<div class="formControls col-4" style="width:360px;">
				<input type="text" name="pickrange"   class="input-text" style="width:300px;" value="<?php echo ($info["pickrange"]); ?>">
			</div>
			<label class="form-label col-2" style="width:100px;">地址：</label>
			<div class="formControls col-4">
				<input type="text" name="address"  class="input-text" style="width:300px;" value="<?php echo ($info["address"]); ?>">
			</div>
		</div>
		<div class="row cl">
				<label class="form-label col-2" >所属驾校：</label>
			   <div class="formControls col-4" style="width:360px;">
			   <input type="text" list="masterid"  id='jxname' value='<?php echo ($info['schoolname']); ?>'/>
				<select name="masterid" id="masterid" multiple="multiple" SIZE="10" style="display:none;width:100px;">
						<option value="<?php echo ($info['masterid']); ?>"><?php echo ($info['schoolname']); ?></option>
				</select></div>

				<div>
			<label class="form-label col-2" style="width:100px;">出生日期：</label>
			<div class="formControls col-4" style="width:360px;">
			<input type="text" name="birthday" onClick="WdatePicker()"  class="input-text" style="width:120px;" value="<?php echo ($info['birthday']); ?>">
			　性别：<label><input type="radio" name="sex" value=1 <?php echo ($info['sex']==1?'checked':''); ?>/>男</label>　<label><input type="radio" name="sex" value=2 <?php echo ($info['sex']==2?'checked':''); ?>/>女</label>　<label><input type="radio" name="sex" value=0 <?php echo ($info['sex']==0?'checked':''); ?>/>保密</label>
		</div></div>
		<div class="row cl">
			<label class="form-label col-2">教练类型：</label>
			<div class="formControls col-4" style="width:360px">
			<div  style="width:210px;float:left">
				<select name="jltype" id=" " >
					<option value=0 <?php echo ($info['jltype']==0?'selected':''); ?>>普通教练</option>
					<option value=1 <?php echo ($info['jltype']==1?'selected':''); ?>>私人教练(个人)</option>
					<option value=2 <?php echo ($info['jltype']==2?'selected':''); ?>>私人教练(团队)</option>
					<option value=3 <?php echo ($info['jltype']==3?'selected':''); ?>>私人教练(小打工)</option>
				</select>　所属教练：</div>
				<div style="width:70px;float:left">
			   <input type="text" list="boss"  id='jlname' value="<?php echo ($info['coachname']); ?>" />
				<select name="boss" id="boss" multiple="multiple" SIZE="10" style="display:none;width:70px;">
						<option value="<?php echo ($info['boss']); ?>"><?php echo ($info['coachname']); ?></option>
				</select></div>
				</div>
			<label class="form-label col-2" style="width:100px;">选择城市：</label>
			<div class="formControls col-4">
				<select name="cityid" id="">
					<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php echo ($info['cityid']==$v['id']?'selected':''); ?>><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
				　　是否支持计时培训：<label><input type="radio" name="timeflag" value=1 <?php echo ($info['timeflag']==1?'checked':''); ?>/>支持</label>　<label><input type="radio" name="timeflag" value=0 <?php echo ($info['timeflag']==0?'checked':''); ?>/>不支持</label>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2" >学员人数：</label>
			<div class="formControls col-4" style="width:200px">
				<input type="text" name="allcount"   class="input-text" style="width:200px;" value="<?php echo ($info["allcount"]); ?>">
			</div>
			<label class="form-label col-2" style="width:100px;">已通过人数：</label>
			<div class="formControls col-4"  style="width:200px;">
				<input type="text" class="input-text"  name="passedcount"  style="width:200px;" value="<?php echo ($info["passedcount"]); ?>">
			</div><label class="form-label col-2" style="width:100px;">通过率：</label>
			<div class="formControls col-4"  style="width:200px;">
				<input type="text" id="datemax" class="input-text"  value="<?php echo ($passrate); ?>" style="width:150px;">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2" >总评价数：</label>
			<div class="formControls col-4" style="width:200px">
				<input type="text" name="evalutioncount"  class="input-text" style="width:200px;" value="<?php echo ($info["evalutioncount"]); ?>">
			</div>
			<label class="form-label col-2" style="width:100px;">好评数：</label>
			<div class="formControls col-4"  style="width:200px;">
				<input type="text"  class="input-text"  name="praisecount" style="width:200px;" value="<?php echo ($info["praisecount"]); ?>">
			</div><label class="form-label col-2" style="width:100px;">好评率：</label>
			<div class="formControls col-4"  style="width:200px;">
				<input type="text" id="datemax" class="input-text"  value="<?php echo ($goodrate); ?>" style="width:150px;">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2">教练简介：</label>
			<div class="formControls col-4" style="width:60%">
		    	<textarea name="introduction"  cols="30" rows="10" style="width:100%"><?php echo ($info["introduction"]); ?></textarea>
		    </div>
		</div>
		<?php echo ($json); ?>
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<b  class="btn btn-primary radius" id="button1" onclick='myCheck()'><i class="Hui-iconfont">&#xe632;</i> 保存更新</b>
				<a href="<?php echo U('coachList?p='.$p);?>"><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回列表</button></a>
				<button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
</form>
<form action="" method="post" class="form form-horizontal" id="form2">
		<div class="row cl">
				<input type="hidden" value="<?php echo ($info["userid"]); ?>"  name="userid">
			<label class="form-label col-2">覆盖地区：</label>
			<div class="formControls col-4" style="width:60%">
		    	<table border="1px" cellspacing=0 style="width:100%">
		    		<tr>
		    			<td align="center" colspan=2>已覆盖地区</td>
		    		</tr>
		    		<tr>
		    			<td align="center" width="150">地区</td>
		    			<td align="center">地标</td>
		    		</tr>
		    		<?php if(is_array($countys)): foreach($countys as $k=>$v): ?><tr>
			    			<td align="center" width="150"><?php echo ($k); ?></td>
			    			<td >
			    				<?php if(is_array($v)): $i = 0; $__LIST__ = $v;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i; echo ($vv['landname']); ?><a href="<?php echo U('?userid='.$userid.'&p='.$p.'&sid='.$vv['sid']);?>"><i class="Hui-iconfont"  style="color:red">&#xe6a6;</i></a> 　　<?php endforeach; endif; else: echo "" ;endif; ?>
			    			</td>
			    		</tr><?php endforeach; endif; ?>
		    	</table><br>
		    	<table border="1px" cellspacing=0 style="width:100%">
		    		<tr>
		    			<td align="center" width="150" colspan='3'>添加地标</td>

		    		</tr>
		    		<tr>
		    			<td align="center" width="150">城市</td>
		    			<td align="center"  width="150">区/(县)</td>
		    			<td align="center">还未添加的地标</td>
		    		</tr>
		    		<tr>
		    			<td align="center" width="150">
		    				<select name="selectcity" id="selectcity">
		    				<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><option value="<?php echo ($c['id']); ?>" <?php echo ($c['id']==$cityid?'selected':''); ?>><?php echo ($c['cityname']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		    				</select>
		    			</td>
		    			<td align="center">
			    			<select name="select" id="select">
		    				<?php if(is_array($count)): $i = 0; $__LIST__ = $count;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><option value="<?php echo ($c['id']); ?>"><?php echo ($c['countyname']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		    				</select>&nbsp;<label>全选<input type="checkbox" id="checkall"></label>
		    			</td>
		    			<td  id='aa'>
		    			<?php if(is_array($lands)): $i = 0; $__LIST__ = $lands;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><label><input type='checkbox' name="<?php echo ($c['id']); ?>" value="<?php echo ($c['id']); ?>"/><?php echo ($c['landname']); ?>　</label><?php endforeach; endif; else: echo "" ;endif; ?>
		    			</td>
		    		</tr>
		    	</table>
		    </div>
		</div>

		<div class="row cl">
			<div class="col-10 col-offset-2">
				<b class="btn btn-primary radius" id="button2"><i class="Hui-iconfont">&#xe632;</i> 添加地标</b>
			<a href="<?php echo U('coachList?p='.$p);?>"><b class="btn btn-secondary radius" ><i class="Hui-iconfont">&#xe632;</i> 返回列表</b></a>
				<button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
	</form>
</div>
</div>
</body>
</html>