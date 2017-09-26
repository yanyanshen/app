<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>添加权限</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》添加权限</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('per_group?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
            <form action="<?php echo U('add_group');?>" method="post" enctype="multipart/form-data">
            <table border="1" width="100%" class="table_a">
                <tr><input type="hidden" name="type" value='1'/>
                    <td>权限名</td>
                    <td><input type="text" name="premissname" /><span style="color:red;"><?php echo ((isset($errorInfo["premissname"]) && ($errorInfo["premissname"] !== ""))?($errorInfo["premissname"]):""); ?></span></td>
                </tr>
                 <tr>
                    <td>描述</td>
                    <td><textarea name="description"></textarea></td>
                </tr>
                  
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="添加">
                    </td>
                </tr>  
            </table>
            </form>
        </div>
    </body>
</html>