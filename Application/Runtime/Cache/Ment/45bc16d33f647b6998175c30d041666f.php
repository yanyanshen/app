<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>分配权限</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
        <script src="/Public/ment/js/jquery.js"></script>
        <script>
        	$("document").ready(function(){
		 		$("#b").click(function(){
		 			a='';
		 			obj=$("input[type='checkbox']:checked").each(function(){
		 				a+=$(this).val()+',';
		 			});
		 			location.href="?id="+<?php echo ($id); ?>+"&p="+<?php echo ($p); ?>+"&ids="+a;
		 		});
        	});
 		</script>
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》分配权限</span>
                 <span style="margin-left:30%">账号：<?php echo ($pers["account"]); ?>　　姓名：<?php echo ($pers["username"]); ?></span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('admin_list?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
            <table border="1" width="10%" class="table_a">
                <tr>
                	<th>序号</th>
                    <th>勾选</th>
                    <th>权限</th>
                </tr>
                <?php if(is_array($permiss)): $i = 0; $__LIST__ = $permiss;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
	                 <td><?php echo ($v["id"]); ?></td>
	                    <td>
	                    <?php if(in_array($v['id'],$per)): ?><input type="checkbox" checked id=<?php echo ($v["id"]); ?> value=<?php echo ($v["id"]); ?>/>
    						<?php else: ?>  
    						<input type="checkbox" id=<?php echo ($v["id"]); ?> value=<?php echo ($v["id"]); ?>/><?php endif; ?>    
	                    </td>
	                    <td><?php echo ($v["premissname"]); ?></td>
	                 </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                <tr>
                    <td colspan="3" align="center">
                        <input type="button" value="保存" id='b'>
                    </td>
                </tr>  
            </table>
        </div>
    </body>
</html>