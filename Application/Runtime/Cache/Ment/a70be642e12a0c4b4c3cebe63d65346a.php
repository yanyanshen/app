<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>添加订单</title>
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
			  $("#jx").change(function(){
					//if($("#jx option:selected").val()!=0){
						jx=$("#jx option:selected").val();
						$("#listname").val($("#jx option:selected").text());
						$("#coach").val(jx);
						$("#coachname").val($("#jx option:selected").text());
						type=1;
						data={"userid":jx,"type":type};
						returntrain(data);
					//}else{
						//return false;
					//}
			  });
			  $("#jl").change(function(){
					//if($("#jl option:selected").val()!=0){
						jl=$("#jl option:selected").val();
						$("#listname").val($("#jl option:selected").text());
						$("#coach").val(jl);
						$("#coachname").val($("#jl option:selected").text());
						type=2;
						data={"userid":jl,"type":type};
						returntrain(data);
					//}else{
					//	return false;
					//}
			  });
			  $("#zdy").change(function(){
					//if($("#zdy option:selected").val()!=0){
						zdy=$("#zdy option:selected").val();
						$("#listname").val($("#zdy option:selected").text());
						$("#coach").val(zdy);
						$("#coachname").val($("#jx option:selected").text());
						type=3;
						data={"userid":zdy,"type":type};
						returntrain(data);
					//}else{
					//	return false;
					//}
			  });
			  function returntrain(data){
				  $.ajax({
					    type: "POST",
					    url: 'returntrain',
					    data:JSON.stringify(data),
					    dataType: "json",//指定返回数据的类型
					    success: function (message) {
					    	$('#train').html('');
					    	$('#train').append("<option>请选择</option>");
					    	$('#class').html('');
					    	$('#class').append("<option>请选择</option>");
								for(i=0;i<message['train'].length;i++){
									$('#train').append("<option value="+message['train'][i]['id']+">"+message['train'][i]['trname']+"</option>");
							    }
								for(i=0;i<message['trainclass'].length;i++){
									$('#class').append("<option value="+message['trainclass'][i]['tcid']+">"+message['trainclass'][i]['name']+"</option>");
								    }
					    },
					    error: function (message) {
					       alert(JSON.stringify(message));
					    }
					});
			  }
		  });
		  </script>
    </head>
    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：订单管理-》添加订单</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('jx_list?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>
        <div style="font-size: 13px;margin: 10px 5px">
            <form action="<?php echo U('#');?>" method="post">
            <input type="hidden" name="listname" id='listname'/>
            <input type="hidden" name="coach" id='coach'/>
             <input type="hidden" name="coachname" id='coachname'/>
            <table border="1" width="100%" class="table_a">
                <tr>
                    <td width="7%">学员姓名</td>
                    <td><input type="text" name="masname" /></td>
                </tr>
                 <tr>
                    <td width="7%">联系方式</td>
                    <td><input type="text" name="phone" /></td>
                </tr>
               <tr>
                    <td width="7%">学员地址</td>
                    <td><input type="text" name="address" /></td>
                </tr>
                 <tr>
                    <td width="7%">选择订单类型</td>
                    <td>
                    		<select name="mode" id="mode">
                    		    <option value=0 >选择订单类型</option>
                    			<option value=1 >驾校订单</option>
                    			<option value=2 >教练订单</option>
                    			<option value=3 >指导员订单</option>
                    		</select>
                    </td>
                </tr>
                 <tr>
                    <td width="7%">选择对象</td>
                    <td>
                    		<select name="objectid" id='jx' class="selectpicker show-tick" data-live-search="true" style="width:300px">
			            	 <?php if(is_array($school)): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>"><?php echo ($v["cityname"]); ?>　<?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				            </select>
                    		<select name="objectid" id='jl' class="selectpicker show-tick" data-live-search="true" style="width:300px">
			            	 <?php if(is_array($coach)): $i = 0; $__LIST__ = $coach;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>"><?php echo ($v["cityname"]); ?>　<?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				            </select>
                    		<select name="objectid"  id='zdy' class="selectpicker show-tick" data-live-search="true" style="width:300px">
			            	 <?php if(is_array($guider)): $i = 0; $__LIST__ = $guider;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>"><?php echo ($v["cityname"]); ?>　<?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				            </select>
                    </td>
                </tr>
                 <tr>
                    <td width="7%">选择基地</td>
                    <td>
                    		<select name="trainid" id="train">
                    			<option value=1 >请选择基地</option>
                    		</select>
                    </td>
                </tr>
                 <tr>
                    <td width="7%">选择课程</td>
                    <td>
                    		<select name="description" id="class">
                    			<option value=1 >请选择课程</option>
                    		</select>
                    </td>
                </tr>
                <tr>
                    <td width="7%">下单价格</td>
                    <td><input type="text" name="prices" /></td>
                </tr>
                <tr>
                    <td width="7%">实际支付</td>
                    <td><input type="text" name="total_fee" /></td>
                </tr>
                <tr>
                    <td width="7%">支付类型</td>
                    <td>
                    		<select name="payment_type">
                    			<option value=0 >未支付</option>
                    			<option value=1 >支付宝</option>
                    			<option value=2 >微信</option>
                    			<option value=3 >银联</option>
                    		</select>
                    </td>
                </tr>
                <tr>
                    <td width="7%">支付状态</td>
                    <td>
                    	    <select name="stste">
                    			<option value=0 >未支付</option>
                    			<option value=1 >已支付</option>
                    		</select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="添加">
                    </td>
                </tr>  
            </table>
            </form>
        </div>
    </body>
</html>