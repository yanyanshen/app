<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>驾校列表</title>

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
                <span style="float: left;">当前位置是：用户管理-》驾校列表</span>
                <span style="float: right; margin-right: 8px; font-weight: bold;">
                    <a style="text-decoration: none;" href="<?php echo U('add_jx');?>">【添加驾校】</a>
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
                    驾校简称：<input type="text" name='nickname' /> 
                    驾校账号：<input type="text" name='account' />
                    <input value="查询" type="button" id='b'/>
                </form>
            </span>
            <span style="float:right">总计：<?php echo ($count); ?>　　</span>
        </div>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <th width="3%">编号</th>
						<th width="5%">账号</th>
						<th width="4%">头像</th>
						<th width="6%">驾校简称</th>
						<th width="6%">注册时间</th>
						
						<th width="4%">进驻基地</th>
						<th width="4%">开设课程</th>
						<th width="5%">学员</th>
						<th width="5%">教练</th>
						<th width="3%">地标</th>
						<th width="3%">评分</th>
				
						<th width="3%">优先级</th>
						<th width="4%">学时价格</th>
						<th width="8%">驾校联系人</th>
						<th width="10%">地址</th>
						<th width="4%">城市</th>
						<th width="3%">证件</th>
						<th width="4%">认证</th>
						<th width="5%">最后操作人</th>
						<th width="10%">操作</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                    		<td><?php echo ($v["id"]); ?></td>
                    		<td><?php echo ($v["account"]); ?></td>
                    		<td><img src="http://www.517xc.com/Upload/small/<?php echo ($v['img']); ?>" alt="" style="border-radius:50%" width="50" height="50" /></td>
                    		<td><?php echo ($v["nickname"]); ?></td>
                    		<td><?php echo (substr($v['ntime'],0,10)); ?></td>
                    		<td><a href="<?php echo U('TeaManage/train_Manage?id='.$v['id'].'&t=jx&p='.$p);?>">查看</a></td>
                    		<td><a href="<?php echo U('TeaManage/class_Manage?id='.$v['id'].'&t=jx&p='.$p);?>"><?php echo ($v["classcount"]); ?>个课程</a></td>
                    		<td><?php echo ($v["stucount"]); ?>个学员</td>
                    		<td><?php echo ($v["coachcount"]); ?>个教练</td>
                    		<td><a href="<?php echo U('TeaManage/land_Manage?id='.$v['id'].'&p='.$p.'&t=jx');?>">查看</a></td>
                    		<td><?php echo ($v["score"]); ?></td>
                    		<td><?php echo ($v["priority"]); ?></td>
                    		<td><a href="<?php echo U('TeaManage/price_Manage?id='.$v['id'].'&t=jx&p='.$p);?>">查看</a></td>
                    		<td><?php echo ($v["connectteacher"]); ?></td>
                    		<td><?php echo ($v["address"]); ?></td>
                    		<td><?php echo ($v["city"]); ?></td>
                    		<td><a href="<?php echo U('TeaManage/zhengjian?id='.$v['id'].'&p='.$p.'&t=jx');?>">查看</a></td>
                    		<td><?php echo ($v['verify']==0?'未提交':($v['verify']==1?'未通过':($v['verify']==2?'认证中':'已通过'))); ?></td>
                    		<td><?php echo ($v["lastupdate"]); ?></td>
                    		<td><a href="<?php echo U('TeaManage/img_Manage?id='.$v['id'].'&p='.$p.'&t=jx');?>">教学环境</a>　<a href="<?php echo U('jx_info?id='.$v['id'].'&p='.$p);?>">编辑</a>　<a href="<?php echo U('del_school?id='.$v['id'].'&p='.$p);?>" onclick="if(confirm('确定删除?')==false)return false;">删除</a></td>
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