<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>添加课时</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>
    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》添加课时</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('price_Manage?id='.$id.'&t='.$t.'&p='.$p);?>">【返回】</a>
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
               	<tr ><td width="5%">日期</td><td><input type="text" name="weekdays"/>(日期为正常星期天-1，中间以'-'隔开)</td></tr>
               	<tr ><td>学时</td><td><input type="text" name="timebucket"/>(学时=小时x2,中间以'-'隔开)</td></tr>
               	<tr ><td>价格</td><td><input type="text" name="price" />(元)</td></tr>
               	<tr ><td>驾照类型</td><td><input type="text" name="jtype" />(如C1)</td></tr>
               	<tr ><td>科目</td><td><input type="text" name="subjects"/>(2/3)</td></tr>
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