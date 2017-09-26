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
<link href="/Public/517/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />
<title>修改试题</title>
</head>
<body style="background:#9CC">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 试题列表 <span class="c-gray en">&gt;</span> 修改试题 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="pd-20">
	<form action="<?php echo U('updatetheory');?>" method="post" class="form form-horizontal">
	<input type="hidden" name='p' value="<?php echo ($p); ?>" />
		题目id：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["id"]); ?>" readonly name ='id'/><br /><br />
		题目：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["question"]); ?>" name ='question'/><br /><br />
		A：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["a"]); ?>" name ='A'/><br /><br />
		B：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["b"]); ?>" name ='b'/><br /><br />
		C：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["c"]); ?>" name ='c'/><br /><br />
		D：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["d"]); ?>" name ='d'/><br /><br />
		科目：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["subjects"]); ?>" name ='subjects'/>(科一0 科四1)<br /><br />
		章节：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["chapterid"]); ?>" name ='ChapterId'/><br /><br />
		类型：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["classid"]); ?>" name ='ClassId'/>(单选0 判断1 多选2)<br /><br />
		图片：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["imgurl"]); ?>" name ='imgurl'/><br /><br />
		答案：<input type="text" style="width:500px;height:30px" value="<?php echo ($theory["answer"]); ?>" name ='answer'/><br /><br />
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<input type="submit" value="保存更新 " class="btn btn-secondary radius"/>
				<a href="<?php echo U('theoryList?p='.$p);?>"><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回列表</button></a>
				<button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
</form>
</div>
</body>
</html>