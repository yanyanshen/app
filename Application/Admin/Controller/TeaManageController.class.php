<?php
namespace Admin\Controller;

use Think\Controller;

class TeaManageController extends CommonController
{
    /**
     * 教学管理
     */
   public function teaManage($type='jl',$userid='A2194076-B223-BBEB-4B33-0D59C8CAD108'){
        try {     
    		 $table=shenfen($type);
    		  //学员人数
    		 $coach['stucount']=M("student")->where("masterid='$userid'")->count();
    		 $coach['timeflag']=M($table)->field("timeflag,jtype")->where("userid='$userid'")->find();
    		 //订单数
    		 $coach['list']=M("list")->where("coach='$userid'")->count();
    		 if($type=='jl'){
    		     //地标管理
    		     $coach['land']=M("coachland c")->field("l.id,l.landname")->join("xueche1_landmark l on c.landmarkid=l.id and c.coachid='$userid'")->count();
    		         $coach['coachcount']=M("coach")->field("userid")->where("boss='$userid'")->count();
    		         $coachs=M("coach")->field("userid")->where("boss='$userid'")->select();
    		         //加上小打工的教练的学员人数和订单数
    		         foreach ($coachs as $k=>$v){
    		             $uid=$v['userid'];
    		             $coach['stucount']+=M("student")->where("masterid='$uid'")->count();
    		             $coach['list']+=M("list")->where("coach='$uid'")->count();
    		         }
    		 }else if($type=='jx'){
    		     //基地管理
    		     $coach['train']=M("schooltrain")->where("schoolid='$userid'")->count();
    		     //地标管理
    		     $coach['land']=M("schoolland")->where("schoolid='$userid'")->count();
    		     //教练人数
    		     $coach['coachcount']=M("coach")->field("userid")->where("masterid='$userid'")->count();
    		     $coachs=M("coach")->field("userid")->where("masterid='$userid'")->select();
    		     //加上小打工的教练的学员人数和订单数
    		     foreach ($coachs as $k=>$v){
    		         $uid=$v['userid'];
    		         $coach['stucount']+=M("student")->where("masterid='$uid'")->count();
    		         $coach['list']+=M("list")->where("coach='$uid'")->count();
    		     }
    		 }else{
    		     //地标管理
    		     $coach['land']=M("guiderland")->where("guiderid='$userid'")->count();
    		 }
    		 $info=result(0, $coach);
    	} catch (Exception $e) {
    		$info=type(1,"返回异常");
    	}
    	echo $info;
    }
 //驾校的教学管理返回教练详情
    public function returnMycoach($userid,$type,$start){
        try {
            if($type=='jl'){
                $coach=returnCoach($userid,"boss",$start,3);
            }else{
                $coach=returnCoach($userid,"masterid",$start,0);
            }
            $info=result(0, $coach);
        } catch (Exception $e) {
            $info=type(1,"返回异常");
        }
        echo $info;
    }
    //驾校(教练团队)的教学管理返回教练详情
    public function returnCoachinfo(){
        try {
            $userid=I('post.userid');
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
            $coach['train']=M("coachtrain c")->field("t.trname")->join("xueche1_train t on c.trainid=t.id and c.coachid='$userid'")->find()['trname'];
            $info=result(0, $coach);
        } catch (Exception $e) {
            $info=type(1,"返回异常");
        }
        echo $info;
    }
    //删除教练（将该教练的masterid/boss置为空）
    public function updateMaster($objectid,$master){
        try {
            if(M("coach")->where("userid='$objectid'")->setField($master,'')){
				 if($master=='masterid'){
                    M("coachtrain")->where("coachid='$objectid'")->setField("schoolid",'');
                }else{
                    M("coachtrain")->where("coachid='$objectid'")->setField("boss",'');
                }
                echo type(0,"删除成功");
            }else{
                echo type(1,"删除失败");
            }
        } catch (Exception $e) {
            echo type(2,"删除失败");
        }
    }
    //驾校或者私人团队添加自己的教练
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
                $t=M('coachtrain')->field($f2)->where("coachid='$userid'")->find();
                if($c[$f1]!=''){
                    echo type(1,"该教练已经有所属驾校或教练");
                }else{
                    M("coach")->where("account='$phone'")->save($coach);
                    if($t){
                        M("coachtrain")->where("coachid='$userid'")->save($coach);
                    }else{
                        $coach['coachid']=$userid;
                        M("coachtrain")->add($coach);
                    }
                    echo result(0,$_POST);
                }
            }else{
                $_POST['userid']=$_POST['coachid']=guid();
                $_POST['pass']=C('coachpass');
                $_POST['account']=$phone;
		$_POST['ntime']=date('Y-m-d H:i:s');
                $_POST['birthday']=date('Y-m-d');
               if(M("coach")->add($_POST) && M("coachtrain")->add($_POST)){
			echo result(0,$_POST);
		}
            }
        } catch (Exception $e) {
            echo type(2,"添加失败");
        } 
    }
	//返回一个驾校所有的基地
    public function returnTrain($userid){
        try {
            $where="xueche1_train t on t.id=s.trainid ";
            if(!empty($userid)){
               $where.=" and s.schoolid='$userid'";
            }
            $train=M("schooltrain s")->field("t.id,t.trname")->join($where)->select();
            echo result(0,$train);
        } catch (Exception $e) {
            echo type(1,"返回失败");
        }
    }
    //----------------------------------------------8.26---------------------
    //地标管理
    public function landManage($userid,$type,$city){
        try {
            $cityid=M("citys")->field("id")->where("cityname like '%$city%'")->find()['id'];
            if($type=='jx'){
                $table='xueche1_schoolland';
                $schoolid='s.schoolid';
            }elseif($type=='jl'){
                $table='xueche1_coachland';
                $schoolid="s.coachid";
            }else{
                $table='xueche1_guiderland';
                $schoolid="s.guiderid";
            }
            //找地标及名字
            $county=M("countys")->field("id,countyname")->where("masterid=$cityid")->select();
            foreach($county as $k=>$v){
                $countyid=$v['id'];
                $county[$k]['land']=M("landmark l")->field("l.id,s.id as sid,l.landname")->join("$table s on s.landmarkid=l.id and l.masterid=$countyid and $schoolid='$userid'")->select();
            }
            echo result(0, $county);
        } catch (Exception $e) {
            echo type(1, "返回失败");
        }
    }
    //删除驾校/教练地标
    public function delLand($userid,$type,$landid,$city){
        try {
            if($type=='jx'){
                $table='schoolland';
                $schoolid="schoolid";
                $land="xueche1_schoolland";
            }else if($type=='jl'){
                $table='coachland';
                $schoolid="coachid";
                $land="xueche1_coachland";
            }else{
                $table='guiderland';
                $schoolid="guiderid";
                $land="xueche1_guiderland";
            }
            if(M($table)->delete($landid)){
                $cityid=M("citys")->field("id")->where("cityname like '%$city%'")->find()['id'];
                //找地标及名字
                $county=M("countys")->field("id,countyname")->where("masterid=$cityid")->select();
                foreach($county as $k=>$v){
                    $countyid=$v['id'];
                    $county[$k]['land']=M("landmark l")->field("s.id,l.landname")->join("$land s on s.landmarkid=l.id and l.masterid=$countyid and $schoolid='$userid'")->select();
                }
                echo result(0, $county);
            }else{
                echo type(1, "删除失败");
            }
        } catch (Exception $e) {
            echo type(2, "删除失败");
        }
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
    //添加地标
    public function addLand(){
        try {
            $userid=I("post.userid");
            $type=I("post.type");unset($_POST['session_id']);
            unset($_POST['userid']);unset($_POST['type']);
            if($type=='jx'){
                $table="schoolland";
                $obj="schoolid";
            }elseif($type=='jl'){
                $table="coachland";
                $obj="coachid";
            }else{
	    	$table="guiderland";
                $obj="guiderid";
	    }
            foreach($_POST as $k=>$v){
                  $post[$k]['landmarkid']=$v;
                  $post[$k][$obj]=$userid;
            }
            if(M($table)->addAll($post)){
                $info=type(0, "添加成功");
            }else{
                $info=type(1, "添加失败");
            }
        } catch (Exception $e) {
            $info=type(2, "添加失败");
        }
        echo $info;
    }
    //基地管理
    public function trainManage($userid){
        try {
            $train=M("schooltrain s")->field("t.id,t.trname,t.address")->join("xueche1_train t on t.id=s.trainid and s.schoolid='$userid'")->select();
            foreach($train as $k=>$v){
                $tid=$v['id'];
                $train[$k]['coachcount']+=M('coachtrain')->where("schoolid='$userid' and trainid=$tid")->count();
            }
            $info=result(0,$train);
        } catch (Exception $e) {
            $info=type(1, "返回失败");
        }
        echo $info;
    }
    //删除基地
    public function delTrain(){
        try {
            $userid=I("post.userid");
            $trainid=I('post.trainid');
            if(M("schooltrain")->where("schoolid='$userid' and trainid=$trainid")->delete()){
                $info=type(0, "删除成功");
            }else{
                $info=type(1, "删除失败");
            }
        } catch (Exception $e) {
            $info=type(2, "删除失败");
        }
        echo $info;
    }
    //添加基地
    public function addTrain($schoolid,$trainid){
        try {
            if(M('schooltrain')->where("schoolid='$schoolid' and trainid='$trainid'")->find()){
                echo type(0, "该基地已存在");return;
            }
            if(M("schooltrain")->add($_POST)){
                $info=type(0, "添加成功");
            }else{
                $info=type(1, "添加失败");
            }
        } catch (Exception $e) {
            $info=type(2, "添加失败");
        }
        echo $info;
    }
    //返回基地列表
    public function returnTrainCoach($trainid,$userid){
        try {
            //教练列表
            $coach=M("coachtrain")->field("coachid")->where("schoolid='$userid' and trainid=$trainid")->select();
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
    //--------------------9.5
    //修改  基地
    //Pay/TeaManage/updateCoachtrain
    public function updateCoachTrain($userid,$objectid,$trainid,$master){
        try {
            //$master(驾校的话传   masterid 教练的话传boss)
            if(M("coachtrain")->where("coachid='$objectid' and $master='$userid'")->setField("trainid",$trainid)){
                $info=type(0, "修改成功");
            }else{
                $info=type(1, "修改失败");
            }
        } catch (Exception $e) {
            $info=type(2, "修改失败");
        }
        echo $info;
    }
    //--------------------------------------------------9.20
    //返回学员信息
    //返回学员信息
     public function returnCoachStu($masterid,$start=0,$type){
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
     //修改驾照类型 Pay/TeaManage/updateJtype         session_id,userid,type,jtype     返回成功或失败
     public function  updateJtype($userid,$type,$jtype){
         try {
             $table=shenfen($type);
             if(M($table)->where("userid='$userid'")->setField("jtype",$jtype)){
                 $info=type(0, "修改成功");
             }else{
                 $info=type(1, "修改失败");
             }
         } catch (Exception $e) {
             $info=type(2, "修改失败");
         }
         echo $info;
     }
	 public function newlandManage($userid,$type,$city){
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
            $land=explode(',',rtrim($land,',')) ;
            foreach ($county as $k=>$v){
                foreach ($land as $kk=>$vv){
                    $c[$kk]=M('landmark')->field("id,landname,masterid")->where("id=$vv")->find();
                    if($v['id']==$c[$kk]['masterid']){
                        $county[$k]['land'][$kk]=$c[$kk];
                    }
                }
                if(!isset($county[$k]['land'])){
                    $county[$k]['land']=[];
                }
            }
           echo result(0, $county);
        } catch (Exception $e) {
            echo type(1, "返回失败");
        }
    }
	  //添加,删除地标
    public function newLand($userid,$type,$landid){
        try {
            if($type=='jx'){
                $table="schoolland";
                $obj="schoolid";
            }else{
                $table="coachland";
                $obj="coachid";
            }
            if(M($table)->where("$obj='$userid'")->setField("landmarkid",$landid)){
                $info=type(0, "成功");
            }else{
                $info=type(1, "失败");
            }
        } catch (Exception $e) {
            $info=type(2, "失败");
        }
        echo $info;
    }   
}


