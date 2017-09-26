<?php
namespace Admin\Controller;

use Think\Controller;

class TeaManage1Controller extends CommonController
{
    
   //返回一个驾校所有的基地
   public function returnTrain($userid='D18CF959-EDB7-B2C2-C8D0-85CFB1415721'){
       try {
           $train=M('schooltrains')->field("trainid")->where("schoolid='$userid'")->find()['trainid'];
	   if($train){
		$train=explode(',',rtrim($train,','));
         	foreach ($train as $k=>$v){
               		$train[$k]=M('train')->field("id,trname")->where("id=$v")->find();
           	}
	   }else{
		$train=[];
	   }
           echo result(0,$train);
       } catch (Exception $e) {
           echo type(1,"返回失败");
       }
   }

   
   /**
    * 教学管理
    */
   public function teaManage($type='jl',$userid='A2194076-B223-BBEB-4B33-0D59C8CAD108'){
   	try {
	   //$_POST['jltype']=2;
           $table=shenfen($type);
       		  //学员人数
           $coach['stucount']=M("student")->where("masterid='$userid' and Cl_type='y'")->count();
           $coach['timeflag']=M($table)->field("timeflag,jtype")->where("userid='$userid'")->find();
           //订单数
           $coach['list']=M("list")->where("objectid='$userid' and Cl_type='y'")->count();
           if($type=='jl'){
               //地标管理
               //$coach['land']=M("coachland c")->field("l.id,l.landname")->join("xueche1_landmark l on c.landmarkid=l.id and c.coachid='$userid'")->count();
               $land=M("coachlands")->field('landmarkid')->where("coachid='$userid'")->find()['landmarkid'];
               $coach['land']=substr_count($land,',');
               if($_POST['jltype']==2){
                   $coach['coachcount']=M("coach")->field("userid")->where("boss='$userid'")->count();
                   $coachs=M("coach")->field("userid")->where("boss='$userid'")->select();
                   //加上小打工的教练的学员人数和订单数
                   foreach ($coachs as $k=>$v){
                       $uid=$v['userid'];
                       $coach['stucount']+=M("student")->where("masterid='$uid' and Cl_type='y'  and subjects !=5")->count();
                       $coach['list']+=M("list")->where("objectid='$uid' and Cl_type='y'")->count();
                   }
               }
           }else if($type=='jx'){
               //基地管理
               $train=M("schooltrains")->field('trainid')->where("schoolid='$userid'")->find()['trainid'];
               $coach['train']=substr_count($train,',');
               //地标管理
               $land=M("schoollands")->field('landmarkid')->where("schoolid='$userid'")->find()['landmarkid'];
               $coach['land']=substr_count($land,',');
               //教练人数
               $coach['coachcount']=M("coach")->field("userid")->where("masterid='$userid'")->count();
               $coachs=M("coach")->field("userid")->where("masterid='$userid'")->select();
               //加上小打工的教练的学员人数和订单数
               foreach ($coachs as $k=>$v){
                   $uid=$v['userid'];
                   $coach['list']+=M("list")->where("objectid='$uid'")->count();
               }
             }else{
               $land=M("guiderlands")->field('landmarkid')->where("guiderid='$userid'")->find()['landmarkid'];
               $coach['land']=substr_count($land,',');
           }
           $info=result(0, $coach);
       } catch (Exception $e) {
           $info=type(1,"返回异常");
       }
       echo $info;
   } 
   //驾校的教学管理返回教练详情
   public function returnMycoach($userid="A2194076-B223-BBEB-4B33-0D59C8CAD108",$type='jl',$start=0){
       try {
           if($type=='jl'){
               $coach=returnCoach1($userid,"boss",$start,3);
           }else{
               $coach=returnCoach1($userid,"masterid",$start,0);
           }
           $info=result(0, $coach);
       } catch (Exception $e) {
           $info=type(1,"返回异常");
       }
       echo $info;
   }
   //驾校(教练团队)的教学管理返回教练详情
   public function returnCoachinfo($userid='4F35E74E-38DE-EC47-A0C9-9E18303ACDBB'){
       try {
           $coach=M("coach")->field("userid,img,nickname,jtype,timeflag")->where("userid='$userid'")->find();
           //加上小打工的教练的学员人数和订单数
           //当前所教学员
           $coach['nowstu']=M("student")->where("masterid='$userid' and subjects !=5")->count();
           //总学员
           $coach['stucount']=M("student")->where("masterid='$userid'")->count();
           //当月订单
           //先求出月份
           $month=date("Y-m");
           $coach['monthlist']=M("list")->where("objectid='$userid' and listtime like '%$month%'")->count();
           //总得订单
           $coach['listcount']=M("list")->where("objectid='$userid'")->count();
           //所在基地
           $trainid=M("coachtrains")->field("trainid")->where("coachid='$userid'")->find()['trainid'];
	   if($trainid){
		$train=rtrim($trainid,',');
		$coach['train']=M('train')->field("trname")->where("id=$train")->find()['trname'];
	   }else{
	  	$coach['train']=null;
	   }
           $info=result(0, $coach);
       } catch (Exception $e) {
           $info=type(1,"返回异常");
       }
       echo $info;
   }
   
   public function addCoach($phone){
       try {
           if(isset($_POST['schoolid'])){
               $f2='schoolid';
               $coach['schoolid']=$_POST['schoolid'];
               $f1='masterid';
           }else{
               $coach['boss']=$_POST['boss'];
               $f1='boss';
               $f2='boss';
           }
           $coach['masterid']=$_POST['masterid'];
           $coach['trainid']=$_POST['trainid'];
           $c=M('coach')->field("$f1,userid")->where("account='$phone'")->find();
           if($c){
               $userid=$c['userid'];
               $t=M('coachtrains')->where("coachid='$userid'")->find();
               if($c['masterid']!=''){
                   echo type(1,"该教练已经有所属驾校");
               }else{
                   M("coach")->where("account='$phone'")->save($coach);
                   $coach['trainid'].',';
                   if($t){
                       M("coachtrain")->where("account='$phone'")->save($coach);
                   }else{
                       $coach['coachid']=$userid;
                       M("coachtrain")->add($coach);
                   }
                   echo result(0,$_POST);
               }
           }else{
               $_POST['userid']=$_POST['coachid']=guid();
               $_POST['pass']=C('coachpass');
               $_POST['ntime']=date('Y-m-d H:i:s');
               $_POST['birthday']=date('Y-m-d');
               $_POST['account']=$phone;
               M("coach")->add($_POST);
               $_POST['trainid']=$_POST['trainid'].',';
               M("coachtrains")->add($_POST);
               echo result(0,$_POST);
           }
       } catch (Exception $e) {
           echo type(2,"添加失败");
       }
   }
   public function landManage($userid="D18CF959-EDB7-B2C2-C8D0-85CFB1415721",$type='jx',$city='上海'){
       try {
           $cityid=M("citys")->field("id")->where("cityname like '%$city%'")->find()['id'];
           if($type=='jx'){
               $table='schoollands';
               $field="schoolid";
           }elseif($type=='jl'){
               $table='coachlands';
               $field="coachid";
           }else{
               $table='guiderlands';
               $field="guiderid";
           }
           //找地标及名字
           $county=M("countys")->field("id,countyname")->where("masterid=$cityid")->select();
           $land=M($table)->field("landmarkid")->where("$field='$userid'")->find()['landmarkid'];
	   if($land){
		$land=explode(',',rtrim($land,',')) ;
               foreach ($county as $k=>$v){
                   foreach ($land as $kk=>$vv){
                       $c[$kk]=M('landmark')->field("id,landname,masterid")->where("id=$vv")->find();
                       if($v['id']==$c[$kk]['masterid']){
                           $county[$k]['land'][]=$c[$kk];
                       }
                   }
                   if(!isset($county[$k]['land'])){
                       $county[$k]['land']=[];
                   }
               }
           }else{
               foreach ($county as $k=>$v){
                   $county[$k]['land']=[];
               }
           }
	   echo result(0, $county);
       } catch (Exception $e) {
           echo type(1, "返回失败");
       }
   }
   //添加,删除地标
   public function operLand($userid,$type,$landid){
       try {
           if($type=='jx'){
               $table="schoollands";
               $obj="schoolid";
           }elseif($type=='jl'){
               $table="coachlands";
               $obj="coachid";
           }else{
               $table="guiderlands";
               $obj="guiderid";
           }
           if(M($table)->where("$obj='$userid'")->find()){
               if(M($table)->where("$obj='$userid'")->setField("landmarkid",$landid)){
                   $info=type(0, "成功");
               }else{
                   $info=type(1, "更新失败");
               }
           }else{
               $land[$obj]=$userid;
               $land['landmarkid']=$landid;
               if(M($table)->add($land)){
                   $info=type(0, "成功");
               }else{
                   $info=type(1, "添加失败");
               }
           }
	} catch (Exception $e) {
           $info=type(2, "失败");
       }
       echo $info;
   }
   //根据区返回地标
   public function returnLand(){
       try {
           $countyid=I("post.countyid");
           $land=M("landmark")->field("id,landname")->where("masterid=$countyid")->select();
           $info=result(0,$land);
       } catch (Exception $e) {
           $info=type(1, "删除失败");
       }
       echo $info;
   }
   //删除教练（将该教练的masterid/boss置为空）
   public function updateMaster($objectid='5C33E1F9-665E-9C7B-B761-AA37A4F5E297',$master){
       try {
           if(M("coach")->where("userid='$objectid'")->setField($master,'')){
                   M("coachtrains")->where("coachid='$objectid'")->delete();
               echo type(0,"删除成功");
           }else{
               echo type(1,"删除失败");
           }
       } catch (Exception $e) {
           echo type(2,"删除失败");
       }
   }
   //基地管理
	 public function trainManage($userid='5196C331_1DA3_E56C_DAC1_5EF8655FF96B'){
       try {
           $train=M("schooltrains")->field("trainid")->where("schoolid='$userid'")->find()['trainid'];
           if($train){
               $train=explode(',', rtrim($train,','));
               foreach($train as $k=>$v){
                   $train[$k]=M('train')->field("id,trname,address")->where("id=$v")->find();
                   //$train[$k]['coachcount']+=M('coachtrains')->where("schoolid='$userid' and locate($v,trainid)")->count();
                   $train[$k]['coachcount']+=M('coach')->where("masterid='$userid' and jltype=0 and trainid=$v")->count();
               }
           }else{
	   	$train=null;
	   }
           $info=result(0,$train);
       } catch (Exception $e) {
           $info=type(1, "返回失败");
       }
       echo $info;
   }   
//删除添加一个借口(只有驾校有)
  //删除添加一个借口(只有驾校有)
   public function operTrain($userid='5196C331_1DA3_E56C_DAC1_5EF8655FF96B',$trainid='1,79,'){
       try {
           $train=M("schooltrains")->field('id')->where("schoolid='$userid'")->find();
           if($train){
               $train=M("schooltrains")->where("schoolid='$userid'")->setField('trainid',$trainid);
               if($train){
                   $info=type(0, "成功");
               }else{
                   $info=type(1, "失败");
               }
           }else{
               $trains['schoolid']=$userid;
               $trains['trainid']=$trainid;
               $train=M("schooltrains")->add($trains);
               if($train){
                   $info=type(0, "成功");
               }else{
                   $info=type(1, "失败");
               }
           }
           
       } catch (Exception $e) {
           $info=type(2, "删除失败");
       }
       echo $info;
   } 
   
   //返回基地列表
   public function returnTrainCoach($trainid=14,$userid='5196C331-1DA3-E56C-DAC1-5EF8655FF96B'){
       try {
           //教练列表
           $coach=M("coachtrains")->field("coachid")->where("schoolid='$userid' and locate($trainid,trainid)")->select();
           foreach($coach as $k=>$v){
               $uid=$v['coachid'];
               $coach[$k]['coach']=M("coach")->field("nickname,img")->where("userid='$uid'")->find();
               $coach[$k]['stucount']=M("student")->where("masterid='$uid'")->count();
               $coach[$k]['listcount']=M("list")->where("objectid='$uid'")->count();
           }
           $info=result(0,$coach);
       } catch (Exception $e) {
           $info=type(1, "返回失败");
       }
       echo $info;
   }
   //给教练修改基地,传修改之后的所拥有的基地
   	 public function updateCoachTrain($userid,$objectid,$trainid,$master){
       try {
           //$master(驾校的话传   masterid 教练的话传boss)
           $tr=M("coachtrains")->field('id')->where("coachid='$objectid' and $master='$userid'")->find();
           if($tr){
               $train=M("coachtrains")->where("coachid='$objectid' and $master='$userid'")->setField("trainid",$trainid);
               if($train){
                   $info=type(0, "修改成功");
               }else{
                   $info=type(1, "修改失败");
               }  
           }else{
               $t['coachid']=$objectid;
               $t['trainid']=$trainid;
               $t[$master]=$userid;
               $train=M("coachtrains")->add($t);
               if($train){
                   $info=type(0, "修改成功");
               }else{
                   $info=type(1, "修改失败");
               } 
           }
       } catch (Exception $e) {
           $info=type(2, "修改失败");
       }
       echo $info;
   }
	//返回教练的学员
   public function returnCoachStu($masterid='4ED02DF4-D6EF-150C-5046-08011F054349',$start=0,$type){
       try {
           if($type=='jx'|| $type=='sjl'){
               $where="masterid";
           }else{
               $where="coachid";
           }
           $date=M("student s")->field("u.img,u.nickname,s.note,s.paytype")->join("xueche1_user u on s.userid=u.userid and s.$where='$masterid'")->limit("$start,10")->select();
           $info = result(0, $date);
       } catch (Exception $e) {
           $info=type(1, "返回失败");
       }
       echo $info;
   }
   
   public function  updateJtype($userid,$type,$jtype){
       try {
           $result1=M("coach")->where("userid='$userid'")->setField("jtype",$jtype);
           $result2=M("coachtrains")->where("coachid='$userid'")->setField("jtype",$jtype);
           if($result1 && $result2){
               $info=type(0, "修改成功");
           }else{
               $info=type(1, "修改失败");
           }
       } catch (Exception $e) {
           $info=type(2, "修改失败");
       }
       echo $info;
   }
}


