<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>修改口令</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/css/mine.css" type="text/css" rel="stylesheet">
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》修改口令</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('Index/right');?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
            <form action="<?php echo U('#');?>" method="post">
            <table border="1" width="100%" class="table_a">
                <tr>
                    <td width="6%">用户账号</td>
                    <td><?php echo (session('account')); ?></td>
                </tr>
                  <tr>
                    <td>输入新密码</td>
                    <td><input type="password" name="newpass" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="修改">
                    </td>
                </tr>  
            </table>
            </form>
        </div>
    </body>
</html>