<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>添加管理员</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》添加管理员</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U('admin_list?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
            <form action="<?php echo U('#');?>" method="post" enctype="multipart/form-data">
            <table border="1" width="100%" class="table_a">
                <tr>
                    <td>用户账号</td>
                    <td><input type="text" name="account" /><span style="color:red;"><?php echo ((isset($errorInfo["account"]) && ($errorInfo["account"] !== ""))?($errorInfo["account"]):""); ?></span></td>
                </tr>
                 <tr>
                    <td>用户名称</td>
                    <td><input type="text" name="username" /><span style="color:red;"><?php echo ((isset($errorInfo["username"]) && ($errorInfo["username"] !== ""))?($errorInfo["username"]):""); ?></span></td>
                </tr>
                  <tr>
                    <td>登录密码</td>
                    <td><input type="password" name="pass" /><span style="color:red;"><?php echo ((isset($errorInfo["pass"]) && ($errorInfo["pass"] !== ""))?($errorInfo["pass"]):""); ?></span></td>
                </tr>
                <tr>
                    <td>所在部门</td>
                    <td>
                        <select name="masgroup">
                            <option value="0">请选择</option>
                            <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value=<?php echo ($v["id"]); ?>><?php echo ($v["groupname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>用户角色</td>
                    <td>
                        <select name="role">
                            <option value=2>请选择</option>
                            <option value=1>普通用户</option>
                            <option value=0>管理员</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>联系方式</td>
                    <td><input type="text" name="phone" /><span style="color:red;"><?php echo ((isset($errorInfo["phone"]) && ($errorInfo["phone"] !== ""))?($errorInfo["phone"]):""); ?></span></td>
                </tr>
                <tr>
                    <td>邮箱</td>
                    <td><input type="text" name="email" /><span style="color:red;"><?php echo ((isset($errorInfo["email"]) && ($errorInfo["email"] !== ""))?($errorInfo["email"]):""); ?></span></td>
                </tr>
                <tr>
                    <td>备注</td>
                    <td>
                        <textarea name="note"></textarea>
                    </td>
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