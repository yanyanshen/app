<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>地标管理</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>
    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》基地管理</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U($url.'?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>
        <form action="<?php echo U('trainsave');?>" method="post">
        <div style="font-size: 13px;margin: 10px 5px">
            <table border="1" width="100%" class="table_a">
                <tr>
                <input type="hidden" name='id' value="<?php echo ($id); ?>"/>
                 <input type="hidden" name='p' value="<?php echo ($p); ?>"/>
                 <input type="hidden" name='t' value="<?php echo ($t); ?>"/>
                 <input type="hidden" name='userid' value="<?php echo ($user["userid"]); ?>"/>
                    <td colspan='2'><?php echo ($user["nickname"]); ?>　城市：<?php echo ($cityname); ?></td>
                    <td ><input type="submit"  value="保存"/></td>
                </tr>
                <tr>
               		<td width="3%">序号</td>
                	<td width="10%">基地</td>
                	<td width="80%">地址</td>
                </tr>
                <?php if(is_array($train)): $i = 0; $__LIST__ = $train;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
	                	<td><?php echo ($v["id"]); ?></td>
	                	<td><label>
	                			<?php if(in_array($v['id'],$trains)): ?><input type="checkbox" name="<?php echo ($v["id"]); ?>" value="<?php echo ($v["id"]); ?>" checked/><?php echo ($v["trname"]); ?>
								    <?php else: ?>
								    <input type="checkbox" name="<?php echo ($v["id"]); ?>" value="<?php echo ($v["id"]); ?>" /><?php echo ($v["trname"]); endif; ?>
	                		</label>
	                	</td>
	                	<td><?php echo ($v["address"]); ?></td>
	                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
       </form>
    </body>
</html>