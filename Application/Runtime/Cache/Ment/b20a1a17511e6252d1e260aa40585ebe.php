<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv=content-type content="text/html; charset=utf-8" />
       <link href="/Public/ment/css/admin.css" type="text/css" rel="stylesheet" />
               <script src="/Public/ment/js/jquery.js"></script>
        <script>
            function expand(el)
            {
                childobj = document.getElementById("child" + el);

                if (childobj.style.display == 'none')
                {
                    childobj.style.display = 'block';
                }
                else
                {
                    childobj.style.display = 'none';
                }
                return;
            }
        </script>
        <script>
		$("document").ready(function(){
			var arr="<?php echo (session('permissid')); ?>";
				arr1=arr.split(",").join(",");
				for(i=0;i<arr1.length;i++){
					if(arr1[i]==','){
						continue;
					}else{
						$("#"+arr1[i]).css("display",'block').css("width",150);
					}
				}
		});
		</script>
    </head>
    <body>
        <table height="100%" cellspacing=0 cellpadding=0 width=170 
               background=/Public/ment/img/menu_bg.jpg border=0>
               <tr>
                <td valign=top align=middle>
                    <table cellspacing=0 cellpadding=0 width="100%" border=0>

                        <tr>
                            <td height=10></td></tr></table>
			<div style="display:none" id=1>
                    <table cellspacing=0 cellpadding=0 width=150 border=0 >
                        <tr height=22 >
                            <td  style="padding-left: 30px;" background=/Public/ment/img/menu_bt.jpg><a 
                                    class=menuparent onclick=expand(3) 
                                    href="javascript:void(0);">订单中心</a></td></tr>
                        <tr height=4>
                            <td></td></tr></table></div>
                    <table id=child3 style="display: none" cellspacing=0 cellpadding=0 
                           width=150 border=0>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('Order/order_list');?>" 
                                   target=_blank>订单列表</a></td></tr>
                       </table>
			<div style="display:none" id=2>
                    <table cellspacing=0 cellpadding=0 width=150 border=0 >
                        <tr height=22>
                            <td style="padding-left: 30px" background=/Public/ment/img/menu_bt.jpg><a 
                                    class=menuparent onclick=expand(4) 
                                    href="javascript:void(0);">客户服务</a></td></tr>
                        <tr height=4>
                            <td></td></tr></table></div>
                    <table id=child4 style="display: none" cellspacing=0 cellpadding=0 
                           width=150 border=0>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('Student/stu_list');?>" 
                                   target=right>学员管理</a></td></tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('School/jx_list');?>" 
                                   target=right>驾校管理</a></td></tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('Coach/jl_list');?>" 
                                   target=right>教练管理</a></td></tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('Guider/zdy_list');?>" 
                                   target=right>指导员管理</a></td></tr>
				<tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('System/consult_list');?>" 
                                   target=right>提问咨询</a></td></tr>
			     <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('System/comment_list');?>" 
                                   target=right>学员评论</a></td></tr>
                        <tr height=4>
                            <td colspan=2></td></tr></table>
                   
                   
                   <div id=3 style="display:none">
                    <table cellspacing=0 cellpadding=0 width=150 border=0 >
                        <tr height=22>
                            <td style="padding-left: 30px" background=/Public/ment/img/menu_bt.jpg><a 
                                    class=menuparent onclick=expand(7) 
                                    href="javascript:void(0);">系统管理</a></td></tr>
                        <tr height=4>
                            <td></td></tr></table></div>
                    <table id=child7 style="display: none" cellspacing=0 cellpadding=0 
                           width=150 border=0>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('System/land_Manage');?>" 
                                   target=right>地标管理</a></td></tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('System/train_Manage');?>" 
                                   target=right>基地管理</a></td></tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('System/per_group');?>" 
                                   target=right>权限与组</a></td></tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('System/admin_list');?>" 
                                   target=right>管理员列表</a></td></tr>
			 <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('System/sendPush');?>" 
                                   target=right>极光推送</a></td></tr>
				 <tr height=20>
                            <td align=middle width=30><img height=9
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild
                                   href="<?php echo U('System/banner');?>"
                                   target=right>banner图片</a></td></tr>
                        <tr height=4>
                            <td colspan=2></td></tr></table>
			<div id=4 style="display:none">
                    <table cellspacing=0 cellpadding=0 width=150 border=0 ">
                        <tr height=22>
                            <td style="padding-left: 30px" background=/Public/ment/img/menu_bt.jpg><a 
                                    class=menuparent onclick=expand(0) 
                                    href="javascript:void(0);">日志管理</a></td></tr>
                        <tr height=4>
                            <td></td></tr></table></div>
                    <table id=child0 style="display: none" cellspacing=0 cellpadding=0 
                           width=150 border=0>

                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                       src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('System/user_log');?>" 
                                   target=right>App用户日志</a></td></tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 
                                                           src="/Public/ment/img/menu_icon.gif" width=9></td>
                            <td><a class=menuchild 
                                   href="<?php echo U('System/admin_log');?>" 
                                   target=right>后台系统日志</a></td></tr></table></td>
                <td width=1 bgcolor=#d1e6f7></td>
            </tr>
        </table>
    </body>
</html>