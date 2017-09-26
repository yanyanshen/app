<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>基地管理</title>

        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <style>
            .tr_color{background-color: #9F88FF}
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：系统管理-》基地管理</span>
                <span style="float: right; margin-right: 8px; font-weight: bold;">
                    <a style="text-decoration: none;" href="<?php echo U('add_train?cityid='.$cityid);?>">【添加基地】</a>
                </span>
            </span>
        </div>
        <div></div>
        <div class="div_search">
            <span style="float:left">
                <form action="#" method="post">
                    城市<select name="cityid" id="">
                <?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php echo ($cityid==$v['id']?'selected':''); ?>><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
                    <input value="查询" type="submit" value="查询"/>
                </form>
            </span>
            <span style="float:right">总计：<?php echo ($count); ?>　　</span>
        </div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <th width="3%">序号</th>
                        <th width="5%">基地</th>
                         <th width="20%">地址</th>
                        <th width="1%">操作</th>
                    </tr>
                    <?php if(is_array($train)): $i = 0; $__LIST__ = $train;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                    	<td><?php echo ($v["id"]); ?></td>
                    	<td><?php echo ($v["trname"]); ?></td>
                    	<td><?php echo ($v["address"]); ?></td>
                        <td>
                        	<a href="<?php echo U('del_train?id='.$v['id'].'&cityid='.$cityid.'&p='.$p);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr><td colspan='4' style="text-align: center;"><?php echo ($page); ?></td></tr>
                </tbody>
            </table>
        </div>
    </body>
</html>