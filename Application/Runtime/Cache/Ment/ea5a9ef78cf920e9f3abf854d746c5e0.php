<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>添加课程</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>
    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》添加课程</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('class_Manage?id='.$id.'&t='.$t.'&p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>
        <form action="#" method="post">
        <div style="font-size: 13px;margin: 10px 5px">
            <table border="1" width="100%" class="table_a">
                <tr>
                <input type="hidden" name='id' value="<?php echo ($id); ?>"/>
                 <input type="hidden" name='p' value="<?php echo ($p); ?>"/>
                 <input type="hidden" name='t' value="<?php echo ($t); ?>"/>
                 <input type="hidden" name='masterid' value="<?php echo ($user["userid"]); ?>"/>
                    <td colspan='2'><?php echo ($user["nickname"]); ?></td>
                </tr>
               	<tr ><td width="5%">课程名</td><td><input type="text" name="name"/></td></tr>
               	<tr ><td>车型</td><td><input type="text" name="carname"/></td></tr>
               	<tr ><td>练车方式</td><td><input type="text" name="traintype"/>(多人一车/一人一车)</td></tr>
               	<tr ><td>官方价</td><td><input type="text" name="officialprice"/>(元)</td></tr>
               	<tr ><td>全款价</td><td><input type="text" name="whole517price"/>(元)</td></tr>
               	<tr ><td>预付费</td><td><input type="text"  name="prepay517deposit"/>(元)</td></tr>
               	<tr ><td>等待时间</td><td><input type="text" name="linetime"/>(天)</td></tr>
               	<tr ><td>班别</td><td><input type="text" name="classtime"/>(例如 平时班)</td></tr>
               	<tr><td>费用包含</td><td><textarea name="include" cols="30" rows="10">教材费、办证费、IC卡费、理科术科培训费燃油费、车辆及人员使用费、经营管理等费用，以及科目一、科目二、科目三考试费、一次补考费</textarea></td></tr>
        		<tr>
        			<td colspan='2'>
        				<input type="submit" value="添加"/>
        			</td>
        		</tr>
        	</table>
        </div>
       </form>
    </body>
</html>