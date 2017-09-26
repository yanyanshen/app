<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>极光推送</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/xueche/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
        <script src="/xueche/Public/ment/js/jquery.js"></script>
        <script>
        $("document").ready(function(){
        	$("form").submit(function(){
        		val=$("#type option:selected").val();
        		xval=$("#xtype option:selected").val();
        		if(val==0){
        			alert('请选择推送类型');
        			return false;
        		}
        		if(!$("#xtype").prop('disabled') && xval==0){
        			alert('请重新选择用户类型');
        			return false;
        		}
        		if(!$("#phone").prop('disabled') && $("#phone").val()==''){
        			alert('请输入手机号码');
        			return false;
        		}
        	});
        	$("#type").change(function(){
        		val=$("#type option:selected").val();
        		if(val==4){
        			$('#xtype').attr("disabled",false);
        		}else{
        			$('#xtype').attr("disabled",true);
        		}
        	});
        	$("#xtype").change(function(){
        		val=$("#xtype option:selected").val();
        		if(val==1 || val==2 ||val==4 || val==6){
        			$('#phone').attr("disabled",false);
        		}else{
        			$('#phone').attr("disabled",true);
        		}
        	});
        });
        </script>
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》极光推送</span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
            <form action="<?php echo U('#');?>" method="post" enctype="multipart/form-data">
            <table border="1" width="100%" class="table_a">
                <tr>
                    <td width="5%">推送类型</td>
                    <td><select name="type" id="type">
                    	<option value=0>请选择</option>
                    	<option value=1>所有人</option>
                    	<option value=2>学员端</option>
                    	<option value=3>教练端</option>
                    	<option value=4>其他</option>
                    </select></td>
                </tr>
                 <tr>
                    <td width="5%">用户类型</td>
                    <td><select name="xtype" id="xtype" disabled >
                    	<option value=0>请选择</option>
                    	<option value='1'>学员</option>
                    	<option value='2'>驾校(个体)</option>
                    	<option value='3'>所有驾校</option>
                    	<option value='4'>教练(个体)</option>
                    	<option value='5'>所有教练</option>
                    	<option value='6'>指导员(个体)</option>
                    	<option value='7'>所有指导员</option>
                    </select></td>
                </tr>
                 <tr>
                	<td>联系方式</td>
                	<td><input type="text" name="phone" disabled id='phone'/></td>
                </tr>
                <tr>
                	<td>推送内容</td>
                	<td><textarea cols="30" rows="5" name="message"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="推送">
                    </td>
                </tr>  
            </table>
            </form>
        </div>
    </body>
</html>