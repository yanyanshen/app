<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Shortcut Icon" href="/Public/517/favicon.ico" />
<link href="/Public/517/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/Hui-iconfont/1.0.6/iconfont.css" rel="stylesheet" type="text/css" />
<script  src="/Public/517/js/form1.js"></script>
<script  src="/Public/517/js/My97DatePicker/WdatePicker.js"></script>
<title>订单列表</title>
<script>
	function aa(){
		$t=$('i1').value;
		if($t==''){
			$('b1').disabled=true;
		}else{
			$('b1').disabled=false;
		}
	}
	function $(id){
		return document.getElementById(id);
	}
</script>
</head>
<body onload="aa()" >
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 订单列表 <span class="c-gray en">&gt;</span> 订单列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20" style="height:70px;margin-top:0">
		<div class="left1">
		<form action="<?php echo U('?type=1');?>" method="post">
			<input type="text" placeholder="用户姓名/联系方式/订单号" id="i1" onkeyup="aa()" style="width:150px;border:1px solid gray;" name="query"/>
			<input type="submit" id='b1' value='快速查询' />
		</form>
			　<a href="<?php echo U('addlist');?>"><button>新建订单</button></a>
		</div>
		<form action="<?php echo U('?type=2');?>" method="post">
		<div class="right1">
		     条件查询 &nbsp;&nbsp;　　<!-- --省:<select name="province" id="province">
						</select>
				    市:<select name="city" id="city">
					 </select>
						<script>
		      			setup()
		  				</script> -->
		订单类型:<select name="mode" id="">
			<option value="">请选择</option>
			<option value=1>驾校</option>
			<option value=2>教练</option>
			<option value=3>指导员</option>
			<option value=4>预约</option>
		</select>&nbsp;
		支付状态:<select name="state" id="">
			<option value="">请选择</option>
			<option value=1>待付款</option>
			<option value=2>待评价</option>
			<option value=3>已完成</option>
		</select>&nbsp;
		订单状态:<select name="paymethod" id="">
			<option value="">请选择</option>
		</select>&nbsp;
		驾照类型:<select name="jtype" id="">
			<option value="">请选择</option>
			<option value="C1">C1</option>
			<option value="C2">C2</option>
			<option value="C3">C3</option>
			<option value="C4">C4</option>
			<option value="A1">A1</option>
			<option value="A2">A2</option>
			<option value="A3">A3</option>
			<option value="B1">B1</option>
			<option value="B2">B2</option>
			<option value="D">D</option>
			<option value="E">E</option>
			<option value="F">F</option>
			<option value="M">M</option>
			<option value="N">N</option>
		</select>&nbsp;
		来源:<select name="listtype" id="">
			<option value="">请选择</option>
			<option value="请选择">在线订单</option>
			<option value="请选择">人工订单</option>
		</select>&nbsp;<br>　　　　　　　驾校简称：<input type="text" name="listname" class="t2"/>&nbsp;&nbsp;
		学车基地：<input type="text" name="trname" class="t2"/>&nbsp;&nbsp;
		学员姓名：<input type="text" name="masname" class="t2"/>&nbsp;&nbsp;<br>
		　　　　　　　回访时间：<input type="text" name="startreturndate" class="t1" onClick="WdatePicker()"/>&nbsp;&nbsp;
		至：<input type="text" name="endreturndate" class="t1" onClick="WdatePicker()"/>&nbsp;&nbsp;
		付款时间：<input type="text" name="startgmt_payment" class="t1" onClick="WdatePicker()"/>&nbsp;&nbsp;
		至：<input type="text" name="endgmt_payment" class="t1" onClick="WdatePicker()"/>&nbsp;&nbsp;
		<input type="submit" value="立即筛选"/>
		</div></form>
		<a href="<?php echo U('?type=3');?>">查看已退费订单</a>
	</div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="19">订单列表<span class="r">共有数据：<strong><?php echo ($count); ?></strong> 条</span> </th>
			</tr>
			<tr class="text-c">
				<th width="40">编号</th>
				<th width="150">订单号</th>
				<th width="80">下单时间</th>
				<th width="60">用户姓名</th>
				<th width="90">联系电话</th>
				<th width="20">人数</th>
				<th width="100">驾校/教练/指导员</th>
				<th width="130">课程</th>
				<th width="70">支付方式</th>
				<th width="70">支付状态</th>
				<th width="80">支付时间</th>
				<th width="70">订单状态</th>
				<th width="80">跟单客服</th>
				<th width="60">最新备注</th>
				<th width="80">回访日期</th>
				<th width="60">最后更新人</th>
				<th width="50">状态</th>
				<th width="30">处理</th>
			</tr>
		</thead>
		<tbody><?php if(empty($list)): ?><td colspan="18" style="text-align:center">没有相关订单</td>
    		<?php else: ?>
		   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr class="text-c">
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo ($vv["listid"]); ?></td>
				<td><?php echo ($vv["listtime"]); ?></td>
				<td><?php echo ($vv["nickname"]); ?></td>
				<td><?php echo ($vv["phone"]); ?></td>
				<td><?php echo ($vv["stucount"]); ?></td>
				<td><?php echo ($vv["typename"]); ?>:<?php echo ($vv["listname"]); ?></td>
				<td><?php echo ($vv["name"]); ?></td>
				<td><?php echo ($vv["paymethod"]); ?></td>
				<td><?php echo ($vv['state']==0?'待付款':($vv['state']==1?'待确认':($vv['state']==2?'待评价':($vv['state']==3?'已完成':'已取消')))); ?></td>
				<td><?php echo ($vv["gmt_payment"]); ?></td>
				<td><?php echo ($vv["liststate"]); ?></td>
				<td><?php echo ($vv["username"]); ?></td>
				<td><?php echo ($vv["note"]); ?></td>
				<td><?php echo ($vv["returndate"]); ?></td>
				<td><?php echo ($vv["lastupdate"]); ?></td>
				<td><?php echo ($vv['CL_type']=='y'?'已处理':'未处理'); ?></td>
				<td><a href="<?php echo U('listInfo?listid='.$vv['listid'].'&userid='.$vv['masterid'].'&p='.$p);?>">处理</a></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<tr>
				<td colspan="19">
				<?php echo ($page); ?>
				</td>
			</tr><?php endif; ?>
		</tbody>
	</table>
</div>

</body>
</html>