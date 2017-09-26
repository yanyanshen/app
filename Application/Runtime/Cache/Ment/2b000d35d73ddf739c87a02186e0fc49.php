<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>订单列表</title>

        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <style>
            .tr_color{background-color: #9F88FF}
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：学员管理-》订单列表</span>
            </span>
        </div>
        <div></div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <th width="4%">序号</th>
                        <th width="10%">订单号</th>
                        <th width="9%">下单时间</th>
                        <th width="7%">用户姓名</th>
                        <th width="7%">当前姓名</th>
                        <th width="8%">驾校/教练/指导员</th>
                        <th width="17%">课程名</th>
                        <th width="4%">所在基地</th>
                        <th width="4%">订单状态</th>
                        <th width="5%">跟单客服</th>
                        <th width="5%">回访日期</th>
                        <th width="4%">状态</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($v["id"]); ?></td>
                        <td><a href="#"><?php echo ($v["listid"]); ?></a></td>
                        <td><?php echo ($v["listtime"]); ?></td>
                        <td><?php echo ($v["masname"]); ?></td>
                        <td><?php echo ($v["nickname"]); ?></td>
                        <td><?php echo ($v["listname"]); ?></td>
                        <td><?php echo ($v["description"]); ?></td>
                        <td><?php echo ($v["trname"]); ?></td>
                        <td><?php echo ($v['state']); ?>-<?php echo ($v['state']==0?'待付款':($v['state']==1?'待评价':($v['state']==2?'待确认':($v['state']==3?'已完成':'已取消')))); ?></td>
                        <td><?php echo ($v["customer"]); ?></td>
                         <td><?php echo ($v["returndate"]); ?></td>
                        <td><?php echo ($v['cl_type']=='y'?'<l style="color:green">已处理</l>':'<l style="color:red">未处理</l>'); ?></td>
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