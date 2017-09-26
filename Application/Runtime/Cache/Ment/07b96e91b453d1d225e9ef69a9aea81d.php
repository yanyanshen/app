<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>指导员详情</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
        <script  src="/Public/ment/js/My97DatePicker/WdatePicker.js"></script>
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》指导员详情</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('zdy_list?p='.$p);?>">【返回】</a>
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
                    <td width="7%">指导员账号</td>
                    <td><input type="text" name="account" value="<?php echo ($zdy["account"]); ?>"/></td>
                </tr>
		<tr>
                    <td>登录密码</td>
                    <td><input type="text" name="pass" value="<?php echo ($zdy["pass"]); ?>" readonly style="width:500px;"/></td>
                </tr>
                <tr>
                    <td>姓名</td>
                    <td><input type="text" name="nickname" value="<?php echo ($zdy["nickname"]); ?>" /></td>
                </tr>
                 <tr>
                    <td>性别</td>
                    <td><label><input type="radio" <?php echo ($zdy['sex']==1?'checked':''); ?> name='sex' value=1/>男</label>　<label><input type="radio"  <?php echo ($zdy['sex']==2?'checked':''); ?> name='sex' value=2/>女</label><label><input type="radio"  <?php echo ($zdy['sex']==0?'checked':''); ?> name='sex' value=0/>保密</label></td>
                </tr>
                <tr>
                    <td>计时培训</td>
                    <td><label><input type="radio" <?php echo ($zdy['timeflag']==1?'checked':''); ?> name='timeflag' value=1/>支持</label>　<label><input type="radio"  <?php echo ($zdy['timeflag']==0?'checked':''); ?> name='timeflag' value=0/>不支持</label></td>
                </tr>
                <tr>
                    <td>出生日期</td>
                    <td><input type="text" name="birthday" value="<?php echo ($zdy['birthday']); ?>" onClick="WdatePicker()"/></td>
                </tr>
                <tr>
                    <td>注册时间</td>
                    <td><input type="text" name="ntime" value="<?php echo ($zdy['ntime']); ?>" readonly/></td>
                </tr>
                 <tr>
                    <td>签名</td>
                    <td><input type="text" name="signature" value="<?php echo ($zdy['signature']); ?>" /></td>
                </tr>
                <tr>
                    <td>评分</td>
                    <td><input type="text" name="score" value="<?php echo ($zdy['score']); ?>" /></td>
                </tr>
                 <tr>
                    <td>评论数</td>
                    <td><input type="text" name="evalutioncount" value="<?php echo ($zdy['evalutioncount']); ?>" /></td>
                </tr>
                 <tr>
                    <td>好评数</td>
                    <td><input type="text" name="praisecount" value="<?php echo ($zdy['praisecount']); ?>" /></td>
                </tr>
                 <tr>
                    <td>总学员数</td>
                    <td><input type="text" name="allcount" value="<?php echo ($zdy['allcount']); ?>" /></td>
                </tr>
                 <tr>
                    <td>已通过人数</td>
                    <td><input type="text" name="passedcount" value="<?php echo ($zdy['passedcount']); ?>" /></td>
                </tr>
                <tr>
                    <td>地址</td>
                    <td><input type="text" name="address" value="<?php echo ($zdy['address']); ?>"/></td>
                </tr>
                 <tr>
                    <td>所在城市</td>
                    <td>
                    	<select name="cityid" id="">
                    		<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php echo ($zdy['cityid']==$v['id']?'selected':''); ?>><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    	</select>
                    </td>
                </tr>
                 <tr>
                    <td>指导员简介</td>
                    <td><textarea name="introduction" id="" cols="80" rows="10"><?php echo ($zdy['introduction']); ?></textarea></td>
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