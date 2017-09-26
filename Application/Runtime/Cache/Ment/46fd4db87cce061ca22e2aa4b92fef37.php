<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>添加学员</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：学员管理-》添加学员</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('stu_list?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
            <form action="<?php echo U('#');?>" method="post" enctype="multipart/form-data">
            <table border="1" width="100%" class="table_a">
                <tr>
                    <td width="7%">用户账号</td>
                    <td><input type="text" name="account" /><span style="color:red;"><?php echo ((isset($errorInfo["account"]) && ($errorInfo["account"] !== ""))?($errorInfo["account"]):""); ?></span></td>
                </tr>
                <tr>
                    <td>用户名称</td>
                    <td><input type="text" name="nickname" /><span style="color:red;"><?php echo ((isset($errorInfo["nickname"]) && ($errorInfo["nickname"] !== ""))?($errorInfo["nickname"]):""); ?></span></td>
                </tr>
             <tr>
                    <td>登录密码</td>
                    <td><input type="password" name="pass" value="244ac348537069c3bfe9d633349b7334" style="width:500px" /><span style="color:red;"><?php echo ((isset($errorInfo["pass"]) && ($errorInfo["pass"] !== ""))?($errorInfo["pass"]):""); ?>(默认为517xueche)</span></td>
                </tr>

		   <tr>
                    <td>性别</td>
                    <td><label><input type="radio" checked name="sex" value=1/>男</label>　<label><input type="radio" name="sex" value=2/>女</label></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="添加">　<input type="reset" value="取消">
                    </td>
                </tr>  
            </table>
            </form>
        </div>
    </body>
</html>