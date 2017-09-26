<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>添加驾校</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：驾校管理-》添加驾校</span>
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
                    <td><input type="text" name="account" /><span style="color:red;"><?php echo ((isset($errorInfo["account"]) && ($errorInfo["account"] !== ""))?($errorInfo["account"]):""); ?></span></td>
                </tr>
		 <tr>
                    <td>登录密码</td>
                    <td><input type="text" name="pass" value="244ac348537069c3bfe9d633349b7334" style="width:500px" /><span style="color:red;"><?php echo ((isset($errorInfo["pass"]) && ($errorInfo["pass"] !== ""))?($errorInfo["pass"]):""); ?>(默认为517xueche)</span></td>
                </tr>

                <tr>
                    <td>驾校简称</td>
                    <td><input type="text" name="nickname" /><span style="color:red;"><?php echo ((isset($errorInfo["nickname"]) && ($errorInfo["nickname"] !== ""))?($errorInfo["nickname"]):""); ?></span></td>
                </tr>
                <tr>
                    <td>驾校全称</td>
                    <td><input type="text" name="fullname" style="width:400px"/><span style="color:red;"><?php echo ((isset($errorInfo["fullname"]) && ($errorInfo["fullname"] !== ""))?($errorInfo["fullname"]):""); ?></span></td>
                </tr> 
                <tr>
                    <td>优先级</td>
                    <td><input type="text" name="priority" />(数字，优先级越大排名越靠前)</td>
                </tr>
                 <tr>
                    <td>驾校logo</td>
                    <td><input type="text" name="logo" style="width:500px"/></td>
                </tr>
                <tr>
                    <td>计时培训</td>
                    <td><label><input type="radio"  name='timeflag' value=1 checked/>支持</label>　<label><input type="radio" name='timeflag' value=0/>不支持</label></td>
                </tr>
                 <tr>
                    <td>签名</td>
                    <td><input type="text" name="signature"/></td>
                </tr>
                <tr>
                    <td>评分</td>
                    <td><input type="text" name="score"/><span style="color:red;"><?php echo ((isset($errorInfo["score"]) && ($errorInfo["score"] !== ""))?($errorInfo["score"]):""); ?></span></td>
                </tr>
                 <tr>
                    <td>火热报名</td>
                    <td><label><input type="radio" name='hotflag' value=1 checked/>是</label><label><input type="radio" name='hotflag' value=0 />否</label></td>
                </tr>
                 <tr>
                    <td>是否推荐</td>
                   <td><label><input type="radio" name='recommendflag' value=1 checked/>是</label><label><input type="radio" name='recommendflag' value=0 />否</label></td>
                </tr>
                 <tr>
                    <td>评论数</td>
                    <td><input type="text" name="evalutioncount"  /><span style="color:red;"><?php echo ((isset($errorInfo["evalutioncount"]) && ($errorInfo["evalutioncount"] !== ""))?($errorInfo["evalutioncount"]):""); ?></span></td>
                </tr>
                 <tr>
                    <td>好评数</td>
                    <td><input type="text" name="praisecount" /><span style="color:red;"><?php echo ((isset($errorInfo["praisecount"]) && ($errorInfo["praisecount"] !== ""))?($errorInfo["praisecount"]):""); ?></span></td>
                </tr>
                 <tr>
                    <td>总学员数</td>
                    <td><input type="text" name="allcount"  /><span style="color:red;"><?php echo ((isset($errorInfo["allcount"]) && ($errorInfo["allcount"] !== ""))?($errorInfo["allcount"]):""); ?></span></td>
                </tr>
                 <tr>
                    <td>已通过人数</td>
                    <td><input type="text" name="passedcount" /><span style="color:red;"><?php echo ((isset($errorInfo["passedcount"]) && ($errorInfo["passedcount"] !== ""))?($errorInfo["passedcount"]):""); ?></span></td>
                </tr>
                <tr>
                    <td>联系人/联系方式</td>
                    <td><input type="text" name="connectteacher" />（姓名 空格 手机号）</td>
                </tr>
                <tr>
                    <td>地址</td>
                    <td><input type="text" name="address" /></td>
                </tr>
                 <tr>
                    <td>所在城市</td>
                    <td>
                    	<select name="cityid" id="">
                    		<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    	</select>
                    </td>
                </tr>
                 <tr>
                    <td>驾校简介</td>
                    <td><textarea name="introduction" id="" cols="80" rows="10"></textarea></td>
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