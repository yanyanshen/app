<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>教学环境</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
        <script  src="/Public/ment/js/My97DatePicker/WdatePicker.js"></script>
    </head>

    <body>

        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》教学环境</span>
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
                    <td colspan='2'><?php echo ($nickname); ?></td>
                    <td colspan='2'><input type="file" name='file[]' multiple/>
                    <input type="submit" />(最多9张，请谨慎添加)
                    </td>
                </tr>
                <tr>
                	<td width="3%">序号</td>
                	<td width="10%">图片</td>
                	<td width="20%">路径</td>
                	<td>操作</td>
                </tr>
                <?php if(is_array($img)): $i = 0; $__LIST__ = $img;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                		<td><?php echo ($v["id"]); ?></td>
                		<td><img src="http://www.517xc.com<?php echo ($v['imgname']); ?>" alt="" style="border-radius:40%" /></td>
                		<td><?php echo ($v["imgname"]); ?></td>
                		<td><a href="<?php echo U('del_img?id='.$id.'&iid='.$v['id'].'&p='.$p.'&t='.$t);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a></td>
                	</tr><?php endforeach; endif; else: echo "" ;endif; ?> 
            </table>
            </form>
        </div>
    </body>
</html>