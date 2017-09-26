<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>管理员列表</title>

        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <style>
            .tr_color{background-color: #9F88FF}
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：系统管理-》管理员列表</span>
                <span style="float: right; margin-right: 8px; font-weight: bold;">
                    <a style="text-decoration: none;" href="<?php echo U('add_admin');?>">【添加管理员】</a>
                </span>
            </span>
        </div>
        <div></div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <th width="5%">序号</th>
                        <th width="8%">账号</th>
                        <th width="9%">姓名</th>
                        <th width="7%">部门</th>
                        <th width="7%">角色</th>
                        <th width="5%">电话</th>
                         <th width="14%">邮箱</th>
                         <th width="10%">创建时间</th>
                         <th width="10%">备注</th>
                        <th width="4%">状态</th>
                        <th width="5%">改变状态</th>
                        <th width="5%">权限分配</th>
                        <th width="10%">操作</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($v["id"]); ?></td>
                        <td><a href="#"><?php echo ($v["account"]); ?></a></td>
                        <td><?php echo ($v["username"]); ?></td>
                        <td><?php echo ($v['masgroup']==1?'管理组':($v['masgroup']==2?'技术组':($v['masgroup']==3?'销售组':($v['masgroup']==4?'客服组':'市场组')))); ?></td>
                        <td><?php echo ($v['role']==0?'管理员':'普通用户'); ?></td>
                        <td><?php echo ($v["phone"]); ?></td>
                        <td><?php echo ($v["email"]); ?></td>
                        <td><?php echo (date("Y-m-d H:i:s",$v["ntime"])); ?></td>
                        <th><?php echo ($v["note"]); ?></th>
                        <td><?php echo ($v["flag"]); ?></td>
                        <td><a href="<?php echo U('changeflag?flag='.$v['flag'].'&p='.$p.'&id='.$v['id']);?>"><?php echo ($v['flag']=='y'?'<l style="color:red">改为禁用</l>':'<l style="color:green">改为启用</l>'); ?></a></td>
                         <td><a href="<?php echo U('allot?id='.$v['id'].'&p='.$p);?>">分配</a></td>
                        <td>
                        	<a href="<?php echo U('edit_admin?account='.$v['account'].'&p='.$p);?>">编辑</a>
                        	<a href="<?php echo U('del_admin?account='.$v['account'].'&p='.$p);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a>
                        </td>
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