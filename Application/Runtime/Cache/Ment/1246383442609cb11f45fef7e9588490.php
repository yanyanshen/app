<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>学员详情</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
        <script  src="/Public/ment/js/My97DatePicker/WdatePicker.js"></script>
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：学员管理-》学员详情</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('stu_list?p='.$p);?>">【返回】</a>
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
                    <td width="7%">用户账号</td>
                    <td><input type="text" name="account" value="<?php echo ($stu["account"]); ?>" /></td>
                </tr>
                <tr>
                    <td>用户名称</td>
                    <td><input type="text" name="nickname" value="<?php echo ($stu["nickname"]); ?>" /></td>
                </tr>
                <tr>
                    <td>性别</td>
                    <td><label><input type="radio" <?php echo ($stu['sex']==1?'checked':''); ?> name='sex' value=1/>男</label>　<label><input type="radio"  <?php echo ($stu['sex']==2?'checked':''); ?> name='sex' value=2/>女</label>　<label><input type="radio"  <?php echo ($stu['sex']==0?'checked':''); ?> name='sex' value=0/>保密</label></td>
                </tr>
                <tr>
                    <td>出生日期</td>
                    <td><input type="text" name="birthday" value="<?php echo ($stu['birthday']); ?>" onClick="WdatePicker()"/></td>
                </tr>
                <tr>
                    <td>注册时间</td>
                    <td><input type="text" name="ntime" value="<?php echo ($stu['ntime']); ?>" readonly/></td>
                </tr>
                 <tr>
                    <td>签名</td>
                    <td><input type="text" name="signature" value="<?php echo ($stu['signature']); ?>" style="width:300px"/></td>
                </tr>
                <tr>
                    <td>驾照类型</td>
                    <td><input type="text" name="jtype" value="<?php echo ($stu['jtype']); ?>"/></td>
                </tr>
                <tr>
                    <td>当前科目</td>
                    <td><input type="text" name="subjects" value="<?php echo ($stu['subjects']); ?>"/></td>
                </tr>
                <tr>
                    <td>联系方式</td>
                    <td><input type="text" name="phone" value="<?php echo ($stu['phone']); ?>"/></td>
                </tr>
                <tr>
                    <td>地址</td>
                    <td><input type="text" name="address" value="<?php echo ($stu['address']); ?>"/></td>
                </tr>
                 <tr>
                    <td>所在城市</td>
                    <td>
                    	<select name="cityid" id="">
                    		<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php echo ($stu['cityid']==$v['id']?'selected':''); ?>><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    	</select>
                    </td>
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