<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>订单列表</title>

        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet" />
        <script src="/Public/ment/js/jquery.js"></script>
	     <script>
		//每隔5秒钟查询一下后台订单列表
	     setInterval(function(){
 			$.post(
 					"<?php echo U('newlist');?>",
 					{
 					},function(data,status){
 						if(data>0){
 							$('#newlist').html('');
 							$('#newlist').html("有"+data+"条未处理订单");
 						}
 				});
 		},5000);
	     $("document").ready(function(){
	  		$("#b").click(function(){
				form=$("#form").serializeArray();
				url="?"
	 			for(i=0;i<form.length;i++){
					if(form[i].value!=''){
						url+=form[i].name+"="+form[i].value+"&";
					}
				}
				location.href =url;
	 		});
	     });
		</script>
    </head>
    <body>
        <style>
            .tr_color{background-color: #9F88FF}
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：订单管理-》订单列表</span>
		 <a href="<?php echo U('neworder_list');?>" style="color:red;margin-left:30%"><span id="newlist" ></span></a>
                <span style="float: right; margin-right: 8px; font-weight: bold;">
                    <a style="text-decoration: none;" href="<?php echo U('add_order');?>">【添加订单】</a>
                </span>
            </span>
        </div>
        <div></div>
        <div class="div_search">
            <span style="float:left">
                <form id='form'>
                    订单类型<select name="mode" style="width: 100px;">
                        <option selected="selected" value="">请选择</option>
                        <option value=1>驾校订单</option>
                        <option value=2>教练订单</option>
                        <option value=3>指导员订单</option>
                        <option value=4>预约订单</option>
                    </select>
                      订单状态<select name="state" style="width: 100px;">
                        <option selected="selected" value="">请选择</option>
                        <option value=0>待付款</option>
                        <option value=1>待评价</option>
                        <option value=2>待确认</option>
                        <option value=3>已完成</option>
                        <option value=4>已取消</option>
                    </select>
                    订单号：<input type="text" name='listid' /> 
                    手机号：<input type="text" name='phone' />
                    用户名:<input type="text" name='masname' />
                     驾校/教练/指导员(姓名):<input type="text" name='listname' />
                    <input value="查询" type="button" id='b'/>
                </form>
            </span>
            <span style="float:right">总计：<?php echo ($count); ?>　　</span>
        </div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <th width="2%">序号</th>
                        <th width="10%">订单号</th>
                         <th width="5%">订单类型</th>
                        <th width="9%">下单时间</th>
                        <th width="6%">用户姓名</th>
                        <th width="6%">当前姓名</th>
                        <th width="8%">驾校/教练/指导员</th>
                         <th width="17%">课程名</th>
                         <th width="4%">订单状态</th>
                         <th width="4%">支付方式</th>
                        <th width="5%">跟单客服</th>
                        <th width="5%">回访日期</th>
                        <th width="4%">状态</th>
                        <th width="5%">改变状态</th>
                        <th width="5%">最后更新人</th>
                        <th>处理</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($v["id"]); ?></td>
                        <td><a href="#"><?php echo ($v["listid"]); ?></a></td>
                         <td><?php echo ($v['mode']==1?'驾校订单':($v['mode']==2?'教练订单':($v['mode']==3?'指导员订单':'预约订单'))); ?></td>
                        <td><?php echo ($v["listtime"]); ?></td>
                        <td><?php echo ($v["masname"]); ?></td>
                        <td><?php echo ($v["nickname"]); ?></td>
                        <td><?php echo ($v["listname"]); ?></td>
                        <td><?php echo ($v["description"]); ?></td>
                        <td><?php echo ($v['state']); ?>-<?php echo ($v['state']==0?'待付款':($v['state']==1?'待确认':($v['state']==2?'待评价':($v['state']==3?'已完成':'已取消')))); ?></td>
                         <td><?php echo ($v['payment_type']); ?>-<?php echo ($v['payment_type']==1?'支付宝':($v['payment_type']==2?'微信':($v['payment_type']==3?'银联':'未支付'))); ?></td>
                        <td><?php echo ($v["customer"]); ?></td>
                         <td><?php echo ($v["returndate"]); ?></td>
                        <td><?php echo ($v['cl_type']=='y'?'<l style="color:green">已处理</l>':'<l style="color:red">未处理</l>'); ?></td>
                        <td><a href="<?php echo U('cl_list?cl='.$v['cl_type'].'&p='.$p.'&id='.$v['id'].'&u='.$v['masterid']);?>"><?php echo ($v['cl_type']=='y'?'<l style="color:red">改为未处理</l>':'<l style="color:green">改为已处理</l>'); ?></a></td>
                        <td><?php echo ($v["lastupdate"]); ?></td>
                        <td><a href="<?php echo U('list_info?id='.$v['id'].'&p='.$p);?>">处理</a></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>
                        <td colspan="20" style="text-align: center;" >
                            <?php echo ($page); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>