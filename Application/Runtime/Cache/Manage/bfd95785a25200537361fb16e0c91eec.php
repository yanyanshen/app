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
		//控制修改学员信息标签的样式
		$("input").css("background","#9cc");$("input").css("height",25);$("input").css("width",500);$("#formclassinfo input").css("width",220);
		$("input").css("border",0);
		$("#in1").css("width",100);$("#in1").css("height",25);$("#in1").css("background","gray");$("#returndate").css("background",'white').css("width",120);
		$("#fkfs input").css("width",300);
		$("input").focus(function(){
			$(this).css("background","white");
		});
		$("input").blur(function(){
			$(this).css("background","#9cc");
			$("#returndate").css("background",'white')
		});
		//修改学员信息
		$("#b1").click(function(){
			$.ajax({
				url:"<?php echo U('list_updatestu');?>",//  订单控制器里面的方法
			    type:"POST",
		        data:$('#fomestuinfo').serialize(),
		        success: function(data) {
					if(data==1){
						alert('修改成功');
						location.reload();
					}else{
						alert('修改失败');
					}
		        }
			});
		});
		//修改课程信息
		$("#b2").click(function(){
			$.ajax({
				url:"<?php echo U('class_updatestu');?>",//  订单控制器里面的方法
			    type:"POST",
		        data:$('#formclassinfo').serialize(),
		        success: function(data) {
					if(data==1){
						alert('修改成功');
						location.reload();
					}else{
						alert('修改失败');
					}
		        }
			});
		});
		//修改支付方式
		$("#b3").click(function(){
			$.ajax({
				url:"<?php echo U('zhifu_updatestu');?>",//  订单控制器里面的方法
			    type:"POST",
		        data:$('#fkfs').serialize(),
		        success: function(data) {
					if(data==1){
						alert('修改成功');
						location.reload();
					}else{
						alert('修改失败');
					}
		        }
			});
		});
		//驾校名字文本框根据选择来变化
		//根据驾校的改变去改变课程
		$("#school").change(function(){
			$("#allschool").val($("#school option:selected").text());$("#school").css("display",'none');
			$.post(
				"<?php echo U('returnclass');?>",
				{
					userid:$("#school option:selected").val(),
				},function(data,status){
					data2=eval("("+data+")");
					//循环前先清空
					$("#allclass").html('');$("#alltrain").html('');
					for(i=0;i<data2.trainclass.length;i++){
						id=data2.trainclass[i]['tcid'];
						$("#allclass").append("<option value="+id+">"+data2.trainclass[i].name+"</option>");//在后面追加
				  	}
					for(i=0;i<data2.train.length;i++){
						id=data2.train[i]['id'];
						$("#alltrain").append("<option value="+id+">"+data2.train[i].trname+"</option>");//在后面追加
				  	}
			});
		});
		//根据不同的课程来显示不同的价格
		$("#allclass").change(function(){
			$.post(
				"<?php echo U('returnprice');?>",
				{
					listid:$("#allclass option:selected").val(),
				},function(data,status){
					data2=eval("("+data+")");
					//循环前先清空
					//$("#allclass").html('');
					//for(i=0;i<data2.length;i++){
						$("#officialprice").val(data2.officialprice);
						$("input[name='whole517price']").val(data2.whole517price);
				  //}
			});
		});
		//根据不同的基地来显示这个驾校不同的教练
		$("#alltrain").change(function(){
			$("#trname").val($("#alltrain option:selected").text());
			$.post(
				"<?php echo U('returncoach');?>",
				{
					 data:$('#alltrain').serialize()+"&schoolid="+$("#school option:selected").val(),
				},function(data,status){
					data2=eval("("+data+")");
					//循环前先清空
					$("#allcoach").html('');
					for(i=0;i<data2.length;i++){
						id=data2[i]['userid'];
						$("#allcoach").append("<option value="+id+">"+data2[i].nickname+"</option>");//在后面追加
						//$("#allcoach").append("<option value="+id+"></option>");//在后面追加
					}
			});
		});
		$("input[name='stucount']").keyup(function(){
			$("input[name='preferentialprice']").val('');
			$("input[name='preferentialprice']").val($("input[name='stucount']").val()*$("#prepay517deposit").val());
		});
		//更换客服
		$("#customer").change(function(){
			$("#i").html($("#customer option:selected").text());
			$.post(
				"<?php echo U('updatecustomer');?>",
				{
					 id:$("#customer option:selected").val(),
					 listid:'<?php echo ($listinfo["listid"]); ?>',
				},function(data,status){
						if(data==0){
							alert('更换失败');
						}
							location.reload();
			});
		});
		//根据用户的输入去数据库找相应的驾校
		$("#allschool").keyup(function(){
			$.post(
				"<?php echo U('returnallschool');?>",
				{
					 nickname:$("#allschool").val(),
				},function(data,status){
					data2=eval("("+data+")");
					//循环前先清空
					$("#school").html('');
					for(i=0;i<data2.length;i++){
						id=data2[i]['userid'];
						$("#school").append("<option value="+id+">"+data2[i].nickname+"</option>");//加
						$("#s1 select").css("display","block");
				  	}
			});
		});
		//添加回访记录
 		$("#bb").click(function(){
			$.ajax({
				url:"<?php echo U('addlistdocument');?>",
			    type:"POST",
		        data:$('#form5').serialize(),
		        success: function(data) {
		           switch(data){
		           		case '0':alert('添加失败');break;
		           		case '2':alert('提交内容不能有空值');break;
		           		default:location.reload();break;
		           }
		        }
			});
		});
		$("#s1 input").css("width",110);
		$("#school").css("width",110);
	});$(document).click(function(){
		$("#school").hide();
	});
	</script>
	<style>
		#school{position:absolute;background:white}
	</style>
<title>订单处理</title>
</head>
<body style="background:#9CC">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 订单列表 <span class="c-gray en">&gt;</span> 订单处理 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
		<div style="width:100%;height:152px;">
				<table style="width:99.99%;height:150px" border='2'>
					<tr>
						<td colspan='8'>　<b>订单状况</b></td>
					</tr>
					<tr>
						<td width="10%" bgcolor='#ebebeb'>　<b>订单号:</b></td>
						<td width="15%"><b><?php echo ($listinfo["listid"]); ?></b></td>
						<td width="10%" bgcolor='#ebebeb'>　<b>订单类型:</b></td>
						<td width="15%"><b><?php echo ($listinfo['listtype']==0?'在线订单':'人工订单'); ?>:</b></td>
						<td width="10%" bgcolor='#ebebeb'>　<b>订单状态:</b></td>
						<td width="15%"><b><?php echo ($vv['state']==0?'待付款':($vv['state']==1?'待确认':($vv['state']==2?'待评价':($vv['state']==3?'已完成':'全部')))); ?>:</b></td>
						<td width="10%" bgcolor='#ebebeb'>　<b>下次回访:</b></td>
						<td width="15%"><b><?php echo ($listinfo['returndate']); ?></b></td>
					</tr>
					<tr>
						<td width="10%" bgcolor='#ebebeb'>　<b>下单时间:</b></td>
						<td width="15%"><b><?php echo ($listinfo["listtime"]); ?></b></td>
						<td width="10%" bgcolor='#ebebeb'>　<b>支付方式:</b></td>
						<td width="15%"><b><?php echo ($listinfo['payment_type']==1?'支付宝':''); ?></b></td>
						<td width="10%" bgcolor='#ebebeb'>　<b>支付状态:</b></td>
						<td width="15%"><b></b></td>
						<td width="10%" bgcolor='#ebebeb'>　<b>跟单客服:</b></td>
						<td width="15%" id="td"><l id='l'><b><?php echo ($listinfo['username']); ?></b></l>&nbsp;&nbsp;
							<select  id="customer">
							<option value="">修改</option>
							<?php if(is_array($customer)): $i = 0; $__LIST__ = $customer;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["username"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan='8'>
						<table width="100%" >
							<tr align='center'>
								<td ><button>操作日志</button></td>
								<td><button>打印收据</button></td>
								<td><button>打印快递单</button></td>
								<td><button>发送短信</button></td>
								<td><button>发送短信凭证</button></td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
		</div><br>
		<div>
			<form id="fomestuinfo" action='' method="post">
				<table style="width:99.99%;height:180px" border='2'>
					<tr>
						<td colspan='4'><b>用户信息</b></td>
					</tr>
					<input type="hidden" name="listid" value="<?php echo ($listinfo['listid']); ?>"/>
					<tr>
						<td width="10%" bgcolor='#ebebeb'>　<b>客户姓名:</b></td>
						<td width="30%"><b><?php echo ($nickname); ?></b></td>
						<td width="10%" bgcolor='#ebebeb'>　<b>客户电话:</b></td>
						<td><b><?php echo ($listinfo["phone"]); ?></b></td>
					</tr>
					<tr>
						<td width="10%" bgcolor='#ebebeb'>　<b>学员信息:</b></td>
						<td colspan='3'><b><input type="text" name="masname" value="<?php echo ($listinfo["masname"]); ?>" style="width:500px;"/></b><b><input type="text" name="phone" value="<?php echo ($listinfo["phone"]); ?>"/></b></td>
					</tr>
					<tr>
						<td width="10%" bgcolor='#ebebeb'>　<b>所在位置:</b></td>
						<td colspan='3'><b><input type="text" name="address" value="<?php echo ($listinfo["address"]); ?>" style="width:500px;"/></b></td>
					</tr>
					<tr>
						<td width="10%" bgcolor='#ebebeb' >　<b>备注:</b></td>
						<td colspan='3'><b><input type="text" name="remark" value="<?php echo ($listinfo["remark"]); ?>" style="width:500px;"/></b></td>
					</tr>
					<tr>
						<td colspan='4'>　　　　　　　　　　<b  class="btn btn-primary radius" id="b1" style="height:25px;line-height:13px"><i class="Hui-iconfont">&#xe632;</i> 修改学员信息</b></td>
					</tr>
				</table>
			</form>
		</div><br />
		<div style="width:100%;height:152px;">
		     <form id="formclassinfo">
				<table style="width:99.99%;height:150px" border='2'>
					<tr>
					<input type="hidden" name="listid" value="<?php echo ($listinfo['listid']); ?>" id="listid"/>
						<td colspan='8'>　<b>意向课程</b></td>
					</tr>
					<tr>
					    <td width="15%" bgcolor='#ebebeb'>　<b>课程名称:</b>
							<select name="allclass" id="allclass" style="width:200px">
									<option value="">请选择</option>
							</select>
						</td>
						<td width="12%"><b><input type="text" name="name" value="<?php echo ($class["name"]); ?>" id="name"></b></td>
						<td width="12%" bgcolor='#ebebeb'>　<b>请输入驾校名称:</b>
						<!--
							<span><input type="text" name="allschool" id="in1"/>
								<?php if(is_array($allschools)): $i = 0; $__LIST__ = $allschools;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>"><?php echo ($v["nickname"]); ?></option>
									<li></li><?php endforeach; endif; else: echo "" ;endif; ?>
							</span>
						-->
						<div id='s1'>
                        <input list="school" name="allschool" id="allschool" style="width:100px;">
						 <select name="school" id="school" multiple="multiple" SIZE="10" style="display:none">
						 	<option value=""></option>
						 </select>
                         </div>
						</td>
						<td width="12%"><b><input type="text" name="objectname" value="<?php echo ($school['schoolname']["nickname"]); ?>" id=""></b></td>
						<td width="12%" bgcolor='#ebebeb'>　<b>选择基地:</b>
							<select name="alltrain" id="alltrain" style="width:100px">
							<option value=" ">请选择</option>
							</select>
						</td>
						<td width="12%"><b><input type="text" id="trname" name="trname" value="<?php echo ($listinfo["trname"]); ?>"  style="width:300px"></b></td>
						<td width="12%" bgcolor='#ebebeb'>　<b>选择教练:</b>
							<select name="allcoach" id="allcoach" style="width:100px">
								<option value="">请选择</option>
							</select>
						</td>
						<td width="12%"><b><input type="text" name="" value="<?php echo ($school["nickname"]); ?>"  style="width:300px"></b></td>
					</tr>
					<tr>
						<td width="15%" bgcolor='#ebebeb'>　<b>负责人/负责人电话:</b></td>
						<td width="12%"><b><input type="text" name="connectteacher" value="<?php echo ($school['schoolname']["connectteacher"]); ?>"></b></td>
						<td width="12%" bgcolor='#ebebeb'>　<b>联系人/联系人电话:</b></td>
												<td width="12%"><b><input type="text" name="" value="<?php echo ($school['schoolname']["connectteacher"]); ?>"></b></td>
						<td width="12%" bgcolor='#ebebeb'>　<b>门市价:</b></td>
						<td width="12%"><b><input type="text" name="officialprice" value="<?php echo ($class["officialprice"]); ?>" id="officialprice" style="width:300px"></b></td>
					    <td width="12%" bgcolor='#ebebeb'>　<b>全包价:</b></td>
						<td width="12%"><b><input type="text" name="whole517price" value="<?php echo ($class["whole517price"]); ?>" id="whole517price"></b></td>
					</tr>
					<tr>
						<td colspan='8'>
						<table width="100%" >
							<tr>
								<td >　　　　　　　　　　<b  class="btn btn-primary radius" id="b2" style="height:25px;line-height:13px"><i class="Hui-iconfont">&#xe632;</i> 修改意向课程</b></td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
			</form>
		</div><br>
		<div style="width:100%;height:182px;">
		<form action="" id="fkfs">
		<table style="width:99.99%;height:180px" border='2'>
			<tr>
				<td colspan='6'>　<b>付款方式</b></td>
			</tr>
			<tr>
			<input type="hidden" name='listid' value="<?php echo ($listinfo["listid"]); ?>"/>
				<td width="10%" bgcolor='#ebebeb'>　<b>支付方式:</b></td>
				<td width="20%"><b><?php echo ($listinfo['payment_type']==1?'支付宝':''); ?></b></td>
				<td width="10%" bgcolor='#ebebeb'>　<b>支付状态:</b></td>
				<td width="20%"><input type="text"></td>
				<td width="10%" bgcolor='#ebebeb'>　<b>下单价格:</b></td>
				<td width="20%"><input type="text" name="whole517price" value="<?php echo ($class["whole517price"]); ?>"></td>
			</tr>
			<tr>
				<td width="10%" bgcolor='#ebebeb'>　<b>报名人数:</b></td>
				<td width="20%"><input type="text" name="stucount" value="<?php echo ($listinfo['stucount']); ?>"></td>
				<td width="10%" bgcolor='#ebebeb'>　<b>报名费:</b></td>
				<td width="20%"><input type="text"  value="<?php echo ($class["prepay517deposit"]); ?>"  id='prepay517deposit'></td>
				<td width="10%" bgcolor='#ebebeb'>　<b>报名费小计:</b></td>
				<td width="20%"><input type="text"  name="preferentialprice" value="<?php echo ($listinfo['preferentialprice']); ?>"></td>
			</tr>
			<tr>
				<td width="10%" bgcolor='#ebebeb'>　<b>团报优惠:</b></td>
				<td width="20%"><input type="text"></td>
				<td width="10%" bgcolor='#ebebeb'>　<b>其他优惠:</b></td>
				<td width="20%"><input type="text"></td>
				<td width="10%" bgcolor='#ebebeb'>　<b>优惠小计:</b></td>
				<td width="20%"><input type="text"></td>
			</tr>
			<tr>
				<td bgcolor='#ebebeb'>　<b>应收合计:</b></td>
				<td colspan=5><input type="text"></td>
			</tr>
			<tr>
				<td colspan=6>
					<table>
						<tr>
							<td align='center'><button id="b3">修改支付方式</button></td>
							<td align='center'><button>其他优惠</button></td>
							<td align='center'><button>确认收款</button></td>
						</tr>
					</table>
				</td>
			</tr>
		</table></form>
		</div><br />
		<div style="width:100%;height:122px;">
			<table style="width:99.99%;height:120px" border='2'>
				<tr>
					<td colspan=6>　<b>来源信息:</b></td>
				</tr>
				<tr>
					<td width="10%" bgcolor='#ebebeb'>　<b>来源:</b></td>
					<td width="20%"></td>
					<td width="10%" bgcolor='#ebebeb'>　<b>竞价关键字:</b></td>
					<td width="20%"></td>
					<td width="10%" bgcolor='#ebebeb'>　<b>搜索词:</b></td>
					<td width="20%"></td>
				</tr>
				<tr>
					<td width="10%" bgcolor='#ebebeb'>　<b>Referrer:</b></td>
					<td colspan=3  width="30%"></td>
					<td width="10%" bgcolor='#ebebeb'>　<b>下单站点:</b></td>
					<td></td>
				</tr>
			</table>
		</div><br />
		<div style="width:100%;height:auto;">
			<table style="width:99.99%;height:120px" border='2'>
				<tr>
					<td width='30%'>　<b>添加跟单记录:</b></td>
					<td>　<b>全部跟单记录:</b></td>
				</tr>
				<tr valign="top">
					<td>
					<form id="form5">
					<input type="hidden" name="listid" value="<?php echo ($listinfo["listid"]); ?>"/>
					<textarea name="content" id="" cols="90" rows="10" style="resize: none"></textarea>
						设置回访日期：<input type="text" onClick="WdatePicker()" id="returndate" name="nextreturndate" value="<?php echo ($date); ?>"/>&nbsp;&nbsp;
						<button id='bb'>添加</button>
					</form>
					</td>
					<td valign="top">
						<!-- 循环跟单记录 -->
						<table style="width:100%;" border="1" >
							<tr style="width:100%;height:30px;">
								<td width='10%' align='center'>跟单时间</td>
								<td width='50%' align='center'>详情</td>
								<td width='10%' align='center'>下次回访时间</td>
								<td width='10%' align='center'>操作人</td>
							</tr>
							<?php if(is_array($listdocument)): $i = 0; $__LIST__ = $listdocument;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr style="width:100%;height:20px;">
									<td><?php echo ($v["documenttime"]); ?></td>
									<td><?php echo ($v["content"]); ?></td>
									<td><?php echo ($v["nextreturndate"]); ?></td>
									<td><?php echo ($v["operator"]); ?></td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>

						</table>
					</td>
				</tr>
			</table>
		</div><br />
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<a href="<?php echo ($url); ?>"><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回列表</button></a>
			</div>
		</div>
</div>
</body>
</html>