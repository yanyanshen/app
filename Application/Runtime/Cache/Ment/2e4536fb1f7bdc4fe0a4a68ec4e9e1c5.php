<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>学时价格</title>

        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <style>
            .tr_color{background-color: #9F88FF}
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：系统管理-》学时价格</span>
                <span style="float: right; margin-right: 8px; font-weight: bold;">
                    <a style="text-decoration: none;" href="<?php echo U('add_price?id='.$id.'&t='.$t.'&p='.$p);?>">【添加学时】</a>　
                    <a style="text-decoration: none" href="<?php echo U($url.'?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody>
                		 <input type="hidden" name='id' value="<?php echo ($id); ?>"/>
                 <input type="hidden" name='p' value="<?php echo ($p); ?>"/>
                 <input type="hidden" name='t' value="<?php echo ($t); ?>"/>
                 <input type="hidden" name='userid' value="<?php echo ($user["userid"]); ?>"/>
                    <tr><td colspan='20'><?php echo ($user["nickname"]); ?></td></tr>
                		<tr style="font-weight: bold;">
                        <th width="2%">序号</th>
                        <th width="4%">日期</th>
                        <th width="5%">学时</th>
                        <th width="3%">价格</th>
                        <th width="4%">驾照类型</th>
                        <th width="4%">科目</th>
                        <th width="60%">操作</th>
                    </tr>
                    <?php if(is_array($price)): $i = 0; $__LIST__ = $price;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                    		<td><?php echo ($v["id"]); ?></td>
                    		<td><?php echo ($v["weekdays"]); ?></td>
                    		<td><?php echo ($v["timebucket"]); ?></td>
                    		<td><?php echo ($v["price"]); ?></td>
                    		<td><?php echo ($v["jtype"]); ?></td>
                    		<td><?php echo ($v["subjects"]); ?></td>
                    		<td><a href="<?php echo U('edit_price?iid='.$id.'&p='.$p.'&t='.$t.'&id='.$v['id']);?>">编辑</a>　<a href="<?php echo U('?id='.$id.'&p='.$p.'&t='.$t.'&iid='.$v['id']);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a></td>
                    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>