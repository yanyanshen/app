<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>后台用户日志</title>

        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <style>
            .tr_color{background-color: #9F88FF}
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：系统管理-》后台用户日志</span>
                <span style="float: right;">总计：<?php echo ($count); ?>条</span>
            </span>
        </div>
        <div></div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <th width="3%">编号</th>
						<th width="5%">账号</th>
						<th width="10%">时间</th>
						<th width="10%">主机ip</th>
						<th width="50%">日志信息</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($v["id"]); ?></td>
						<td><?php echo ($v["account"]); ?></td>
						<td><?php echo (date("Y-m-d H:i:s",$v['ntime'])); ?></td>
						<td><?php echo (long2ip($v['nip'])); ?></td>
						<td><?php echo ($v["info"]); ?></td>
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