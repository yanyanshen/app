<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>banner图片</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet">
        <script src="/Public/ment/js/jquery.js"></script>
    </head>
    <body>
        <div class="div_head">
            <span>
                <span style="float:left">当前位置是：系统管理-》banner图片</span>
            </span>
        </div>
        <div></div>

        <div style="font-size: 13px;margin: 10px 5px">
	<form action="<?php echo U('#');?>" method="post"  enctype="multipart/form-data">
            <table border="1" width="100%" class="table_a">
                <tr>
                    <td width="5%"></td>
                    <td colspan='4'>
                    	<form action="<?php echo U('#');?>" method="post" enctype="multipart/form-data">
                    		<input type="file" name="file"/>图片链接<input type="text" name="imgurl"/><input type="submit" />
                    	</form>
                    </td>
                </tr>
                <?php if(is_array($banner)): $i = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
	                    <td width="5%">图片<?php echo ($v["id"]); ?></td>
	                    <td widtn='30%'><img src="http://www.517xc.com/Upload/Banner/<?php echo ($v['imgname']); ?>" alt="" style="border-radius:40%" /></td>
	                    <td width='30'><?php echo ($v["imgurl"]); ?></td>
	                     <td width='5'>上传者：<?php echo ($v["manager"]); ?></td>
				 <td><a href="<?php echo U('delimg?id='.$v['id'].'&imgname='.$v['imgname']);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a></td>
	                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table></form>
        </div>
    </body>
</html>