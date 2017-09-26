<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>驾校详情</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
        <script  src="/Public/ment/js/My97DatePicker/WdatePicker.js"></script>
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：驾校管理-》驾校详情</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('jx_list?p='.$p);?>">【返回】</a>
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
                    <td width="7%">驾校账号</td>
                    <td><input type="text" name="account" value="<?php echo ($jx["account"]); ?>" /></td>
                </tr>
		<tr>
                    <td>登录密码</td>
                    <td><input type="text" name="pass" value="<?php echo ($jx["pass"]); ?>" readonly style="width:500px;"/></td>
                </tr>
                <tr>
                    <td>驾校简称</td>
                    <td><input type="text" name="nickname" value="<?php echo ($jx["nickname"]); ?>" /></td>
                </tr>
                <tr>
                    <td>驾校全称</td>
                    <td><input type="text" name="fullname" value="<?php echo ($jx["fullname"]); ?>" style="width:400px"/></td>
                </tr>
                 <tr>
                    <td>优先级</td>
                    <td><input type="text" name="priority" value="<?php echo ($jx["priority"]); ?>"/>(数字，优先级越大排名越靠前)</td>
                </tr>
                 <tr>
                    <td>驾校logo</td>
                    <td><input type="text" name="logo" value="<?php echo ($jx["logo"]); ?>" style="width:500px"/></td>
                </tr>
                <tr>
                    <td>计时培训</td>
                    <td><label><input type="radio" <?php echo ($jx['timeflag']==1?'checked':''); ?> name='timeflag' value=1/>支持</label>　<label><input type="radio"  <?php echo ($jx['timeflag']==0?'checked':''); ?> name='timeflag' value=0/>不支持</label></td>
                </tr>
                <tr>
                    <td>注册时间</td>
                    <td><input type="text" name="ntime" value="<?php echo ($jx['ntime']); ?>" readonly/></td>
                </tr>
                 <tr>
                    <td>签名</td>
                    <td><input type="text" name="signature" value="<?php echo ($jx['signature']); ?>" /></td>
                </tr>
                <tr>
                    <td>评分</td>
                    <td><input type="text" name="score" value="<?php echo ($jx['score']); ?>" /></td>
                </tr>
                 <tr>
                    <td>火热报名</td>
                    <td><label><input type="radio" name='hotflag' value=1 <?php echo ($jx['hotflag']==1?'checked':''); ?>/>是</label><label><input type="radio" name='hotflag' value=0 <?php echo ($jx['hotflag']==0?'checked':''); ?>/>否</label></td>
                </tr>
                 <tr>
                    <td>是否推荐</td>
                   <td><label><input type="radio" name='recommendflag' value=1 <?php echo ($jx['recommendflag']==1?'checked':''); ?>/>是</label><label><input type="radio" name='recommendflag' value=0 <?php echo ($jx['recommendflag']==0?'checked':''); ?>/>否</label></td>
                </tr>
                 <tr>
                    <td>评论数</td>
                    <td><input type="text" name="evalutioncount" value="<?php echo ($jx['evalutioncount']); ?>" /></td>
                </tr>
                 <tr>
                    <td>好评数</td>
                    <td><input type="text" name="praisecount" value="<?php echo ($jx['praisecount']); ?>" /></td>
                </tr>
                 <tr>
                    <td>总学员数</td>
                    <td><input type="text" name="allcount" value="<?php echo ($jx['allcount']); ?>" /></td>
                </tr>
                 <tr>
                    <td>已通过人数</td>
                    <td><input type="text" name="passedcount" value="<?php echo ($jx['passedcount']); ?>" /></td>
                </tr>
                <tr>
                    <td>联系人/联系方式</td>
                    <td><input type="text" name="connectteacher" value="<?php echo ($jx['connectteacher']); ?>"/>（姓名 空格 手机号）</td>
                </tr>
                <tr>
                    <td>地址</td>
                    <td><input type="text" name="address" value="<?php echo ($jx['address']); ?>"/></td>
                </tr>
                 <tr>
                    <td>所在城市</td>
                    <td>
                    	<select name="cityid" id="">
                    		<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php echo ($jx['cityid']==$v['id']?'selected':''); ?>><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    	</select>
                    </td>
                </tr>
                 <tr>
                    <td>驾校简介</td>
                    <td><textarea name="introduction" id="" cols="80" rows="10"><?php echo ($jx['introduction']); ?></textarea></td>
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