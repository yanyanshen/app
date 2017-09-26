<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>证件管理</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
        <script  src="/Public/ment/js/My97DatePicker/WdatePicker.js"></script>
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》证件管理</span>
                <span style="float:right;margin-right: 8px;font-weight: bold">
                    <a style="text-decoration: none" href="<?php echo U($url.'?p='.$p);?>">【返回】</a>
                </span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
            <form action="<?php echo U('#');?>" method="post" enctype="multipart/form-data">
            <table border="1" width="100%" class="table_a">
                <tr>
                <input type="hidden" name='id' value="<?php echo ($id); ?>"/>
                 <input type="hidden" name='p' value="<?php echo ($p); ?>"/>
                 <input type="hidden" name='t' value="<?php echo ($t); ?>"/>
                    <td colspan='2'><?php echo ($user["nickname"]); ?></td>
                     <td colspan='3'>
                         <a href="<?php echo U('verify?id='.$id.'&p='.$p.'&t='.$t.'&flag=3');?>">认证通过</a>
	                     <a href="<?php echo U('verify?id='.$id.'&p='.$p.'&t='.$t.'&flag=2');?>">正在认证</a>
	                     <a href="<?php echo U('verify?id='.$id.'&p='.$p.'&t='.$t.'&flag=1');?>">已拒绝</a>
                     </td>
                </tr>
                <tr>
                	<td width="3%">序号</td>
                	<td width="10%">图片</td>
                	<td width="20%">路径</td>
                	<td width="5%">类型</td>
                	<td>操作</td>
                </tr>
                <?php if(is_array($img)): $i = 0; $__LIST__ = $img;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                		<td><?php echo ($v["id"]); ?></td>
                		<td><img src="http://www.517xc.com<?php echo ($v['imgurl']); ?>" alt="" style="border-radius:40%" /></td>
                		<td><?php echo ($v["imgurl"]); ?></td>
                		<td><?php echo ($v['paperstype']=='yyzz'?'营业执照':($v['paperstype']=='jlz'?'教练证':($v['paperstype']=='jsz'?'驾驶证':'身份证'))); ?></td>
                		<td><a href="<?php echo U('del_zhengjian?id='.$id.'&iid='.$v['id'].'&p='.$p.'&t='.$t);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a></td>
                	</tr><?php endforeach; endif; else: echo "" ;endif; ?> 
            </table>
            </form>
        </div>
    </body>
</html>