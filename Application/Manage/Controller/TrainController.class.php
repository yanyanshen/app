<?php
namespace Manage\Controller;
use Think\Controller;
use Org\Util\Page;
class TrainController extends CommonController {
   //-----------------------------------------------8.4------------------------------------------
   //城市基地列表
   public function trainList(){
       $cityname=I('post.cityname');
       $where="cityname like '%$cityname%'";
       $Data=M("citys"); // 实例化Data数据对象
       $count=$Data->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count,15);// 实例化分页类 传入总记录数
       $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
       $show=$Page->show($Page->nowPage);//分页显示输出
       // 进行分页数据查询
       $list = $Data->field("id,cityname")->where($where)->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
       foreach ($list as $k=>$v){
           $cityid=$v['id'];
           $list[$k]['train']=M("train")->field("id as tid,trname")->where("cityid=$cityid")->select();
           $list[$k]['traincount']=count($list[$k]['train']);
       }
       $this->assign("list",$list);
       $this->assign('page',$show);// 赋值分页输出
       $this->assign('p',$Page->nowPage);// 赋值分页输出
       $this->assign('rowscount',$count);// 总得记录数
       $this->display();
   }
   //
   public function train(){
       $cityid=I('get.cityid');
       $cityname=M('citys')->field("cityname")->where("id=$cityid")->find()['cityname'];
       $train=M("train")->field("id,trname")->where("cityid=$cityid")->select();
       $this->assign("cityname",$cityname);//该城市的名字
       $this->assign("train",$train);//该城市所有的基地
       $this->assign("cityid",$cityid);
       $this->assign("p",I('get.p'));
       $this->display();
   }
   //删除该基地
   public function delcitytrain(){
       $id=I('get.id');
       $p=I('get.p');$cityid=I('get.cityid');
       M('train')->where("id=$id")->delete();
       $this->redirect("Train/train?cityid={$cityid}&p={$p}");
   }
   //实现添加基地
   public function addcitytrain(){
       
       foreach ($_GET as $k=>$v){
           $post[]=$k;
       }
       $train=explode(",",$post[0]);
	 
       foreach ($train as $k=>$v){
           $trains[$k]['trname']=$v;
           $trains[$k]['cityid']=trim($post[1],"'");
       }
       if(M("train")->addAll($trains)){
		   addlog("添加基地成功");
           echo 1;
       }else{
		   addlog("添加基地失败");
           echo 0;
       }
   }
  /* //基地的列表   根据市/区来显示地标
   public function landList(){
      $Data=M("citys"); // 实例化Data数据对象
       $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count,15);// 实例化分页类 传入总记录数
       $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
      
       $show=$Page->show($Page->nowPage);//分页显示输出
       // 进行分页数据查询
       $list = $Data->field("id,cityname")->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
       //找区
       foreach($list as $k=>$v){
           $masterid=$v['id'];
           $list[$k]['county']=M("countys")->field("id as cid,countyname")->where("masterid=$masterid")->select();
//            foreach ($list[$k]['county'] as $kk=>$vv){
//                $masid=$vv['cid'];
//                $list[$k]['county'][$kk]['land']=M('landmark')->field("id as lid,landname")->where("masterid=$masid")->select();
//            }
       }
       $this->assign("list",$list);
       $this->assign('page',$show);// 赋值分页输出
       $this->assign('p',$Page->nowPage);// 赋值分页输出
       $this->assign('rowscount',$count);// 总得记录数
       $this->display();
   }*/
   //ajax返回地标，在列表显示
   public function retunland(){
       $masterid=I('post.countyid');//接收区的id
       $land=M('landmark')->field("id,landname")->where("masterid=$masterid")->select();
       echo $this->result($land);
   }
   //地标的更新添加页面
   public function land(){
       $masterid=I("get.countyid");
       $county=M("countys")->field("countyname,masterid")->where("id=$masterid")->find();
       $cityid=$county['masterid'];
       $cityname=M('citys')->field('cityname')->where("id=$cityid")->find()['cityname'];
       $land=M("landmark")->field("id,landname")->where("masterid=$masterid")->select();
       $this->assign("countyname",$county['countyname']);
       $this->assign("land",$land);
       $this->assign("cityname",$cityname);
       $this->assign("countyid",$masterid);
       $this->display();
   }
   //删除该地标
   public function delland(){
       $id=I('get.id');
       $countyid=M('landmark')->field("masterid")->where("id=$id")->find()['masterid'];
	   $cityid=M('countys')->field('masterid')->where("id=$countyid")->find()['masterid'];
       M('landmark')->where("id=$id")->delete();
       $this->redirect("Train/countyland?countyid={$countyid}&cityid={$cityid}");
   }
   //添加新地标
   public function addland(){
       $masterid=I('post.masterid');
	   $cityid=M("countys")->field('masterid')->where("id=$masterid")->find()['masterid'];
       $landname=explode(" ",$_POST['landname'])[1];
       if(M('landmark')->where("landname='$landname' and masterid=$masterid")->find()){
		    $this->redirect("Train/countyland?countyid={$masterid}&cityid={$cityid}&t=1");return;
       }
	   unset($_POST['landname']);$_POST['landname']=$landname;
       if(M('landmark')->add($_POST)){
		    addlog("添加地标成功");
		    $this->redirect("Train/countyland?countyid={$masterid}&cityid={$cityid}");
       }else{
		    addlog("添加基地失败");
		    $this->redirect("Train/countyland?countyid={$masterid}&cityid={$cityid}&t=0");
	   }
   }
   //----------------------------------------------------------------------8.5
    //---------------------------------------------------------------
   public function countyland(){
	   	 if(isset($_GET['t'])){
			 if($_GET['t']==1){
				 alert("该地标已经存在");
			 }else{
				alert("添加失败");
			 }
		 }
	   if(isset($_REQUEST['countyid'])){
		   $cityid=$_REQUEST['cityid'];
		   $cityname=M('citys')->field("cityname")->where("id=$cityid")->find()['cityname'];
		   $county=M('countys')->field("id,countyname")->where("masterid=$cityid")->select();
		   $county1=$_REQUEST['countyid'];
		   $land=M("landmark")->field("id,landname")->where("masterid=$county1")->select();
	   }else{
		    $cityname="上海";
		     //默认是上海
			$cityid=M('citys')->field("id")->where("cityname like '%上海%'")->find()['id'];
			$county=M('countys')->field("id,countyname")->where("masterid=$cityid")->select();
			$county1=$county[0]['id'];
		    $land=M("landmark")->field("id,landname")->where("masterid=$county1")->select();
	   } 
	    //找区
	    // if(isset($_GET['countyid'])){
		   //   $county1=$_GET['countyid'];
		  //    $land=M("landmark")->field("id,landname")->where("masterid=$county1")->select();
	 //  }else{
		//      $county1=$county[0]['id'];
		//      $land=M("landmark")->field("id,landname")->where("masterid=$county1")->select();
	  // }

       $this->assign("county",$county);
       $this->assign("land",$land);
	   $this->assign("cityid",$cityid);
	   $this->assign("countyid",$county1);
	   $this->assign("cityname",$cityname);//搜索框中的城市名（默认是上海）
       $this->display();
   }
   //返回城市
   public function returncity(){
       $cityname=I("post.cityname");
       $city=M("citys")->field("id,cityname")->where("cityname like '%$cityname%'")->select();
       echo $this->result($city);
   }
   //返回区
   public function returncounty(){
        $cityid=I('post.cityid');
        $county=M('countys')->field("id,countyname")->where("masterid=$cityid")->select();
        echo $this->result($county);
   }
   //根据用户的基地来显示这个基地的 驾校和教练
   public function coachstrain(){
		$trainid=I('get.trainid');
		$cityid=I('get.cityid');
		$trname=M('train')->field('trname')->where("id=$trainid")->find()['trname'];
		$cityname=M('citys')->field('cityname')->where("id=$cityid")->find()['cityname'];
		//找驾校
		$schools=M("schooltrain t")->field("s.userid,s.nickname")->join("xueche1_school s on s.userid=t.schoolid and t.trainid=$trainid")->select();
		//找教练
		foreach($schools as $k=>$v){
			$schoolid=$v['userid'];
			$schools[$k]['coachs']=M("coachtrain t")->field("c.userid,c.nickname")->join("xueche1_coach c on c.userid=t.coachid and t.trainid=$trainid and schoolid='$schoolid'")->select();
		}
		$this->assign("schools",$schools);
		$this->assign("p",I('get.p'));
		$this->assign("cityname",$cityname);
		$this->assign("cityid",$cityid);
		$this->assign("trainid",$trainid);
		$this->assign("trname",$trname);
		$this->display();
   }
   //在一个基地里面删除一个驾校并删除其所有的教练
   public function deltrainschool(){
		$trainid=I('get.trainid');
		$schoolid=I('get.schoolid');
		$p=I('get.p');
		$cityid=I('get.cityid');
		M("schooltrain")->where("trainid=$trainid and schoolid='$schoolid'")->delete();
		M("coachtrain")->where("trainid=$trainid and schoolid='$schoolid'")->delete();
		$this->redirect("coachstrain?cityid={$cityid}&trainid={$trainid}&p={$p}");
   }
  /* public function a{
		$a=1;
		$this->assign("a",$a);
		$this->display();
   }*/
}