<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link href="/Public/517/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/icheck/icheck.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/Hui-iconfont/1.0.6/iconfont.css" rel="stylesheet" type="text/css" />
<title>更新管理员信息</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span>  管理员列表  <span class="c-gray en">&gt;</span> 修改用户信息<a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
<form action="<?php echo U('updateuser');?>" method="post" class="form form-horizontal" id="form-admin-add">
    <div class="row cl">
      <label class="form-label col-3"><span class="c-red">*</span>用户名：</label>
      <div class="formControls col-5">
        <input type="text" class="input-text" value="<?php echo ($userinfo["account"]); ?>" name="account" readonly>
      </div>
      <div class="col-4"> </div>
    </div>
    <div class="row cl">
      <label class="form-label col-3"><span class="c-red">*</span>管理员姓名：</label>
      <div class="formControls col-5">
        <input type="text" class="input-text" value="<?php echo ($userinfo["username"]); ?>" name="username" >
      </div>
      <div class="col-4"> </div>
    </div>
	  <div class="row cl">
      <label class="form-label col-3"><span class="c-red">*</span>输入新密码：</label>
      <div class="formControls col-5">
        <input type="password" class="input-text" value="<?php echo ($userinfo["pass"]); ?>" name="pass" >
      </div>
      <div class="col-4"> </div>
    </div>
    <div class="row cl">
      <label class="form-label col-3"><span class="c-red">*</span>联系方式：</label>
      <div class="formControls col-5">
        <input type="text" class="input-text" value="<?php echo ($userinfo["phone"]); ?>" name="phone" >
      </div>
      <div class="col-4"> </div>
    </div>
     <div class="row cl">
      <label class="form-label col-3"><span class="c-red">*</span>管理员邮箱：</label>
      <div class="formControls col-5">
        <input type="text" class="input-text" value="<?php echo ($userinfo["email"]); ?>" name="email" >
      </div>
      <div class="col-4"> </div>
    </div>
    <div class="row cl">
      <label class="form-label col-3"><span class="c-red">*</span>创建时间：</label>
      <div class="formControls col-5">
        <input type="text" class="input-text"  value="<?php echo (date('Y-m-d',$userinfo["ntime"])); ?>" readonly>
      </div>
      <div class="col-4"> </div>
    </div>
    <div class="row cl">
			<label class="form-label col-3">角色：</label>
			<div class="formControls col-5"> <span class="select-box" style="width:150px;">
				<select class="select" name="role" size="1">
					<option value="1" <?php echo ($userinfo['role']==1?'selected':''); ?>>普通用户</option>
					<option value="0" <?php echo ($userinfo['role']==0?'selected':''); ?>>管理员</option>
				</select>
				</span> </div>
		</div>
		<div class="row cl">
			<label class="form-label col-3">部门：</label>
			<div class="formControls col-5"> <span class="select-box" style="width:150px;">
				<select class="select" name="masgroup" size="1">
					<option value="1" <?php echo ($userinfo['masgroup']==1?'selected':''); ?>>管理组</option>
					<option value="2" <?php echo ($userinfo['masgroup']==2?'selected':''); ?>>技术组</option>
					<option value="3" <?php echo ($userinfo['masgroup']==3?'selected':''); ?>>销售组</option>
					<option value="4" <?php echo ($userinfo['masgroup']==4?'selected':''); ?>>客服组</option>
					<option value="5" <?php echo ($userinfo['masgroup']==5?'selected':''); ?>>市场组</option>
				</select>
				</span> </div>
		</div>
    <div class="row cl">
      <label class="form-label col-3">备注信息：</label>
      <div class="formControls col-5">
        <textarea name="note" cols="" rows="" class="textarea" datatype="*10-100"><?php echo ($userinfo["note"]); ?></textarea>
      </div>
      <div class="col-4"> </div>
    </div>
    <div class="row cl">
      <div class="col-9 col-offset-3">
       <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;保存更新&nbsp;&nbsp;">
      </div>
    </div>
    </form>
</div>
</body>
</html>