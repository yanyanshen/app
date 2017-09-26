<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<link href="/Public/517/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/Hui-iconfont/1.0.6/iconfont.css" rel="stylesheet" type="text/css" />
<title>我的桌面</title>
</head>
<body>
<div class="pd-20" style="padding-top:20px;">
  <p class="f-20 text-success">欢迎来到517后台管理界面<span class="f-14"></span></p>
  <p>登录次数：<?php echo ($countlogin); ?> </p>
  <p>上次登录IP：<?php echo (long2ip($data['nip'])); ?>  上次登录时间：<?php echo (date("Y-m-d H:i:s",$data['ntime'])); ?></p>
  <table class="table table-border table-bordered table-bg">
    <thead>
      <tr>
        <th colspan="9" scope="col">订单信息统计</th>
      </tr>
      <tr class="text-c">
        <th>统计</th>
        <th>下单数</th>
        <th>待付款</th>
        <th>已付款</th>
        <th>待评价</th>
        <th>已完成</th>
        <th>待确认</th>
        <th>退单数</th>
      </tr>
    </thead>
    <tbody>
      <tr class="text-c">
        <td>总数</td>
        <td><?php echo ($count); ?></td>
       <td><?php echo ($countlist[0][0]['id']); ?></td>
        <td><?php echo ($countlist[0][1]['id']); ?></td>
        <td><?php echo ($countlist[0][1]['id']); ?></td>
       <td><?php echo ($countlist[0][2]['id']); ?></td>
         <td><?php echo ($countlist[0][3]['id']); ?></td>
        <td></td>
      </tr>
      <tr class="text-c">
        <td>今日</td>
        <td><?php echo ($countlist[1]); ?></td>
        <td><?php echo ($todaystate[0]==''?0:$todaystate[0]); ?></td>
        <td><?php echo ($todaystate[1]==''?0:$todaystate[1]); ?></td>
        <td><?php echo ($todaystate[1]==''?0:$todaystate[1]); ?></td>
       <td><?php echo ($todaystate[2]==''?0:$todaystate[2]); ?></td>
        <td><?php echo ($todaystate[3]==''?0:$todaystate[3]); ?></td>
        <td></td>
      </tr>
      <tr class="text-c">
        <td>昨日</td>
        <td><?php echo ($countlist[2]); ?></td>
        <td><?php echo ($yesdaystate[0]==''?0:$yesdaystate[0]); ?></td>
        <td><?php echo ($yesdaystate[1]==''?0:$yesdaystate[1]); ?></td>
        <td><?php echo ($yesdaystate[1]==''?0:$yesdaystate[1]); ?></td>
        <td><?php echo ($yesdaystate[2]==''?0:$yesdaystate[2]); ?></td>
        <td><?php echo ($yesdaystate[3]==''?0:$yesdaystate[3]); ?></td>
        <td></td>
      </tr>
      <tr class="text-c">
        <td>本月</td>
        <td><?php echo ($countlist[3]); ?></td>
        <td><?php echo ($monthstate[0]==''?0:$monthstate[0]); ?></td>
        <td><?php echo ($monthstate[1]==''?0:$monthstate[1]); ?></td>
        <td><?php echo ($monthstate[1]==''?0:$monthstate[1]); ?></td>
        <td><?php echo ($monthstate[4]==''?0:$monthstate[4]); ?></td>
        <td><?php echo ($monthstate[3]==''?0:$monthstate[3]); ?></td>
        <td></td>
      </tr>
      <tr class="text-c">
        <td>上月</td>
        <td><?php echo ($countlist[4]); ?></td>
        <td><?php echo ($yesmonthstate[0]==''?0:$yesmonthstate[0]); ?></td>
        <td><?php echo ($yesmonthstate[1]==''?0:$yesmonthstate[1]); ?></td>
        <td><?php echo ($yesmonthstate[1]==''?0:$yesmonthstate[1]); ?></td>
        <td><?php echo ($yesmonthstate[2]==''?0:$yesmonthstate[2]); ?></td>
        <td><?php echo ($yesmonthstate[3]==''?0:$yesmonthstate[3]); ?></td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <!-- <table class="table table-border table-bordered table-bg mt-20">
    <thead>
      <tr>
        <th colspan="2" scope="col">服务器信息</th>
      </tr>
    </thead>
    <tbody>
		<tr>
        <th width="200">操作系统</th>
        <td><span id="lbServerName"><?php echo ($ww); ?></span></td>
      </tr>
      <tr>
        <th width="200">PHP版本</th>
        <td><span id="lbServerName"><?php echo ($php); ?></span></td>
      </tr>
      <tr>
        <th width="200">运行环境</th>
        <td><span id="lbServerName"><?php echo ($_SERVER["SERVER_SOFTWARE"]); ?></span></td>
      </tr>
      <tr>
        <th width="200">服务器域名</th>
        <td><span id="lbServerName"><?php echo ($_SERVER['SERVER_NAME']); ?></span></td>
      </tr>
      <tr>
        <th width="200">web服务端口</th>
        <td><span id="lbServerName"><?php echo ($_SERVER['SERVER_PORT']); ?></span></td>
      </tr>
      <tr>
        <th width="200">网站文档目录</th>
        <td><span id="lbServerName"><?php echo ($_SERVER["DOCUMENT_ROOT"]); ?></span></td>
      </tr>
      <tr>
        <th width="200">请求方法</th>
        <td><span id="lbServerName"><?php echo ($_SERVER['REQUEST_METHOD']); ?></span></td>
      </tr>
      <tr>
        <th width="200">ThinkPHP版本</th>
        <td><span id="lbServerName"><?php echo ($ver); ?></span></td>
      </tr>
       <tr>
        <th width="200">服务器脚本超时时间</th>
        <td><span id="lbServerName"><?php echo ($s); ?></span></td>
      </tr>
      <tr>
        <th width="200">系统目录</th>
        <td><span id="lbServerName"><?php echo ($tp); ?></span></td>
      </tr>
      <tr>
        <th width="200">数据库版本</th>
        <td><span id="lbServerName"><?php echo ($sql); ?></span></td>
      </tr>
     <tr>
        <th width="200">IP地址</th>
        <td><span id="lbServerName"><?php echo ($_SERVER['REMOTE_ADDR']); ?></span></td>
      </tr>
       <tr>
        <th width="200">上传文件限制</th>
        <td><span id="lbServerName"><?php echo ($file); ?></span></td>
      </tr>
    </tbody>
  </table>-->
</div>
</body>
</html>