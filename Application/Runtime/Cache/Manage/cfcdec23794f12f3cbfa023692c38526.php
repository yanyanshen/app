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
<title>添加试题</title>
</head>
<body style="background:#9CC">
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 试题列表 <span class="c-gray en">&gt;</span> 添加试题 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="pd-20"">
<div style="float:left;width:42%;">
	<form action="<?php echo U('theoryList');?>" method="post" class="form form-horizontal">
	<input type="hidden" name='p' value="<?php echo ($p); ?>" />
		题 目：<input type="text" style="width:490px;height:30px" name ='question'/> <span style="color:red;"><?php echo ((isset($errorInfo["question"]) && ($errorInfo["question"] !== ""))?($errorInfo["question"]):""); ?></span><br /><br />
		选项A：<input type="text" style="width:490px;height:30px" name ='A'/><br /><br />
		选项B：<input type="text" style="width:490px;height:30px" name ='b'/><br /><br />
		选项C：<input type="text" style="width:490px;height:30px" name ='c'/><br /><br />
		选项D：<input type="text" style="width:490px;height:30px" name ='d'/><br /><br />
		地区：<input type="radio" name="AddressId" value=1/>全国　
		<input type="radio" name="AddressId1" value=1/>上海市　
		<input type="radio" name="AddressId2" value=1/>北京市　
		<input type="radio" name="AddressId3" value=1/>浙江省　
		<input type="radio" name="AddressId4" value=1/>山东省　
		<input type="radio" name="AddressId5" value=1/>山西省　
		<input type="radio" name="AddressId6" value=1/>安徽省　
		<input type="radio" name="AddressId7" value=1/>黑龙江省　
		<input type="radio" name="AddressId8" value=1/>四川省　<br /><br />
		科目：<input type="text" style="width:500px;height:30px"  name ='subjects'/> <span style="color:red;"><?php echo ((isset($errorInfo["subjects"]) && ($errorInfo["subjects"] !== ""))?($errorInfo["subjects"]):""); ?></span><br /><br />
		章节：<input type="text" style="width:500px;height:30px" name ='ChapterId'/> <span style="color:red;"><?php echo ((isset($errorInfo["ChapterId"]) && ($errorInfo["ChapterId"] !== ""))?($errorInfo["ChapterId"]):""); ?></span><br /><br />
		类型：<input type="text" style="width:500px;height:30px" name ='ClassId'/><span style="color:red;"><?php echo ((isset($errorInfo["ClassId"]) && ($errorInfo["ClassId"] !== ""))?($errorInfo["ClassId"]):""); ?></span><br /><br />
		图片：<input type="text" style="width:500px;height:30px" name ='imgurl'/><br /><br />
		答案：<input type="text" style="width:500px;height:30px" name ='answer'/><span style="color:red;"><?php echo ((isset($errorInfo["answer"]) && ($errorInfo["answer"] !== ""))?($errorInfo["answer"]):""); ?></span><br /><br />
		解析：<input type="text" style="width:500px;height:30px"  name ='analysis'/><span style="color:red;"><?php echo ((isset($errorInfo["analysis"]) && ($errorInfo["analysis"] !== ""))?($errorInfo["analysis"]):""); ?></span><br /><br />
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<input type="submit" value="添加 " class="btn btn-secondary radius"/>
				<a href="<?php echo U('theoryList?p='.$p);?>"><button class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 返回列表</button></a>
				<button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
</form>
</div>
<div style="float:left;width:58%;">
<img src="/Public/a.jpg" alt="" width="100%"/>
</div>
</div>
</body>
</html>