<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>订单详情</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
       	 <script  src="/Public/ment/js/My97DatePicker/WdatePicker.js"></script>
          <link rel="stylesheet" href="http://www.jq22.com/jquery/bootstrap-3.3.4.css">
		  <link rel="stylesheet" href="/Public/ment/bootstrap/css/bootstrap-select.min.css">
		  <script src="/Public/ment/js/jquery.js"></script>
		  <script src="/Public/ment/js/bootstrap.min.js"></script>
		  <script src="/Public/ment/js/bootstrap-select.min.js"></script>
        <script>
        	$("document").ready(function(){
        		$(":text").css('width',"97%").css("border",0);
        		$("#input1").removeAttr("style");
        		$("#input2").removeAttr("style");
        		$("#input5").removeAttr("style");
        		$("#jx").change(function(){
        			$.post(
        				"<?php echo U('returnclass');?>",
        				{
        					masterid:$("#jx option:selected").val(),
        				},function(data,status){
        					data1=eval("("+data+")");
        					//循环前先清空
        					$("#class").html("");
        					$("#class").append("<option value=''>请选择</option>");//在后面追加
        					for(i=0;i<data1.length;i++){
        						$("#class").append("<option value="+data1[i].tcid+">"+data1[i].name+"</option>");//在后面追加
        					}
        			});
        		});
        		$("#jl2").change(function(){
        			$.post(
        				"<?php echo U('returnclass');?>",
        				{
        					masterid:$("#jl2 option:selected").val(),
        				},function(data,status){
        					data1=eval("("+data+")");
        					//循环前先清空
        					$("#class").html("");
        					$("#class").append("<option value=''>请选择</option>");//在后面追加
        					for(i=0;i<data1.length;i++){
        						$("#class").append("<option value="+data1[i].tcid+">"+data1[i].name+"</option>");//在后面追加
        					}
        			});
        		});
        		$("#jl1").change(function(){
        			$.post(
        				"<?php echo U('returnclass');?>",
        				{
        					masterid:$("#jl1 option:selected").val(),
        				},function(data,status){
        					data1=eval("("+data+")");
        					//循环前先清空
        					$("#class").html("");
        					$("#class").append("<option value=''>请选择</option>");//在后面追加
        					for(i=0;i<data1.length;i++){
        						$("#class").append("<option value="+data1[i].tcid+">"+data1[i].name+"</option>");//在后面追加
        					}
        			});
        		});
        		$("#zdy").change(function(){
        			$.post(
        				"<?php echo U('returnclass');?>",
        				{
        					masterid:$("#zdy option:selected").val(),
        				},function(data,status){
        					data1=eval("("+data+")");
        					//循环前先清空
        					$("#class").html("");
        					$("#class").append("<option value=''>请选择</option>");//在后面追加
        					for(i=0;i<data1.length;i++){
        						$("#class").append("<option value="+data1[i].tcid+">"+data1[i].name+"</option>");//在后面追加
        					}
        			});
        		});
        		$("#class").change(function(){
        			$.post(
        				"<?php echo U('returnprices');?>",
        				{
        					tcid:$("#class option:selected").val(),
        				},function(data,status){
        					data1=eval("("+data+")");
        					//alert(data);
        					//循环前先清空
        					$("#off").val('');
        					$("#who").val('');
        					$("#off").val(data1['officialprice']);
        					$("#who").val(data1['whole517price']);
        			});
        		});
        		 $("input:text").not("[readonly]").css("background",'#F0F0F0');
        	});
 		</script>
    </head>
    <body>
        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：订单中心-》订单详情</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                 <a href="<?php echo U('order_list?p='.$p.'&id='.$id.'&t=ok');?>" style="text-decoration: none">【确认处理】</a>
					<a href="<?php echo U('cencel_list?id='.$id.'&p='.$p);?>" style="text-decoration: none">【取消订单】</a>
                    <a style="text-decoration: none" href="<?php echo U('order_list?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
            <form action="<?php echo U('#');?>" method="post">
            <table border="1" width="100%" class="table_a">
                <tr>
                	<td colspan='8'>订单状况</td>
                </tr>
                <tr><input type="hidden" name="id" value="<?php echo ($id); ?>"/>
                	<input type="hidden" name="p" value="<?php echo ($p); ?>"/>
                	<td width="6%">订单号:</td>
                	<td><input type="text" name="listid" value="<?php echo ($list["listid"]); ?>" readonly/></td>
                	<td width="6%">订单类型:</td>
                	<td>
                		<select name="mode" id="">
                			<option value=1 <?php echo ($list['mode']==1?'selected':''); ?>>驾校订单</option>
                			<option value=2 <?php echo ($list['mode']==2?'selected':''); ?>>教练订单</option>
                			<option value=3 <?php echo ($list['mode']==3?'selected':''); ?>>指导员订单</option>
                			<option value=4 <?php echo ($list['mode']==4?'selected':''); ?>>预约订单</option>
                		</select>
                	</td>
                	<td width="6%">订单状态:</td>
                	<td>
                		<select name="state" id="">
                			<option value=0 <?php echo ($list['state']==0?'selected':''); ?>>待支付</option>
                			<option value=1 <?php echo ($list['state']==1?'selected':''); ?>>待评价</option>
                			<option value=2 <?php echo ($list['state']==2?'selected':''); ?>>待确认</option>
                			<option value=3 <?php echo ($list['state']==3?'selected':''); ?>>已完成</option>
                			<option value=4 <?php echo ($list['state']==4?'selected':''); ?>>已取消</option>
                		</select>
                	</td>
                	<td width="6%">下次回访:</td>
                	<td><input type="text" name="returndate" value="<?php echo ($list["returndate"]); ?>"/></td>
                </tr>
                <tr>
                	<td width="6%">下单时间:</td>
                	<td><input type="text" name="listtime" value="<?php echo ($list["listtime"]); ?>" readonly/></td>
                	<td width="6%">支付方式:</td>
                	<td><input type="text" value="<?php echo ($list['payment_type']==1?'支付宝':($list['payment_type']==2?'微信':($list['payment_type']==3?'银联':'未支付'))); ?>"/></td>
                	<td width="6%">支付状态:</td>
                	<td><input type="text"  value="<?php echo ($list['state']==0?'待支付':'已支付'); ?>"/></td>
                	<td width="6%">跟单客服:</td>
                	<td>
                		<select name="customer" id="">
    						<?php if(is_array($customers)): $i = 0; $__LIST__ = $customers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php echo ($v[id]==$list['customer']?"selected":""); ?>><?php echo ($v["username"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>	
                		</select>
                	</td>
                </tr>
                <tr>
                	<td colspan='8'><input type="submit" value='保存更新' />　
                	            
                	</td>
                </tr>
            </table>
            </form><br>
 <!-- ------------------------------------form2------------------------------------------------ -->
            <form action="<?php echo U('#');?>" method="post">
            	  <table border="2" width="100%" class="table_a">
            	   		<tr><input type="hidden" name="id" value="<?php echo ($id); ?>"/>
                	<input type="hidden" name="p" value="<?php echo ($p); ?>"/>
                			<td colspan='8'>学员信息</td>
                		</tr>
            	  		<tr>
            	  			<td width="6%">客户姓名:</td>
            	  			<td><input type="text" value="<?php echo ($user["nickname"]); ?>" readonly/></td>
            	  			<td width="6%">客户电话:</td>
            	  			<td><input type="text" value="<?php echo ($user["phone"]); ?>" readonly/></td>
            	  			<td width="6%">学员姓名:</td>
            	  			<td><input type="text" value="<?php echo ($list["masname"]); ?>" name="masname"/></td>
            	  			<td width="6%">学员电话:</td>
            	  			<td><input type="text" value="<?php echo ($list["phone"]); ?>" name="phone" /></td>
            	  		</tr>
            	  		<tr>
            	  			<td width="6%">所在位置:</td>
            	  			<td colspan="3"><input type="text" value="<?php echo ($list["address"]); ?>" name="address"/></td>
            	  			<td width="6%">备注信息:</td>
            	  			<td colspan="3"><input type="text" value="<?php echo ($list["note"]); ?>" name="note"/></td>
            	  		</tr>
            	  		<tr>
                			<td colspan='8'><input type="submit" value='保存更新' /></td>
               			</tr>
            	  </table>
            </form><br>
<!-- ------------------------------------form3------------------------------------------------ -->
			<form action="<?php echo U('classs');?>" method="post">
				<table border="1" width="100%" class="table_a">
					<tr>
					<input type="hidden" name="id" value="<?php echo ($id); ?>"/>
                	<input type="hidden" name="p" value="<?php echo ($p); ?>"/>
                	<input type="hidden" name="type" value="<?php echo ($type); ?>"/>
						<td colspan='14' >意向课程</td>
					</tr>
					<tr>
						<td width="6%">已报驾校:</td>
						<td>
							<select id ='jx' name="jx" class="selectpicker  form-control" data-live-search="true" style="width:300px">
								<option value="">请选择</option>
					       		<?php if(is_array($school)): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>" <?php echo ($schoolid==$v['userid']?'selected':''); ?>><?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					        </select>
						</td>
						<td width="6%">普通教练:</td>
						<td>
							<select name="jl0" class="selectpicker  form-control" data-live-search="true" style="width:300px">
							<option value="">请选择</option>
					       		<?php if(is_array($coach0)): $i = 0; $__LIST__ = $coach0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>" <?php echo ($list['objectid']==$v['userid']?'selected':''); ?>><?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					        </select>
						</td>
						<td width="6%">教练小老板:</td>
						<td>
							<select id='jl2' name="jl2" class="selectpicker  form-control" data-live-search="true" style="width:300px">
					       		<option value="">请选择</option>
					       		<?php if(is_array($coach2)): $i = 0; $__LIST__ = $coach2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>" <?php echo ($list['objectid']==$v['userid']?'selected':''); ?>><?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					        </select>
						</td>
						<td width="6%">打工教练:</td>
						<td>
							<select name="jl3" class="selectpicker  form-control" data-live-search="true" style="width:300px">
					       		<option value="">请选择</option>
					       		<?php if(is_array($coach3)): $i = 0; $__LIST__ = $coach3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>" <?php echo ($list['objectid']==$v['userid']?'selected':''); ?>><?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					        </select>
						</td>
						<td width="6%">私人教练:</td>
						<td>
							<select id='jl1' name="jl1" class="selectpicker  form-control" data-live-search="true" style="width:300px">
					       		<option value="">请选择</option>
					       		<?php if(is_array($coach1)): $i = 0; $__LIST__ = $coach1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>" <?php echo ($list['objectid']==$v['userid']?'selected':''); ?>><?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					        </select>
						</td>
						<td width="6%">指导员:</td>
						<td>
							<select id='zdy' name="zdy" class="selectpicker  form-control" data-live-search="true" style="width:300px">
					       		<option value="">请选择</option>
					       		<?php if(is_array($guider)): $i = 0; $__LIST__ = $guider;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>" <?php echo ($list['objectid']==$v['userid']?'selected':''); ?>><?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					        </select>
						</td>
					</tr>
					<tr>
					    <td width="6%">已报课程:</td>
						<td>
							<!--  <input type="text" name="description" value="<?php echo ($list["description"]); ?>"/>-->
							<select name="tcid" id="class">
								
								<?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["tcid"]); ?>" <?php echo ($list['description']==$v['name']?'selected':''); ?>><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</td>
						<td width="6%">已选基地:</td>
						<td>
							<input type="text" name="trname"/>
						</td>
						<td width="6%">负责人/电话:</td>
						<td><input type="text" /></td>
						<td width="6%">联系人/电话:</td>
						<td><input type="text"/></td>
						<td width="6%">门市价:</td>
						<td><input type="text" value="<?php echo ($price["officialprice"]); ?>" id='off' /></td>
						<td width="6%">全包价:</td>
						<td><input type="text" value="<?php echo ($price["whole517price"]); ?>" id='who'/></td>
					</tr>
					<tr>
                			<td colspan='14'><input type="submit" value='保存更新' /></td>
               		</tr>
				</table>
			</form><br />
<!-- ------------------------------------form4------------------------------------------------ -->
			<form action="<?php echo U('zhifu');?>" method="post">
			<input type="hidden" name="id" value="<?php echo ($id); ?>"/>
                	<input type="hidden" name="p" value="<?php echo ($p); ?>"/>
				<table border="1" width="100%" class="table_a">
					<tr>
						<td colspan='10' >付款信息</td>
					</tr>
				<!--	<tr>
						<td width="6%">支付方式:</td>
						<td><input type="text" value="<?php echo ($list['payment_type']==1?'支付宝':($list['payment_type']==2?'微信':($list['payment_type']==3?'银联':'未支付'))); ?>" readonly/></td>
						<td width="6%">支付状态:</td>
						<td><input type="text"  value="<?php echo ($list['state']==0?'待支付':'已支付'); ?>" readonly/></td>
						<td width="6%">下单价格:</td>
						<td><input type="text" value="<?php echo ($list['prices']); ?>" readonly/></td>
						<td width="6%">报名人数:</td>
						<td><input type="text" value="<?php echo ($list['stucount']); ?>" name="stucount" readonly/></td>
						<td width="6%">预付报名费</td>
						<td><input type="text" value="<?php echo ($list['preferentialprice']); ?>" name="preferentialprice" readonly/></td>
					</tr>
					<tr>
						<td width="6%">报名费小计</td>
						<td><input type="text" value="<?php echo ($list['preferentialprice']*$list['stucount']); ?>" readonly/></td>
						<td width="6%">团报优惠:</td>
						<td><input type="text" readonly/></td>
						<td width="6%">其它优惠:</td>
						<td><input type="text" readonly/></td>
						<td width="6%">优惠小计:</td>
						<td><input type="text" readonly/></td>
						<td width="6%">应收合计:</td>
						<td><input type="text" value="<?php echo ($list['prices']*$list['stucount']-$list['preferentialprice']*$list['stucount']); ?>" readonly/></td>
					</tr>-->
					<tr>
						<td width="6%">支付方式:</td>
						<td><input type="text" value="<?php echo ($list['payment_type']==1?'支付宝':($list['payment_type']==2?'微信':($list['payment_type']==3?'银联':'未支付'))); ?>" readonly/></td>
						<td width="6%">支付状态:</td>
						<td><input type="text"  value="<?php echo ($list['state']==0?'待支付':'已支付'); ?>" readonly/></td>
						<td width="6%">报名人数:</td>
						<td><input type="text" value="<?php echo ($list['stucount']); ?>" name="stucount" readonly/></td>
						
					</tr>
					<tr>
						<td width="6%">下单价格:</td>
						<td><input type="text" value="<?php echo ($list['prices']); ?>" readonly/></td>
						<td width="6%">预付报名费</td>
						<td><input type="text" value="<?php echo ($list['preferentialprice']); ?>" name="preferentialprice" readonly/></td>
						<td width="6%">实际支付金额:</td>
						<td><input type="text" value="<?php echo ($list['total_fee']); ?>" readonly /></td>
					</tr>
					<!--<tr>
                			<td colspan='10'><input type="submit" value='保存更新' /></td>
               		</tr>-->
				</table>
			</form><br />
			<!-- ------------------------------------form5------------------------------------------------ -->
        	<form action="<?php echo U('#');?>" method="post">
				<table border="1" width="100%" class="table_a">
					<tr>
						<td colspan=10>来源信息</td>
					</tr>
					<tr>
						<td width="6%">来源:</td>
						<td><input type="text"/></td>
						<td width="6%">竞价关键字:</td>
						<td><input type="text"/></td>
						<td width="6%">搜索词:</td>
						<td><input type="text"/></td>
						<td width="6%">referee:</td>
						<td><input type="text"/></td>
						<td width="6%">下单站点:</td>
						<td><input type="text"/></td>
					</tr>
				</table>
			</form><br>
		<!-- ------------------------------------form6------------------------------------------------ -->
			<form action="<?php echo U('returndate');?>" method="post">
					<input type="hidden" name="id" value="<?php echo ($id); ?>"/>
					<input type="hidden" name="listid" value="<?php echo ($list["listid"]); ?>"/>
                	<input type="hidden" name="p" value="<?php echo ($p); ?>"/>
				<div class="left1">
					<table border="1" width="80%" class="table_a">
						<tr>
							<td>
								添加回访记录
							</td>
						</tr>
						<tr>
							<td>
								<textarea name="content" id="" cols="60%" rows="7"></textarea>
							</td>
						</tr>
						<tr>
							<td>
								设置回访日期: <input type="text" id='input1' onClick="WdatePicker()" name="nextreturndate"/>　<input type="submit" value="添加"/>
							</td>
						</tr>
					</table>
				</div>
			</form>
				<div class="right1">
					<table border="1" width="100%" class="table_a">
						<tr>
							<td colspan=5>全部跟单记录</td>
						</tr>
						<tr>
							<td width="4%">序号</td>
							<td width="10%">跟单时间</td>
							<td width="26%">详情</td>
							<td width="10%">下次回访日期</td>
							<td width="5%">回访人</td>
						</tr>
						<?php if(is_array($jilu)): $i = 0; $__LIST__ = $jilu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr> 
								<td><?php echo ($v["id"]); ?></td>
								<td><?php echo ($v["documenttime"]); ?></td>
								<td><?php echo ($v["content"]); ?></td>
								<td><?php echo ($v["nextreturndate"]); ?></td>
								<td><?php echo ($v["operator"]); ?></td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</table>
				</div>
			<!-- --------------------------------驾校------------------------------------- -->
			<datalist id="school_list" name="school_list"></datalist>
       </div>
             <div style="margin:0 auto;width:100%;clear:both;text-align:center">
  
             </div>
    </body>
</html>