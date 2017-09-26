<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>教练列表</title>

        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <style>
            .tr_color{background-color: #9F88FF}
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：用户管理-》提问列表</span>
                <span style="float: right;">总计<?php echo ($count); ?>条</span>
            </span>
        </div>
        <div></div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <th width="2%">编号</th>
						<th width="6%">学员姓名</th>
						<th width="6%">联系方式</th>
						<th width="6%">咨询对象</th>
						<th width="6%">咨询时间</th>
						<th width="50%">咨询内容</th>
						<th width="10%">操作</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                    		<td><?php echo ($v["id"]); ?></td>
                    		<td><?php echo ($v["user"]["nickname"]); ?></td>
                    		<td><?php echo ($v["user"]["account"]); ?></td>
                    		<td><?php echo ($v["coach"]); ?></td>
                    		<td><?php echo ($v["time"]); ?></td>
                    		<td><?php echo ($v["content"]); ?></td>
                    		<td><a href="<?php echo U('del_eval?id='.$v['id'].'&p='.$p.'&y=2');?>">删除</a></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>
                        <td colspan="20" style="text-align: center;" >
                            <?php echo ($page); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>