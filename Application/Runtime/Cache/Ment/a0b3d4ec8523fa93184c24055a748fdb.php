<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>教练详情</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
       	 <script  src="/Public/ment/js/My97DatePicker/WdatePicker.js"></script>
          <link rel="stylesheet" href="http://www.jq22.com/jquery/bootstrap-3.3.4.css">
		  <link rel="stylesheet" href="/Public/ment/bootstrap/css/bootstrap-select.min.css">
		  <script src="/Public/ment/js/jquery.js"></script>
		  <script src="/Public/ment/js/bootstrap.min.js"></script>
		  <script src="/Public/ment/js/bootstrap-select.min.js"></script>
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：教练管理-》教练详情</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('jl_list?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
            <form action="<?php echo U('#');?>" method="post" enctype="multipart/form-data">
            <table border="1" width="100%" class="table_a">
                <tr>
                <input type="hidden" name='id' value="<?php echo ($id); ?>"/>
                <input type="hidden" name='p' value="<?php echo ($p); ?>"/>
                    <td width="7%">教练账号</td>
                    <td><input type="text" name="account" value="<?php echo ($jl["account"]); ?>" /></td>
                </tr>
		<tr>
                    <td>登录密码</td>
                    <td><input type="text" name="pass" value="<?php echo ($jl["pass"]); ?>" readonly style="width:500px;"/></td>
                </tr>
                <tr>
                    <td>教练姓名</td>
                    <td><input type="text" name="nickname" value="<?php echo ($jl["nickname"]); ?>" /></td>
                </tr>
                <tr>
                    <td>性别</td>
                    <td><label><input type="radio" <?php echo ($jl['sex']==1?'checked':''); ?> name='sex' value=1 />男</label>　<label><input type="radio"  <?php echo ($jl['sex']==2?'checked':''); ?> name='sex' value=2 />女</label><label><input type="radio"  <?php echo ($jl['sex']==0?'checked':''); ?> name='sex' value=0 />保密</label></td>
                </tr>
                 <tr>
                    <td>身份证号</td>
                    <td><input type="text" name="numberId" value="<?php echo ($jl['numberid']); ?>" /></td>
                </tr>
                 <tr>
                    <td>驾驶证号</td>
                    <td><input type="text" name="driverId" value="<?php echo ($jl['driverid']); ?>" /></td>
                </tr>
                 <tr>
                    <td>教练证号</td>
                    <td><input type="text" name="serialId" value="<?php echo ($jl['serialid']); ?>" /></td>
                </tr>
                 <tr>
                    <td>签名</td>
                    <td><input type="text" name="signature" value="<?php echo ($jl['signature']); ?>" /></td>
                </tr>
                <tr>
                    <td>计时培训</td>
                    <td><label><input type="radio" <?php echo ($jl['timeflag']==1?'checked':''); ?> name='timeflag' value=1 />支持</label>　<label><input type="radio"  <?php echo ($jl['timeflag']==0?'checked':''); ?> name='timeflag' value=0 />不支持</label></td>
                </tr>
                 <tr>
                    <td>出生日期</td>
                    <td><input type="text" name="birthday" value="<?php echo ($jl['birthday']); ?>" onClick="WdatePicker()"/></td>
                </tr>
                <tr>
                    <td>注册时间</td>
                    <td><input type="text" name="ntime" value="<?php echo ($jl['ntime']); ?>" readonly/></td>
                </tr>
                 <tr>
                    <td>驾龄</td>
                    <td><input type="text" name="driverage" value="<?php echo ($jl['driverage']); ?>" onClick="WdatePicker()"/></td>
                </tr>
                  <tr>
                    <td>教龄</td>
                    <td><input type="text" name="teachedage" value="<?php echo ($jl['teachedage']); ?>" onClick="WdatePicker()"/></td>
                </tr>
                 
                 <tr>
                    <td>评论数</td>
                    <td><input type="text" name="evalutioncount" value="<?php echo ($jl['evalutioncount']); ?>" /></td>
                </tr>
                 <tr>
                    <td>好评数</td>
                    <td><input type="text" name="praisecount" value="<?php echo ($jl['praisecount']); ?>" /></td>
                </tr>
                 <tr>
                    <td>总学员数</td>
                    <td><input type="text" name="allcount" value="<?php echo ($jl['allcount']); ?>" /></td>
                </tr>
                 <tr>
                    <td>已通过人数</td>
                    <td><input type="text" name="passedcount" value="<?php echo ($jl['passedcount']); ?>" /></td>
                </tr>
                <tr>
                    <td>地址</td>
                    <td><input type="text" name="address" value="<?php echo ($jl['address']); ?>"/></td>
                </tr>
                <tr>
                    <td>教练类型</td>
                    <td>
                    	<select name="jltype" id="">
                    			<option value=0 <?php echo ($jl['jltype']==0?'selected':''); ?>>普通教练</option>
                    			<option value=1 <?php echo ($jl['jltype']==1?'selected':''); ?>>私人教练</option>
                    			<option value=2 <?php echo ($jl['jltype']==2?'selected':''); ?>>小老板</option>
                    			<option value=3 <?php echo ($jl['jltype']==3?'selected':''); ?>>打工教练</option>
                    	</select>
                    </td>
                </tr>
                 <tr>
                    <td>所属驾校</td>
                    <td>
                    	<select name="masterid" class="selectpicker show-tick form-control" data-live-search="true" style="width:300px">
			            	 <?php if(is_array($school)): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>" <?php echo ($v['userid']==$jl['masterid']?'selected':''); ?>><?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				        </select>
                    </td>
                </tr>
                <tr>
                    <td>所属教练</td>
                    <td>
                    	<select name="boss" class="selectpicker show-tick form-control" data-live-search="true" style="width:300px">
			            	 <?php if(is_array($coach)): $i = 0; $__LIST__ = $coach;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["userid"]); ?>" <?php echo ($v['userid']==$jl['boss']?'selected':''); ?>><?php echo ($v["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				        </select>
                    </td>
                </tr>
                 <tr>
                    <td>所在城市</td>
                    <td>
                    	<select name="cityid" id="">
                    		<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php echo ($jl['cityid']==$v['id']?'selected':''); ?>><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    	</select>
                    </td>
                </tr>
                 <tr>
                    <td>教练简介</td>
                    <td><textarea name="introduction" id="" cols="150" rows="4"><?php echo ($jl['introduction']); ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="保存更新">
                    </td>
                </tr>  
            </table>
            </form>
        </div>
    </body>
</html>