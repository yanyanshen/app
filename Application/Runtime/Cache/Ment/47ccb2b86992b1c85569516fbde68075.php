<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>编辑课程</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>
    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》编辑课程</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('class_Manage?id='.$iid.'&t='.$t.'&p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>
        <form action="#" method="post">
        <div style="font-size: 13px;margin: 10px 5px">
            <table border="1" width="100%" class="table_a">
                <tr>
                <input type="hidden" name='iid' value="<?php echo ($iid); ?>"/>
                <input type="hidden" name='id' value="<?php echo ($id); ?>"/>
                 <input type="hidden" name='p' value="<?php echo ($p); ?>"/>
                 <input type="hidden" name='t' value="<?php echo ($t); ?>"/>
                 <input type="hidden" name='masterid' value="<?php echo ($user["userid"]); ?>"/>
                    <td colspan='2'><?php echo ($user["nickname"]); ?></td>
                </tr>
               	<tr ><td width="5%">课程名</td><td><input type="text" name="name" value="<?php echo ($class["name"]); ?>"/></td></tr>
               	<tr ><td>车型</td><td><input type="text" name="carname" value="<?php echo ($class["carname"]); ?>"/></td></tr>
               	<tr ><td>练车方式</td><td><input type="text" name="traintype" value="<?php echo ($class["traintype"]); ?>"/>(多人一车/一人一车)</td></tr>
               	<tr ><td>官方价</td><td><input type="text" name="officialprice" value="<?php echo ($class["officialprice"]); ?>"/>(元)</td></tr>
               	<tr ><td>全款价</td><td><input type="text" name="whole517price" value="<?php echo ($class["whole517price"]); ?>"/>(元)</td></tr>
               	<tr ><td>预付费</td><td><input type="text"  name="prepay517deposit" value="<?php echo ($class["prepay517deposit"]); ?>"/>(元)</td></tr>
               	<tr ><td>等待时间</td><td><input type="text" name="linetime" value="<?php echo ($class["linetime"]); ?>"/>(天)</td></tr>
               	<tr ><td>班别</td><td><input type="text" name="classtime" value="<?php echo ($class["classtime"]); ?>"/>(例如 平时班)</td></tr>
               	<tr><td>费用包含</td><td><textarea name="include" cols="30" rows="10"><?php echo ($class["include"]); ?></textarea></td></tr>
        		<tr>
        			<td colspan='2'>
        				<input type="submit" value="更新"/>
        			</td>
        		</tr>
        	</table>
        </div>
       </form>
    </body>
</html>