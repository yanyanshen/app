<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>地标管理</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
	<script  src="/Public/ment/js/jquery.js"></script>
        <script>
        $("document").ready(function(){
      		$("#checkall").click(function(){
     			if($(this).val()=='全选'){
     				 $("input[type='checkbox']").prop('checked', true);
     				 $("#checkall").val("取消全选");
     			}else{
     				 $("input[type='checkbox']").prop('checked', false);
     				$("#checkall").val("全选");
     			}
     		});
         });
        </script>
    </head>
    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》地标管理</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U($url.'?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>
        <form action="<?php echo U('landsave');?>" method="post">
        <div style="font-size: 13px;margin: 10px 5px">
            <table border="1" width="100%" class="table_a">
                <tr>
                <input type="hidden" name='id' value="<?php echo ($id); ?>"/>
                 <input type="hidden" name='p' value="<?php echo ($p); ?>"/>
                 <input type="hidden" name='t' value="<?php echo ($t); ?>"/>
                 <input type="hidden" name='userid' value="<?php echo ($user["userid"]); ?>"/>
                    <td colspan='2'><?php echo ($user["nickname"]); ?></td>
                    <td ><input type="button" id="checkall" value="全选"/> 　<input type="submit"  value="保存"/></td>
                </tr>
                <tr>
               		<td width="3%">序号</td>
                	<td width="3%">区/县</td>
                	<td width="80%">地标</td>
                </tr>
                <?php if(is_array($county)): $i = 0; $__LIST__ = $county;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr height="40px">
	                	<td><?php echo ($v["id"]); ?></td>
	                	<td><?php echo ($v["countyname"]); ?></td>
	                	<td>
	                		<?php if(is_array($v['land'])): $i = 0; $__LIST__ = $v['land'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><label>
	                			<?php if(in_array($vv['id'],$land)): ?><input type="checkbox" name="<?php echo ($vv["id"]); ?>" value="<?php echo ($vv["id"]); ?>" checked/><?php echo ($vv["landname"]); ?>
								    <?php else: ?>
								    <input type="checkbox" name="<?php echo ($vv["id"]); ?>" value="<?php echo ($vv["id"]); ?>" /><?php echo ($vv["landname"]); endif; ?>
	                			</label><?php endforeach; endif; else: echo "" ;endif; ?>
	                	</td>
                	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
        </div>
       </form>
    </body>
</html>