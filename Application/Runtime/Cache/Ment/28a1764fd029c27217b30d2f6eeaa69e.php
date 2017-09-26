<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>管理员详情</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》管理员详情</span>
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
                     <input type="hidden" name='p' value="<?php echo ($p); ?>" />
                     <input type="hidden" name='id' value="<?php echo ($admin["id"]); ?>" />
                    <td><input type="text" name="account" value="<?php echo ($admin["account"]); ?>" /></td>
                </tr>
                 <tr>
                    <td>用户名称</td>
                    <td><input type="text" name="username"  value="<?php echo ($admin["username"]); ?>"/></td>
                </tr>
                  <tr>
                    <td>登录密码</td>
                    <input type="hidden" name="oldpass" value="<?php echo ($admin["pass"]); ?>"/>
                    <td><input type="password" name="pass" value="<?php echo ($admin["pass"]); ?>"/></td>
                </tr>
                <tr>
                    <td>所在部门</td>
                    <td>
                        <select name="masgroup">
                            <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value=<?php echo ($v["id"]); ?> <?php echo ($admin['masgroup']==$v['id']?'selected':''); ?>><?php echo ($v["groupname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>用户角色</td>
                    <td>
                        <select name="role">
                            <option value=1 <?php echo ($admin['role']==1?'selected':''); ?>>普通用户</option>
                            <option value=0 <?php echo ($admin['role']==0?'selected':''); ?>>管理员</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>联系方式</td>
                    <td><input type="text" name="phone" value="<?php echo ($admin["phone"]); ?>" /></td>
                </tr>
                <tr>
                    <td>邮箱</td>
                    <td><input type="text" name="email" value="<?php echo ($admin["email"]); ?>"/></td>
                </tr>
                <tr>
                    <td>备注</td>
                    <td>
                        <textarea name="note" value="<?php echo ($admin["note"]); ?>"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="更新">
                    </td>
                </tr>  
            </table>
            </form>
        </div>
    </body>
</html>