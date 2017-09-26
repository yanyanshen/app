<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>学员员列表</title>

        <link href="/Public/ment/css/mine.css" type="text/css" rel="stylesheet" />
        <script src="/Public/ment/js/jquery.js"></script>
	     <script>
	     $("document").ready(function(){
	  		$("#b").click(function(){
				form=$("#form").serializeArray();
				url="?"
	 			for(i=0;i<form.length;i++){
					if(form[i].value!=''){
						url+=form[i].name+"="+form[i].value+"&";
					}
				}
				location.href =url;
	 		});
	     });
		</script>
    </head>
    <body>
        <style>
            .tr_color{background-color: #9F88FF}
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：系统管理-》学员列表</span>
                <span style="float: right; margin-right: 8px; font-weight: bold;">
                    <a style="text-decoration: none;" href="<?php echo U('add_stu');?>">【添加学员】</a>
                </span>
            </span>
        </div>
        <div></div>
        <div class="div_search">
            <span style="float:left">
                <form id='form'>
                    城市<select name="cityid" id="">
                    <option value="">请选择</option>
                <?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
                   学员姓名：<input type="text" name='nickname' /> 
                    学员账号：<input type="text" name='account' />
                    <input value="查询" type="button" id='b'/>
                </form>
            </span>
            <span style="float:right">总计：<?php echo ($count); ?>　　</span>
        </div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <th width="4%">编号</th>
						<th width="8%">账号</th>
						<th width="4%">头像</th>
						<th width="7%">学员姓名</th>
						<th width="4%">性别</th>
						<th width="10%">注册时间</th>
						<th width="8%">联系方式</th>
						<th width="4%">驾照类型</th>
						<th width="4%">当前科目</th>
						<th width="5%">订单个数</th>
						<th width="5%">预约个数</th>
						<th width="15%">地址</th>
						<th width="3%">状态</th>
						<th width="5%">最后操作人</th>
                        <th width="10%">操作</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr>
                    		<td><?php echo ($vv["id"]); ?></td>
                    		<td><?php echo ($vv["account"]); ?></td>
                    		<td><img src="http://www.517xc.com/Upload/small/<?php echo ($vv['img']); ?>" alt="" style="border-radius:50%" width="50" height="50" /></td>
	                    	<td><?php echo ($vv["nickname"]); ?></td>
							<td><?php echo ($vv['sex']==0?'保密':($vv['sex']==1?'男':'女')); ?></td>
							<td><?php echo ($vv['ntime']); ?></td>
							<td><?php echo ($vv["phone"]); ?></td>
							<td><?php echo ($vv["jtype"]); ?></td>
							<td>科目<?php echo ($vv['subjects']==0?'一':($vv['subjects']==1?'四':($vv['subjects']==2?'二':'三'))); ?></td>
						   	<td><a href="<?php echo U('order_list?masterid='.$vv['userid'].'&pp='.$p);?>" target=main><?php echo ($vv["listcount"]); ?>个订单</a></td>
						   	<td><a href=""><?php echo ($vv["rescount"]); ?>个预约</a></td>
						    <td><?php echo ($vv["address"]); ?></td>
						    <td><a href="<?php echo U('verify?id='.$vv['id'].'&flag='.$vv['verify'].'&p='.$p);?>"><?php echo ($vv['verify']==1?"<font style='color:green'>启用</font>":"<font style='color:red'>禁用</font>"); ?></a></td>
								<td><?php echo ($vv["lastupdate"]); ?></td>
						    <td>
						     <a title="编辑" href="<?php echo U('stu_info?id='.$vv['id'].'&p='.$p);?>">编辑</a>
						     <a title="删除" href="<?php echo U('del_stu?id='.$vv['id'].'&p='.$p);?>" onclick="if(confirm('确定删除?')==false)return false;" >删除</a></td>
                    	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>
                        <td colspan="20" style="text-align: center;" >
                            <?php echo ($page); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>