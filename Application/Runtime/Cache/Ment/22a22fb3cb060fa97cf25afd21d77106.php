<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>App用户日志</title>

        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <style>
            .tr_color{background-color: #9F88FF}
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：系统管理-》App用户日志</span>
                <span style="float: right;">总计：<?php echo ($count); ?>条</span>
            </span>
        </div>
        <div></div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <th width="5%">序号</th>
                        <th width="8%">用户姓名</th>
                        <th width="9%">ip</th>
                        <th width="10%">访问时间</th>
                         <th width="3%">phone</th>
                        <th width="70%">url</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($v["id"]); ?></td>
						<td><?php echo ($v["nickname"]); ?></td>
						<td><?php echo ($v["ip"]); ?></td>
						<td><?php echo ($v["dotime"]); ?></td>
						<td><?php echo ($v["phone"]); ?></td>
						<td><?php echo ($v["url"]); ?></td>
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