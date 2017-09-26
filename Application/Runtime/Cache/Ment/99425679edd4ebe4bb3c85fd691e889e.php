<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>课程管理</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>
    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》课程管理</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                 <a style="text-decoration: none" href="<?php echo U('add_class?id='.$id.'&t='.$t.'&p='.$p);?>">【添加课程】</a>
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
                    <td colspan='20'><?php echo ($user["nickname"]); ?></td>
                </tr>
                <tr>
               		<td width="3%">序号</td>
                	<td width="3%">课程号</td>
                	<td width="10%">课程名</td>
                	<td width="4%">车型</td>
                	<td width="4%">练车方式</td>
                	<td width="3%">官方价</td>
                	<td width="3%">全款价</td>
                	<td width="3%">预付费</td>
                	<td width="4%">等待时间</td>
                	<td width="4%">班别</td>
                	<td width="20%">费用包含</td>
                	<td width="4%">操作</td>
                </tr>
                <?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
	                	<td><?php echo ($v["id"]); ?></td>
	                	<td><?php echo ($v["tcid"]); ?></td>
	                	<td><?php echo ($v["name"]); ?></td>
	                	<td><?php echo ($v["carname"]); ?></td>
	                	<td><?php echo ($v["traintype"]); ?></td>
	                	<td><?php echo ($v["officialprice"]); ?></td>
	                	<td><?php echo ($v["whole517price"]); ?></td>
	                	<td><?php echo ($v["prepay517deposit"]); ?></td>
	                	<td><?php echo ($v["linetime"]); ?></td>
	                	<td><?php echo ($v["classtime"]); ?></td>
	                	<td><?php echo ($v["include"]); ?></td>
	                	<td>
	                	<a href="<?php echo U('edit_class?iid='.$id.'&t=jx&p='.$p.'&id='.$v['id']);?>">编辑</a>
	                		<a href="<?php echo U('del_class?id='.$id.'&t=jx&p='.$p.'&iid='.$v['id']);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a>
	                	</td>
	                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
       </form>
    </body>
</html>