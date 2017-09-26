<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta content="MSHTML 6.00.6000.16674" name="GENERATOR" />
        <title>用户登录</title>
        <link href="/Public/ment/css/User_Login.css" type="text/css" rel="stylesheet" />
        <script src="/Public/ment/js/jquery.js"></script>
        <script>
     $("document").ready(function(){
  		$("#b").click(function(){
 			$.ajax({
 				url:"<?php echo U('checklogin');?>",
 			    type:"POST",
 		        data:$('#form').serialize(),
 		        success: function(data) {
 		          if(data==0){
 		        	 location.href="<?php echo U('index');?>";
 		          }else{
 		        	  alert(data);
 		          }
 		        }
 			});
 		});
     });
	</script>
    </head><body id="userlogin_body">
        <div></div>
        <div id="user_login">
            <dl>

                <dd id="user_top">
                    <ul>
                        <li class="user_top_l"></li>
                        <li class="user_top_c"></li>
                        <li class="user_top_r"></li></ul>
                </dd><dd id="user_main">
                    <form id='form'>
                        <ul>
                            <li class="user_main_l"></li>
                            <li class="user_main_c">
                                <div class="user_main_box">
                                    <ul>
                                        <li class="user_main_text">用户名： </li>
                                        <li class="user_main_input">
                                            <input class="TxtUserNameCssClass"  name="account" maxlength="20" style="width:165px"> </li></ul>
                                    <ul>
                                        <li class="user_main_text">密&nbsp;&nbsp;&nbsp;&nbsp;码： </li>
                                        <li class="user_main_input">
                                            <input class="TxtPasswordCssClass" name="pass" type="password">
                                        </li>
                                    </ul>
                                    <ul>
                                        <li class="user_main_text">验证码： </li>
                                        <li class="user_main_input">
                                            <input class="TxtValidateCodeCssClass"   name="code" type="text">
                                            <img style="vertical-align: middle" src="<?php echo U('verify_img');?>"  alt="" onClick="this.src='<?php echo U("verify_img");?>?+new Date()'"/>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="user_main_r">

                                <input style="border: medium none; background: url('/Public/ment/img/user_botton.gif') repeat-x scroll left top transparent; height: 122px; width: 111px; display: block; cursor: pointer;" id='b' type="button">
                            </li>
                        </ul>
                    </form>
                </dd><dd id="user_bottom">
                    <ul>
                        <li class="user_bottom_l"></li>
                        <li class="user_bottom_c"><span style="margin-top: 40px;"></span> </li>
                        <li class="user_bottom_r"></li></ul></dd></dl></div><span id="ValrUserName" style="display: none; color: red;"></span><span id="ValrPassword" style="display: none; color: red;"></span><span id="ValrValidateCode" style="display: none; color: red;"></span>
        <div id="ValidationSummary1" style="display: none; color: red;"></div>
    </body>
</html>