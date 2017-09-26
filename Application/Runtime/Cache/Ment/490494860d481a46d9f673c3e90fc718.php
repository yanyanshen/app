<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>权限与组</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/xueche/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》权限与组</span>
                <span style="float:right;"><a style="text-decoration: none" href="<?php echo U('add_group');?>">【添加组】</a>
                	<a style="text-decoration: none" href="<?php echo U('add_permiss');?>">【添加权限】</a>
                </span>
            </span>
        </div>
        <div></div>
        <div style="font-size: 13px;margin: 10px 5px;float:left;width:50%;">
            <table border="1"  class="table_a" width="100%">
                <tr>
                	<th>序号</th>
                    <th >组别</th>
                    <th >说明</th>
                    <th>操作</th>
                </tr>
                <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
	                 <td><?php echo ($v["id"]); ?></td>
	                    <td>
	            <?php echo ($v["groupname"]); ?>
	                    </td> <td><?php echo ($v["description"]); ?></td>
	                    <td>
	                    	<a href="<?php echo U('?id='.$v['id'].'&type=0');?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a>
	                    </td>
	                 </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
        </div>
        <div style="font-size: 13px;margin: 10px 5px;float:left;width:45%"">
            <table border="1" width="100%" class="table_a">
                <tr>
                	<th>序号</th>
                    <th >权限</th>
                    <th >说明</th>
                    <th>操作</th>
                </tr>
                <?php if(is_array($per)): $i = 0; $__LIST__ = $per;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
	                 <td><?php echo ($v["id"]); ?></td>
	                    <td>
	                    <?php echo ($v["premissname"]); ?>
	                    </td><td><?php echo ($v["description"]); ?></td>
	                    <td><a href="<?php echo U('?id='.$v['id'].'&type=1');?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a></td>
	                 </tr><?php endforeach; endif; else: echo "" ;endif; ?> 
            </table>
        </div>
    </body>
</html>