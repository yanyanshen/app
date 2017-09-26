<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Upload;
class CoachController extends CommonController
{
    public function upcertificate($type,$userid,$city)
    {
        try {
            if(empty($_FILES)){
                echo type(4,"图片为空");return;
            }
            foreach($_FILES as $k=>$v){
                $filename=pathinfo($v['name'],PATHINFO_FILENAME);
                $push=strtolower(pathinfo($v['name'],PATHINFO_EXTENSION));
                $dir=C('conf.coach').$filename;
                $en=range('a','z');
                $newname=time().mt_rand(-9,9).$en[mt_rand(0,25)].'.'.$push;
                if(move_uploaded_file($v['tmp_name'],$dir.'/'.$newname)){
                    $img[$k]['imgurl']=$dir.'/'.$newname;
                    $img[$k]['userid']=$_POST['userid'];
                    $img[$k]['imgtime']=date('Y-m-d H:i:s');
                    $img[$k]['paperstype']=$filename;
                    M('papersimg')->add($img[$k]);
                }else{
                    echo type(3,"上传失败");return;
                }
            }
        } catch (Exception $e) {
            echo type(2,"上传失败");return;
        }
        $_POST['cityid']=M("citys")->field("id")->where("cityname like '%$city%'")->find()['id'];
        $table=shenfen($type);
        M($table)->where("userid='$userid'")->save($_POST);
        if($type=='jl'){
            $trainid=$_POST['trainid'];
            if(M("coachtrain")->where("coachid='$userid'")->find()){
                M("coachtrain")->where("coachid='$userid'")->setField("trainid",$trainid);
            }else{
                M("coachtrain")->add($_POST);
            }
        }
        echo type(0,"上传成功");
    }
    public function getpublicList($start,$type,$latitude,$longitude)
    {
            $address = $_POST['address'];
			$cityid=M("citys")->field("id")->where("cityname like '%$address%'")->find()['id'];
			switch($type){
				case 'jx':
						getSchoolList($start,$cityid,$latitude,$longitude);
					break;
				case 'jl':
						getCoachList($start,$cityid,$latitude,$longitude);
					break;
				case 'zdy':
						getGuiderList($start,$cityid,$latitude,$longitude);
					break;
			}
    }
    public function getAllList($objectid,$start,$type)
    {
		if($type=='jl'){
			$count=M("evaluating")->where("objecthingid='$objectid'")->count();
			if($count==0){
				$objectid=M("coach")->field("masterid")->where("userid='$objectid'")->find()['masterid'];
			}
		}
		$mm = M('evaluating')->field("id,time,masterid,objectid,content,score,num")
			->where("objecthingid='$objectid'")
			->limit("$start,10")
			->order('id desc')
			->select();
		foreach ($mm as $k => $v) {
			$uid = $v['masterid'];
			$mm[$k]['user'] = M("user")->field("nickname,img")
				->where("userid='$uid'")
				->find();
		}
		$info = result(0,$mm);
        echo $info;
    }
    public function allconList($type,$objectid,$start)
    {
        $m = M('consult');
        if ($m->where("objectid='$objectid'")->find()) {
            $mm =  $m->field("id,time,masterid,objectid,content,replycount")
                ->where("objectid='$objectid'")
                ->limit("$start-1,10")
                ->order('id desc')
                ->select();
            foreach ($mm as $k =>$v){
                $uid = $v['masterid'];
                $mm[$k]['nickname'] = M("user")->field("nickname")
                    ->where("userid='$uid'")
                    ->find()['nickname'];
            }
            $info = result(0, $mm);
        } else {
            $info = type(1, "还没有任何提问", - 1);
        }
        echo $info;
    }
    public function addconsult($masterid,$objectid,$content)
    {
        $_POST['time'] = date("Y-m-d H:i:s");
        if (M('consult')->add($_POST)) {
            $cinfo = M("consult c")->field("c.id,c.time,c.masterid,c.objectid,c.content,u.nickname,u.img")
                ->join("xueche1_user u on u.userid=c.masterid and c.objectid='$objectid'")
                ->order("id desc")
                ->limit(2)
                ->select();
            $info = result(0, $cinfo);
        }
        echo $info;
    }
    public function coachupload()
    {
        $userid = $_POST["userid"];
        $img = new Upload();
        switch ($_POST['type']) {
            case 'jx':
                $table = "school";
                $c = C('conf.schoolupimg');
                break;
            case 'jl':
                $table = "coach";
                $c = C('conf.coachupimg');
                break;
            case 'zdy':
                $table = "guider";
                $c = C('conf.guiderupimg');
                break;
        }
        $mm = $img->dirup($c);
        foreach ($mm as $k => $v) {
            $mm[$k]['userid'] = $userid;
            $mm[$k]['imgtime'] = date("Y-m-d H:i:s");
        }
        $c = count($mm);
        if (M('img')->addAll($mm) && M($table)->where("userid='$userid'")->setInc("piccount", $c)) {
            $img=M('img')->field("id,imgname")->where("userid='$userid'")->select();
            foreach($img as $k=>$v){
                $img[$k]['imgname']=C("conf.ip").$v['imgname'];
            }
            $info =result(0,$img);
        } else {
            $info =  type(1, "上传失败，请检查", - 1);
        }
        echo $info;
    }
	//--------------------------------------------------9.7
	public function getTeacherDetail($objectid,$userid,$type){
        switch ($type) {
            case "jx":
                $table = "school u";
                $field = "id,userid,nickname,img,piccount,phone,address,pickrange,score,introduction,allcount,passedcount,praisecount,price,hotflag,recommendflag,certificateflag,pickflag,timeflag,piccount,trainaddress,verify";
                break;
            case "jl":
                $table = "coach u";
                $field = "id,userid,masterid,nickname,img,address,sex,phone,birthday,masterid,piccount,introduction,teachedage,score,passedcount,driverage,allcount,jtype,praisecount,certificateflag,timeflag,teachablecount,verify,jltype,boss";
                echo returnCoachDetail($userid, $objectid, $field);return;
                break;
            case "zdy":
                $table = "guider u";
                $field = "id,userid,nickname,img,address,sex,birthday,phone,piccount,teachedage,introduction,score,passedcount,allcount,driverage,praisecount,certificateflag,timeflag,teachablecount,cost,verify";
                break;
        }
        $m = M($table);
        $mm = $m->field($field)
        ->where("userid='$objectid'")
        ->find();
		$mm['evalutioncount'] = M('evaluating')->where("objecthingid='$objectid'")->count();
        $mm['introduction']=strip_tags($mm['introduction']);
        $p1 = M("user u")->field("u.nickname,u.img,e.id,e.time,e.masterid,e.objectid,e.content,e.score,e.num")
        ->join("xueche1_evaluating e on e.masterid=u.userid and e.objecthingid='$objectid'")->order('e.id desc')->limit(2)->select();
        $p2 =M("evaluating")->field("id,masterid,time,masterid,content,score")->where("masterid='1' and objecthingid='$objectid'")->order('id desc')->select();
        $mm['message'] = array_slice(array_merge($p1,$p2),0,2);
        $mm['consultcount'] = M('consult')->where("objectid='$objectid'")->count();
        $pp = $m->field("c.id,c.time,c.masterid,c.objectid,c.content,c.replycount")
        ->join("xueche1_consult c on u.userid=c.objectid and u.userid='$objectid'")
        ->order('id desc')
        ->limit(2)
        ->select();
        foreach ($pp as $k => $v){
            $uid = $v['masterid'];
            $pp[$k]['nickname'] =M('user')->field("nickname")->where("userid='$uid'")->find()['nickname'];
            $pp[$k]['img'] =M('user')->field("img")->where("userid='$uid'")->find()['img'];
        }
        $mm['consult'] = $pp;
        $img = M('img')->where("userid='$objectid'")->count();
	if($img>0){
		$imgname1 = M('img')->field("imgname ")
        	->where("userid='$objectid'")//先写死
        	->order('id desc')
        	->select();
	}else{
        	$imgname1 = M('img')->field("imgname ")
      		  ->where("userid='D18CF959-EDB7-B2C2-C8D0-85CFB1415721'")//先写死
        	->order('id desc')
        	->select();
	}
        foreach($imgname1 as $k=>$v){
            $mm['imgurl'][$k]= C("conf.ip").$imgname1[$k]['imgname'];
        }
        $mm['classcount']=M('trainclass')->where("masterid='$objectid'")->count();
        $trainclass=M("trainclass")->field("tcid,name,include,masterid,time,carname,mode,officialprice,whole517price,prepay517price,prepay517deposit,classtime")->where("masterid='$objectid'")->select();
        $mm['class'] = $trainclass;
        if(M('attention')->where("userid='$userid' and objectid='$objectid'")->count()==1){
            $mm['conflag']=1;
        }else{
            $mm['conflag']=0;
        }
        $info = result(0, $mm);
        echo $info;
    }

	//教练端添加修改简介
	public function uploadProfile(){
		try {
			$userid=I('post.userid');
			$timeflag=I('post.timeflag');
			$table= shenfen($_POST['type']);
			if(M($table)->where("userid='$userid'")->setField("introduction",$_POST['introduction'])){
				echo  type(0, "修改成功");
			}else{
				echo  type(1, "修改失败");
			}
		 } catch (Exception $e) {
			echo  type(2, "修改失败");
		 }
	}
	//教练端添加修改是否支持即使培训
	public function uploadTimeflag(){
	    try {
	        $userid=I('post.userid');
	        $table= shenfen($_POST['type']);
	        if(M($table)->where("userid='$userid'")->setField('timeflag',I('post.timeflag'))){
	            echo  type(0, "修改成功");
	        }else{
	            echo  type(1, "修改失败");
	        }
	    } catch (Exception $e) {
	        echo  type(2, "修改失败");
	    }
	}
	//添加课程
	public function addClass(){
		 try {
			if(empty($_POST)){
		         echo  type(3, "添加为空");
		         return;
		     }
		     $masterid=I('post.masterid');
		     $_POST['time']=date("Y-m-d H:i:s");
		     $_POST['tcid']=M("trainclass")->field("id")->order("id desc")->find()['id']+1;
            if(M("trainclass")->add($_POST)){
                $class=M("trainclass")->field("tcid,name,carname,include,picktype,traintype,officialprice,whole517price,prepay517price,jtype,prepay517deposit,classtime,objectsecondcount,linetime,objectthirdcount")->where("masterid='$masterid'")->order('id desc')->find();
                $info=result(0, $class);
                echo $info;
			}else{
				echo  type(1, "添加失败");
			}
        } catch (Exception $e) {
			   echo  type(2,"添加失败");
        }
        
	}
	//---------------------------------------------------8.17---------------------------------------------------------
	//返回已报名学员
 public function returnallstu($masterid='A25BF70C-D5FC-2956-5760-06611F40EBC7',$start=0,$subjects=1,$type='jl'){
      try {
	  $wheres="s.Cl_type='y'";
          if($type=='jx' || $type=='sjl'){
              if($type=='jx'){
                  $where='masterid';
              }else{
                  $where='boss';
              }
              $stu=M("student s")->field("u.img,u.userid,s.truename,s.masterid,s.note,s.phone,s.address,s.pickupaddress,s.trainclass,s.subjects,s.paytype")->join("xueche1_user u on s.userid=u.userid and s.masterid='$masterid' and s.subjects=$subjects and $wheres")->limit("$start,10")->select();
             
	 $masterid=M('coach')->field("userid")->where("$where='$masterid'")->select();
              foreach ($masterid as $k=>$v){
                  $cid=$v['userid'];
                  $stu1[$k]=M("student s")->field("u.img,u.userid,s.truename,s.masterid,s.note,s.phone,s.address,s.pickupaddress,s.trainclass,s.subjects,s.paytype")->join("xueche1_user u on s.userid=u.userid and s.masterid='$cid' and s.subjects=$subjects and s.coachid=1 and $wheres")->limit("$start,10")->select();
                  if(empty($stu1[$k])){
                      unset($stu1[$k]);
                  }else{
                      $stu=array_merge($stu,$stu1[$k]);
                  }
              }

           }else{
              $stu=M("student s")->field("u.img,u.userid,s.truename,s.note,s.masterid,s.phone,s.address,s.pickupaddress,s.trainclass,s.subjects,s.paytype")->join("xueche1_user u on s.userid=u.userid and s.masterid='$masterid' and s.subjects=$subjects and $wheres")->limit("$start,10")->select();
          }
          $info= result(0,$stu);
      } catch (Exception $e) {
          $info= type(1,'返回失败');
      }
      echo $info;
   }
    //删除课程（驾校和私人教练）
	public function delClass(){
		 try {
              $tcid=I("post.tcid");
			  if(M("trainclass")->where("tcid=$tcid")->delete()){
				  echo  type(0,"删除成功");
			  }else{
				  echo  type(1,"删除失败");
			  }
            } catch (Exception $e) {
				echo  type(2,"删除失败");
            }
	}
	//删除教学环境
	public function delCoachimg(){
	 try {
           unset($_POST['session_id']);
           $userid=I('post.userid');
           unset($_POST['userid']);
           $i=0;
           foreach ($_POST as $v){
               $url=M("img")->field("imgname")->where("id=$v")->find()['imgname'];
               unlink($url);
               if(M("img")->where("id=$v")->delete()){
                   $i++;
               }
           }
           if($i==count($_POST)){
                $img=M("img")->field("id,imgname")->where("userid='$userid'")->select();
               echo result(0, $img);
           }else{
               echo  type(1,"删除失败");
           }
		} catch (Exception $e) {
			echo  type(2,"删除失败");
		}
	}
	//--------------------------------------------8.19
	//添加学员接口
	public function addStudent(){
	    try {
            $account=$_POST['account'];
		if(issetAccount($account)){
                echo  type(4,'该账号已存在');
                return;
            }
	        if(M('student')->where("account='$account'")->find()){
	            echo  type(3,'该学员已存在');
	            return;
	        }else{
	            $_POST['userid']=guid();
	            $_POST['addstutime']=date("Y-m-d H:i:s");
	            $_POST['stufrom']='r';//---来自教练添加
	            $_POST['Cl_type']='y';//---来自教练添加,默认显示
	            if(M("student")->add($_POST)){
	                 $_POST['nickname']=$_POST['truename'];
	                 M("user")->add($_POST);
	                 $stu=M("student")->field("userid,masterid,truename,phone,address,pickupaddress,trainclass,subjects,note,paytype")->where("account='$account'")->find();
                     $info= result(0,$stu);
	            }else{
	                echo  type(1,'添加失败');
	            }
	        }
	    } catch (Exception $e) {
	        echo  type(2,'添加失败');
	    }
	    echo $info;
	}
	//获取某个老师的课程信息
    public function returnClass($userid,$type){
	    try {
	        if($type=='jl'){
	            $jltype=M("coach")->field("jltype,masterid,boss")->where("userid='$userid'")->find();
	            if($jltype['jltype']==0){
	                $userid=$jltype['masterid'];
	            }elseif($jltype['jltype']==3){
	                $userid=$jltype['boss'];
	            }
	        }
	        $class=M("trainclass")->field("tcid,name,include,carname,picktype,traintype,officialprice,whole517price,prepay517price,jtype,prepay517deposit,classtime,linetime,objectsecondcount,objectthirdcount")->where("masterid='$userid'")->select();
	        $info=result(0, $class);
	    } catch (Exception $e) {
	        $info=type(1,'失败');
	    }
	    echo $info;
	}
	//获取简介和培训课程
	public function getAbstract($userid,$type){
	    try {
	        $table=shenfen($type);
	        $coachinfo=M($table)->field("introduction,timeflag,pickrange")->where("userid='$userid'")->find();
	        $info=result(0, $coachinfo);
	    } catch (Exception $e) {
	        $info=type(1,'添加失败');
	    }
	    echo $info;
	}
	//获取教学环境
    public function getCoachimg(){
        try {
            $userid=I('post.userid');
            $coachinfo=M("img")->field("id,imgname")->where("userid='$userid'")->select();
           foreach($coachinfo as $k=>$v){
                $coachinfo[$k]['imgname']=C("conf.ip").$coachinfo[$k]['imgname'];
            }
	    $info=result(0, $coachinfo);
        } catch (Exception $e) {
            $info=type(1,'添加失败');
        }
        echo $info;
    }
   //修改学员信息
    public function updateStu($objectid,$key,$value){
        try {
            if(M("student")->where("userid='$objectid'")->setField($key,$value)){
                $stu=M("student")->field("userid,masterid,truename,phone,address,pickupaddress,trainclass,subjects,note,paytype")->where("userid='$objectid'")->find();
                $info=result(0, $stu);
            }else{
                $info=type(1,'修改失败');
            }
        } catch (Exception $e) {
            $info=type(2,'修改失败');
        }
        echo $info;
    } 
    //删除学员
    public function delStu(){
        try {
            $objectid=I("post.objectid");
            if(M("student")->where("userid='$objectid'")->delete()){
                $info=type(0,'删除成功');
            }else{
                $info=type(1,'删除成功');
            }
        } catch (Exception $e) {
            $info=type(2,'修改失败');
        }
        echo $info;
    }
	//获得教学环境，培训课程，简介
   public function getCoachs(){
        try {
            $userid=I('post.userid');
            $type=$_POST['type'];
            $table=shenfen($type);
            //如果是指导员的话获取学车成本
            $class['class']=M("trainclass")->field("tcid,name,include,carname,picktype,traintype,officialprice,whole517price,prepay517price,jtype,prepay517deposit,classtime,linetime,objectsecondcount,objectthirdcount")->where("masterid='$userid'")->select();
            if($type=='jl'){
                $jltype=M("coach")->field("jltype,masterid")->where("userid='$userid'")->find();
                if($jltype['jltype']==0 || $jltype['jltype']==3){
                    $masterid=$jltype['masterid'];
                    $class['class']=M("trainclass")->field("tcid,name,include,carname,picktype,traintype,officialprice,whole517price,prepay517price,jtype,prepay517deposit,classtime,objectsecondcount,linetime,objectthirdcount")->where("masterid='$masterid'")->select();
                }
            }
            //获取简介
            $class['introduction']=M($table)->field("pickrange,introduction,timeflag")->where("userid='$userid'")->find();
            //获得环境
            $class['img']=M("img")->field("id,imgname")->where("userid='$userid'")->select();
            foreach ($class['img'] as $k=>$v){
                $class['img'][$k]['imgname']=C("conf.ip").$v['imgname'];
            }
            echo result(0, $class);
        } catch (Exception $e) {
            echo type(1,'返回失败失败');
        }
    }
    //修改学车成本
    public function updateCost(){
        try {
            $userid=I('post.userid');
            if(M("guider")->where("userid='$userid'")->setField("cost",I('post.cost'))){
                echo type(0,'修改成功');
            }else{
                echo type(1,'修改失败');
            }
        } catch (Exception $e) {
            echo type(2,'修改失败');
        }
    }
	//更新接送范围   Coach/updatePickrange     session_id     userid   type     content
    public function updatePickrange($userid,$type,$content){
        try {
            $table=shenfen($type);
            if(M($table)->where("userid='$userid'")->setField("pickrange",$content)){
                echo type(0,'修改成功');
            }else{
                echo type(1,'修改失败');
            }
        } catch (Exception $e) {
            echo type(2,'修改失败');
        }
        echo $info;
    }


	/*************************************************************************************************************/
    //上传认证信息的接口
    public function upcertificate1($type,$userid,$city)
    {
        if(empty($_FILES)){
            echo type(4,"图片为空");return;
        }
        foreach($_FILES as $k=>$v){
            $filename=pathinfo($v['name'],PATHINFO_FILENAME);
            $push=strtolower(pathinfo($v['name'],PATHINFO_EXTENSION));
            $dir=C('conf.coach').$filename;
            $en=range('a','z');
            $newname=time().mt_rand(-9,9).$en[mt_rand(0,25)].'.'.$push;
            if(move_uploaded_file($v['tmp_name'],$dir.'/'.$newname)){
                $img[$k]['imgurl']=$dir.'/'.$newname;
                $img[$k]['userid']=$_POST['userid'];
                $img[$k]['imgtime']=date('Y-m-d H:i:s');
                $img[$k]['paperstype']=$filename;
                M('papersimg')->add($img[$k]);
            }else{
                echo type(3,"上传失败");return;
            }
        }
        $_POST['cityid']=M("citys")->field("id")->where("cityname like '%$city%'")->find()['id'];
        $table=shenfen($type);
        M($table)->where("userid='$userid'")->save($_POST);
        if($type=='jl'){
            $trainid=$_POST['trainid'].',';
            if(M("coachtrains")->where("coachid='$userid'")->find()){
                M("coachtrains")->where("coachid='$userid'")->setField("trainid",$trainid);
            }else{
                M("coachtrains")->add($_POST);
            }
        }
        echo type(0,"上传成功");
    }
    //列表
    public function getpublicList1($start=0,$type='jl',$latitude='',$longitude='')
    {
        $address = $_POST['address'];
        $cityid=M("citys")->field("id")->where("cityname like '%$address%'")->find()['id'];
        switch($type){
            case 'jx':
                getSchoolList1($start,$cityid,$latitude,$longitude);
                break;
            case 'jl':
                getCoachList1($start,$cityid,$latitude,$longitude);
                break;
            case 'zdy':
                getGuiderList1($start,$cityid,$latitude,$longitude);
                break;
        }
    }
	//新的教练详情
    public function teacherDetail($objectid='',$userid='1ACCA599_8206_F1DA_90D3_BCFC6243E415',$type='jl'){
        switch ($type) {
            case "jx":
                $table = "school u";
                $field = "id,userid,nickname,img,piccount,phone,address,pickrange,score,introduction,allcount,passedcount,praisecount,price,hotflag,recommendflag,certificateflag,pickflag,timeflag,piccount,trainaddress,verify";
                break;
            case "jl":
                $table = "coach u";
                $field = "id,userid,masterid,nickname,img,address,sex,phone,birthday,masterid,piccount,introduction,teachedage,score,passedcount,driverage,allcount,jtype,praisecount,certificateflag,timeflag,teachablecount,verify,jltype,boss";
                echo coachDetail($userid, $objectid, $field);return;
                break;
            case "zdy":
                $table = "guider u";
                $field = "id,userid,nickname,img,address,sex,birthday,phone,piccount,teachedage,introduction,score,passedcount,allcount,driverage,praisecount,certificateflag,timeflag,teachablecount,cost,verify";
                break;
        }
        $m = M($table);
        $mm = $m->field($field)
        ->where("userid='$objectid'")
        ->find();
        $mm['evalutioncount'] = M('evaluating')->where("objecthingid='$objectid'")->count();
        $mm['introduction']=strip_tags($mm['introduction']);
        $mm['message'] = M("user u")->field("u.nickname,u.img,e.id,e.time,e.masterid,e.objectid,e.content,e.score,e.num,e.replycount,e.evaluate")
        ->join("xueche1_evaluating e on e.masterid=u.userid and e.objecthingid='$objectid'")->order('e.id desc')->find();
        if(!$mm['message']){
            $mm['message'] = M("evaluating")->field("id,time,masterid,objectid,content,score,num,replycount,evaluate")
            ->where("objecthingid='$objectid'")->order('id desc')->find();
        }
        $mm['consultcount']=M('consult')->where("objectid='$objectid'")->count();
        $mm['consult'] = M('consult c')->field("c.id,c.time,c.masterid,c.objectid,c.content,c.replycount,u.nickname,u.img")
        ->join("xueche1_user u on u.userid=c.masterid and c.objectid='$objectid'")->order('id desc')->find();
        $img = M('img')->where("userid='$objectid'")->count();
        if($img>0){
            $imgname1 = M('img')->field("imgname ")
            ->where("userid='$objectid'")//先写死
            ->order('id desc')
            ->select();
        }else{
            $imgname1 = M('img')->field("imgname ")
            ->where("userid='8141F133-AD0A-388C-23F8-D69897FDE17A'")//先写死
            ->order('id desc')
            ->select();
        }
        foreach($imgname1 as $k=>$v){
            $mm['imgurl'][$k]= C("conf.ip").$imgname1[$k]['imgname'];
        }
        $mm['classcount']=M('trainclass')->where("masterid='$objectid'")->count();
        $trainclass=M("trainclass")->field("tcid,name,include,masterid,time,carname,mode,officialprice,whole517price,prepay517price,prepay517deposit,classtime")->where("masterid='$objectid'")->select();
        $mm['class'] = $trainclass;
        if(M('attention')->where("userid='$userid' and objectid='$objectid'")->find()){
            $mm['conflag']=1;
        }else{
            $mm['conflag']=0;
        }
        $info = result(0, $mm);
        echo $info;
    }
	public function conreplyList($objectid='A2194076_B223_BBEB_4B33_0D59C8CAD108',$start=0,$master='objectid')
    {
        $m = M('replyconsult');
        $mm =  $m->field("id,cid,time,masterid,objectid,content")
        ->where("objectid='$objectid'")->limit("$start,10")
        ->order('id desc')->select();
        if($mm){
            foreach($mm as $k=>$v){
                $id=$mm[$k]['cid'];//那条咨询的id
                if($mm[$k]['masterid']==$objectid){
                    $obj=$mm[$k]['objectid'];//发布回复的人的id
                }else{
                    $obj=$mm[$k]['masterid'];//发布回复的人的id
                }
                $mm[$k]['consult']=M('consult')->field("id as cid,time,masterid,objectid,content,lasttime,type")->where("id=$id")->find();
                if($master=='masterid'){
                    $flag='stuflag';
                    M('replyconsult')->where("cid=$id")->setField('stuflag','y');
                    switch($mm[$k]['consult']['type']){
                        case 'jx':$table="school";break;
                        case 'jl':$table="coach";break;
                        case 'zdy':$table="guider";break;
                    }
                }else{
			 M('replyconsult')->where("cid=$id")->setField('flag','y');
                    $table="user";
                }
                $mm[$k]['consult']['nickname']=M($table)->field("nickname")->where("userid='$obj'")->find()['nickname'];
            }
        }else{
            $mm=[];
        }
        if($master=='objectid'){
		   $flag='flag';
                    M('consult')->where("objectid='$objectid'")->setField('flag','y');
            $mmm=M('consult c')->field("c.id as cid,c.time,c.masterid,c.objectid,c.content,c.lasttime,c.type,u.nickname,c.replycount")->join("xueche1_user u on u.userid=c.masterid and c.objectid='$objectid' and (c.replycount=0 or c.replycount=1)")->order("c.id desc")->limit("$start,10")->select();
            $mm=array_merge($mmm,$mm);
        }
        $info = result(0, $mm);
        echo $info;
    }
	 public function evalreplyList($objectid='22A0A301_8877_C0EE_CB6E_E4D24D46DA3A',$start=0,$master='objecthingid')
    {
        $m = M('replyevaluate');
        $mm =  $m->field("id,tid,time,masterid,objectid,content")
        ->where("objectid='$objectid'")
        ->limit("$start,10")
        ->order('id desc')->select();
        if($mm){
            foreach($mm as $k=>$v){
                $id=$mm[$k]['tid'];//那条评论的id
                if($mm[$k]['masterid']==$objectid){
                    $obj=$mm[$k]['objectid'];//发布回复的人的id
                }else{
                    $obj=$mm[$k]['masterid'];//发布回复的人的id
                }
                $mm[$k]['evaluating']=M('evaluating')->field("id,time,masterid,objecthingid,content,lasttime,type,score,evaluate")->where("id=$id")->find();
                if($master=='masterid'){
                    $flag='stuflag';
                    M('replyevaluate')->where("tid=$id")->setField('stuflag','y');
                    switch($mm[$k]['evaluating']['type']){
                        case 'jx':$table="school";break;
                        case 'jl':$table="coach";break;
                        case 'zdy':$table="guider";break;
                    }
                }else{
		 M('replyevaluate')->where("tid=$id")->setField('flag','y');
                    $table='user';
                }
                $mm[$k]['evaluating']['nickname']=M($table)->field("nickname")->where("userid='$obj'")->find()['nickname'];
            }
        }else{
            $mm=[];
        }
       //如果是教练端
        if($master=='objecthingid'){
		 $flag='flag';
                    M('evaluating')->where("objecthingid='$objectid'")->setField('flag','y');
            $mmm=M('evaluating c')->field("c.id as tid,c.time,c.masterid,c.objecthingid,c.content,c.lasttime,c.type,u.nickname,c.score,c.evaluate")->join("xueche1_user u on u.userid=c.masterid  and c.objecthingid='$objectid' and (c.replycount=0 or c.replycount=1)")->order("c.id desc")->limit("$start,10")->select();
            $mm=array_merge($mmm,$mm);
        }
        $info = result(0, $mm);
        echo $info;
    }
	//新的创建提问接口
    public function createconsult($masterid='8CF687BE_A1AF_5F50_5C3F_C051BB0218A7',$objectid='22A0A301_8877_C0EE_CB6E_E4D24D46DA3A',$content='你好',$type='jx')
    {
        $_POST['masterid']=$masterid;
        $_POST['objectid']=$objectid;
        $_POST['content']=$content;
        $_POST['time'] =$_POST['lasttime']=date("Y-m-d H:i:s");
        if (M('consult')->add($_POST)) {
            $cinfo = M("consult c")->field("c.id,c.time")->where("masterid='$masterid'")->order("c.id desc")->find();
            sendcon($objectid,$type);
            $info = result(0, $cinfo);
        }
        echo $info;
    }
    //创建对提问的回复 createreply   参数 cid(那条提问的id) masterid(我的id) objectid（对方id）content内容
    public function createreply($cid=2,$masterid="1ACCA599_8206_F1DA_90D3_BCFC6243E415",$objectid="F5DCBE66_B741_D0EA_91ED_3A137EE99519",$content='你好',$type='jl')
    {
        $_POST['time'] = date("Y-m-d H:i:s");
        if($type=='xy'){
            $_POST['flag']='y';
        }else{
            $_POST['stuflag']='y';
        }
        if (M('replyconsult')->add($_POST)){
            $post['flag']='n';
            $post['lasttime']=date("Y-m-d H:i:s");
            M('consult')->where("id=$cid")->save($post);
            $result=M("replyconsult")->field("id,time")->where("masterid='$masterid' and objectid='$objectid'")->order("id desc")->find();
            if($type=='xy'){
                send($objectid,"您有一条新消息",array("code"=>1006),'xy');
            }else{
                send($objectid,"您有一条新消息",array("code"=>1006),'jl');
            }
            M('consult')->where("id=$cid")->setInc('replycount',1);
        }else{
            $result=[];
        }
        $info = result(0,$result);
        echo $info;
    }
	//创建评论回复replyevaluate  参数tid（那条评价的id）   masterid（我的id） objectid(对方id) content
    public function replyevaluate($tid=95,$masterid="22A0A301_8877_C0EE_CB6E_E4D24D46DA3A",$objectid="8CF687BE_A1AF_5F50_5C3F_C051BB0218A7",$content='你好',$type)
    {
        $_POST['time'] = date("Y-m-d H:i:s");
        if($type=='xy'){
            $_POST['flag']='y';
        }else{
            $_POST['stuflag']='y';
        }
        if (M('replyevaluate')->add($_POST)){
            $post['flag']='n';
            $post['lasttime']=date("Y-m-d H:i:s");
            M('evaluating')->where("id=$tid")->save($post);
            $result=M("replyevaluate")->field("id,time")->where("masterid='$masterid' and objectid='$objectid'")->order("id desc")->find();
            if($type=='xy'){
                M('evaluating')->where("id=$tid")->setInc('replycount',1);
            }else{
                send($objectid,"您有一条新消息",array("code"=>1004),'jl');
            }
            M('evaluating')->where("id=$tid")->setInc('replycount',1);
        }else{
            $result=[];
        }
        $info = result(0,$result);
        echo $info;
    }
    //回复详情页consultdetail   id(那条提问的id)
    public function consultdetail($id=79)
    {
        $result=M('replyconsult')->field("id,time,masterid,objectid,content")->where("cid=$id")->select();
        $info = result(0, $result);
        echo $info;
    }
    //新的评论列表
    public function allList($objectid='090EB210_D6F4_047A_BC12_2306A6DA280F',$start=0,$type='jx')
    {
		if($type=='jl'){
			$count=M("evaluating")->where("objecthingid='$objectid'")->count();
			if($count==0){
				$objectid=M("coach")->field("masterid")->where("userid='$objectid'")->find()['masterid'];
			}
		}
		$mm = M('evaluating')->field("id,time,masterid,objecthingid,content,score,replycount,evaluate")
			->where("objecthingid='$objectid'")
			->limit("$start,10")
			->order('id desc')
			->select();
		foreach ($mm as $k => $v) {
			$uid = $v['masterid'];
			if($uid!=1){
			    $mm[$k]['user'] = M("user")->field("nickname,img")
			    ->where("userid='$uid'")
			    ->find();
			}
		}
		$info = result(0,$mm);
        echo $info;
    }
    //评论详情 //回复详情页evaluatdetail   id(那条评价的id)
    public function evaluatdetail($id=32)
    {
        $result=M('replyevaluate')->field("id,time,masterid,objectid,content")->where("tid=$id")->select();
        $info = result(0, $result);
        echo $info;
    }
	 //教练端咨询提问列表
    //返回{"code":0,"result":{"consult":"2","evaluating":"0"}}
    function  newmessage($userid='F5DCBE66_B741_D0EA_91ED_3A137EE99519'){
        //提问新的
        $m['consult']=M("consult")->where("objectid='$userid' and flag='n'")->count();
        $m['evaluate']=M("evaluating")->where("objecthingid='$userid' and flag='n' and masterid !=1")->count();
        //提问新的
        $m['replyconsult']=M("replyconsult")->where("objectid='$userid' and flag='n'")->count();
        $m['replyevaluate']=M("replyevaluate")->where("objectid='$userid' and flag='n'")->count();
        echo result(0, $m);
    }
    function  stunewmessage($userid=''){
        //提问新的
        $m['consult']=M("replyconsult")->where("objectid='$userid' and stuflag='n'")->count();
        $m['evaluate']=M("replyevaluate")->where("objectid='$userid' and stuflag='n'")->count();
        echo result(0, $m);
    }
}
