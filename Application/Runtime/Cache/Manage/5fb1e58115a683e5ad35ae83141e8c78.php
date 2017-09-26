<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/Public/517/favicon.ico" >
<LINK rel="Shortcut Icon" href="/Public/517/favicon.ico" />
<link href="/Public/517/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/517/lib/Hui-iconfont/1.0.6/iconfont.css" rel="stylesheet" type="text/css" />
<script src="/Public/517/js/jquery-1.12.0.min.js"></script>
  <script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>
<script>
$("document").ready(function(){
	$("#county").change(function(){
		$.post(
				"<?php echo U('retunland');?>",
				{
					countyid:$("#county option:selected").val(),
				},function(data,status){
					data1=eval("("+data+")");
					//循环前先清空
					$("#td").html("");
					for(i=0;i<data1.length;i++){
						url="<?php echo U('delland');?>?id="+data1[i].id;
						$("#td").append(data1[i].landname+"<a href="+url+"  onclick=if(confirm('确定删除')==false)return false><i class='Hui-iconfont'  style='color:red' >&#xe6a6;</i></a>&nbsp;&nbsp;");//在后面追
	          }
		});
	});
	$("#city").keyup(function(){
		$.post(
				"<?php echo U('returncity');?>",
				{
					 cityname:$("#city").val(),
				},function(data,status){
					data2=eval("("+data+")");
					//循环前先清空
					$("#citys").html('');
					for(i=0;i<data2.length;i++){
						id=data2[i]['id'];
						$("#citys").append("<option value="+id+">"+data2[i].cityname+"</option>");//加
						$("#citys").css("display",'block');
					}
			});
	});
	$("#citys").change(function(){
		$("#city").val($("#citys option:selected").text());
		$(this).hide();
	});
	$("#b2").click(function(){
		$.post(
				"<?php echo U('returncounty');?>",
				{
					 cityid:$("#citys option:selected").val(),
				},function(data,status){
					$('#landname').val($('#city').val()+" ");
					data2=eval("("+data+")");
					//循环前先清空
					$("#county").html('');

					for(i=0;i<data2.length;i++){
						id=data2[i]['id'];
						if(id==<?php echo ($countyid); ?>){
							$("#county").append("<option value="+id+" selected}>"+data2[i].countyname+"</option>");//加
						}else{
						$("#county").append("<option value="+id+">"+data2[i].countyname+"</option>");//加
						}
					}
			});
	});
	$("#land").click(function(){
		location.href="<?php echo U('land');?>?countyid="+$("#county option:selected").val();
	});


	$("#b1").click(function(){
			if($("#landname").val()=='' || $("longitude").val()==''||$("latitude").val()==''){
				alert('不能提交空值');return false;
			}
		});

});

</script>

<style>
#citys{position:absolute;background:white;z-index:1000}
</style>
<title>城市基地列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 城市地标列表 <span class="c-gray en">&gt;</span> 地标列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div style="float:left;margin-left:100px;">
<form action="<?php echo U('addland');?>" method='post' id="form2">
	<br><div style="float:left">请输入城市：</div><div style="float:left">
	<input type="text" name="cityname"  value="<?php echo ($cityname); ?>" id="city"
	onblur="if(this.value==''){this.value='上海'}"
	onfocus="if(this.value=='上海'){this.value=''}"/>
	<select name="cityid" id="citys" multiple="multiple" SIZE="10" style="display:none;width:135px;">
					<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["cityname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
	</div>
	 <input type="button" value='　查询　' id='b2' />
</div><br>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"> </span> <span class="r"></span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="6">地标</th>
			</tr>
			<tr class="text-c">

				<th width="100">区（县）</th>
				<th width="600">地标</th>
			</tr>
		</thead>
		<tbody>
			<tr class="text-c">
				<td>
					<select name="masterid" style="width:100px" id="county">
						<?php if(is_array($county)): $i = 0; $__LIST__ = $county;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v['id']); ?>" <?php echo ($v['id']==$countyid?'selected':''); ?>><?php echo ($v["countyname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</td>
				<td  id="td">
					<?php if(is_array($land)): $i = 0; $__LIST__ = $land;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; echo ($v["landname"]); ?><a href="<?php echo U('delland?id='.$v['id']);?>" onclick="if(confirm('确定删除')==false)return false;"><i class='Hui-iconfont'  style='color:red' >&#xe6a6;</i></a>&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
				</td>
			</tr>
		</tbody>
	</table><br><br>
		<div class="row cl">
			<div class="formControls col-4" style="width:62%;float:left">

		    		添加新地标：<input type="text"  class="input-text"  style="width:120px" name="landname" id="landname" value="<?php echo ($cityname); ?> " />　 <input type="button" value="　查询　" onclick="searchByStationName();"/>　经度 <input type="text" name="longitude" id="longitude" class="input-text" style="width:150px" /> 　纬度 <input type="text"  name="latitude" id="latitude" class="input-text" style="width:150px"/>　　<button class="btn btn-secondary radius" type="submit" id='b1'><i class="Hui-iconfont">&#xe632;</i> 添加地标</button>

		    </div></div></form>
				 <div id="container"
				style="position: absolute;
					margin-top:30px;
					width: 700px;
					height: 450px;
					top: 50;
					border: 1px solid gray;
					overflow:hidden;float:left">

		</div>
</div>
</body>
<script>
  var map = new BMap.Map("container");
    map.centerAndZoom("上海", 12);
    map.enableScrollWheelZoom(true);    //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
    map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
    map.addControl(new BMap.OverviewMapControl()); //添加默认缩略地图控件
    map.addControl(new BMap.OverviewMapControl({ isOpen: true, anchor: BMAP_ANCHOR_BOTTOM_RIGHT }));   //右下角，打开
    var localSearch = new BMap.LocalSearch(map);
    localSearch.enableAutoViewport(); //允许自动调节窗体大小
function searchByStationName() {
    map.clearOverlays();//清空原来的标注
    var keyword = document.getElementById("landname").value;
    localSearch.setSearchCompleteCallback(function (searchResult) {
        var poi = searchResult.getPoi(0);
        document.getElementById("longitude").value = poi.point.lng;
        document.getElementById("latitude").value =poi.point.lat;
        map.centerAndZoom(poi.point, 12);
        var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
        map.addOverlay(marker);
        var content = document.getElementById("landname").value + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
        var infoWindow = new BMap.InfoWindow("<p style='font-size:14px;'>" + content + "</p>");
        marker.addEventListener("click", function () { this.openInfoWindow(infoWindow); });
        // marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
    });
    localSearch.search(keyword);
	 window.onload = initialize;
}
</script>
</html>