<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/Public/517/favicon.ico" >
<LINK rel="Shortcut Icon" href="/Public/517/favicon.ico" />
<link href="/Public/517/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/Hui-iconfont/1.0.6/iconfont.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/Public/517/js/jquery-1.12.0.min.js"></script>
	<script>
	$("document").ready(function(){
		$("#b").click(function(){
			$.post(
				"<?php echo U('checkuser');?>",
				{
					account:$("#account").val(),
					pass:$("#pass").val(),
					code:$("#code").val(),
				},function(data,status){
				    if(data == "登录成功"){
				    	alert(data);
				    	location.href="<?php echo U('index');?>";
				    }else{alert(data);}
				});
			});

	});
	</script>
<title>后台登录 -517</title>
</head>
<body>
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox">
      <div class="row cl" style="margin-left:115px;">
        <label class="form-label col-3" style="width:40px"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-8">
          <input name="account" type="text" placeholder="账户" class="input-text size-L" id="account">
        </div>
      </div>
      <div class="row cl" style="margin-left:115px;">
        <label class="form-label col-3"  style="width:40px"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-8">
          <input name="pass" type="password" placeholder="密码" class="input-text size-L" id="pass">
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-8 col-offset-3">
          <input class="input-text size-L" type="text" placeholder="验证码" onblur="if(this.value==''){this.value='验证码:'}" onfocus="if(this.value=='验证码:'){this.value=''}"  value="验证码:" style="width:150px;" name="code" id="code" >
          <img src="<?php echo u('verify');?>" id='cc' onClick="this.src='<?php echo U("verify");?>?+new Date()'"> <a id="kanbuq" href="javascript:;" onClick="document.getElementById('cc').src='<?php echo U("verify");?>?+new Date()'">看不清，换一张</a> </div>
      </div>
      <div class="row">
        <div class="formControls col-8 col-offset-3">
          <button id="b" type="button" class="btn btn-success radius size-L" >&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;</button>
          <input  type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
        </div>
      </div>
  </div>
</div>
<div class="footer">Copyright 吾要去信息科技有限公司</div>
</body>
</html>