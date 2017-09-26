<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Page;
class TheoryController extends CommonController{
    public function traffictheoryone(){
        try {
            $start=$_POST['start'];
            $mm=M('theory')->field("thnum")->limit("$start,10")->cache(true,300)->select();
            $info=result(0,$mm);
        } catch (Exception $e) {
            $info = type(1, '返回失败', - 1);
        } 
        echo $info;
    }

public function zjtestnum1($subjects=0,$jtype='C1',$address='',$userid=''){
        try {
             $where="t.subjects=$subjects ";
             $w="and t.AddressId=1 ";
            if($subjects==0){
                $data=array();
                $w1=" and t.ChapterId >0 and t.ChapterId <=4";
                $where=$where.$w1;
                $mm=M('smallclass s')->field("count(t.id) as num,s.classname,t.ChapterId")->join("xueche1_theory t on classsmalltype=ChapterId and $where and cartype=0")->group("t.ChapterId")->cache(true,300)->select();
                if($jtype=="A1" || $jtype=="A3" || $jtype=="B1"){
                    $mm[4]['num']=M("theory")->field("count(id) as num")->where("cartype=1")->count();
                    $mm[4]['classname']='客车专用试题';
                    $mm[4]['chapterid']='6';
                }else if($jtype=="A2" || $jtype=="B2"){
                    $mm[4]['num']=M("theory")->field("count(id) as num")->where("cartype=2")->count();
                    $mm[4]['classname']='货车专用试题';
                    $mm[4]['chapterid']='6';
                }else if($jtype=="M"){
                    $mm[4]['num']=M("theory")->field("count(id) as num")->where("cartype=3")->count();
                    $mm[4]['classname']='轮式机械专用试题';
                    $mm[4]['chapterid']='6';
                }
		$privince=M('province')->field('id')->where("province like '%$address%'")->cache(true,300)->find()['id'];
                if($privince){
                    $data[1]['num']=M("theory")->field("count(id) as num")->where("AddressId{$privince}=1")->count();
                    $data[1]['chapterid']=5;
		    $data[1]['cityid']=$privince;
	            $data[1]['classname']=$address."题库";
                }
            }else{
		$data=[];
                $mm=M('smallclass4 s')->field("count(t.id) as num,s.classname,t.ChapterId")->join("xueche1_theory t on s.classsmalltype=t.ChapterId and $where ")->group("s.classname")->cache(true,300)->select();
            }
           $mm=array_merge($mm,$data);	
			foreach($mm as $k=>$v){
				$chapterid=$mm[$k]['chapterid'];
				$mm[$k]['speed']=M("speed")->field("tid,(indexs-1) as indexs")->where("userid='$userid' and subjectid=$subjects and ChapterId=$chapterid")->cache(true,300)->find();
			}
            $info=result(0,$mm);
        } catch (Exception $e) {
            $info = type(1, '返回失败', -1);
        }
          echo $info; 
    }
    public function zjtestnum2(){
        try {
             $subject=$_POST['subjects'];
             $jtype=$_POST['jtype'];
             $addressid=$_POST['address'];
             $where="t.subjects=$subject ";
             $w="and t.AddressId=1 ";
             $userid=$_POST['userid'];
            if($subject==0){
                $data=array();
                $w1=" and t.ChapterId >0 and t.ChapterId <=4";
                if($jtype=="A1" || $jtype=="A3" || $jtype=="B1"){
                    $data[0]['num']=M("theory")->field("count(id) as num")->where("cartype=1")->count();
                }else if($jtype=="A2" || $jtype=="B2"){
                     $data[0]['num']=M("theory")->field("count(id) as num")->where("cartype=2")->count();
                }else if($jtype=="M"){
                    $data[0]['num']=M("theory")->field("count(id) as num")->where("cartype=3")->count();
                }
                $where=$where.$w1;
                if(M('province')->where("province like '%$addressid%'")->count()==1){
                    $id=M('province')->field("id")->where("province like '%$addressid%'")->cache(true,300)->select()[0]['id'];
                    $data[1]['num']=M("theory")->field("count(id) as num")->where("AddressId{$id}=1")->count();
                    $data[1]['chapterid']=5;
					$data[1]['classname']=$addressid."题库";
                }
                $mm=M('smallclass s')->field("count(t.id) as num,s.classname,t.ChapterId")->join("xueche1_theory t on classsmalltype=ChapterId and $where")->group("t.ChapterId")->cache(true,300)->select();
            }else{
				$data=[];
                $mm=M('smallclass4 s')->field("count(t.id) as num,s.classname,t.ChapterId")->join("xueche1_theory t on s.classsmalltype=t.ChapterId and $where ")->group("s.classname")->cache(true,300)->select();
            }
           $mm=array_merge($mm,$data);	
			foreach($mm as $k=>$v){
				$chapterid=$mm[$k]['chapterid'];
				$mm[$k]['speed']=M("speed")->field("tid,(indexs-1) as indexs")->where("userid='$userid' and subjectid=$subject and ChapterId=$chapterid")->cache(true,300)->find();
			}
            $info=result(0,$mm);
        } catch (Exception $e) {
            $info = type(1, '返回失败', -1);
        }
          echo $info; 
    }
     public function truetheory($subjects=0,$jtype='C',$address='上海'){
       try {
		   $count=0;
           $where="subjects=$subjects";
           $w=" and AddressId=1 ";
           $field="id,question,answer,a,b,c,d,times,errortimes,degree,ChapterID,ClassId,imgurl";
           $len1=20;$len2=35;$len3=20;$len4=25;
           if($jtype=="A1" || $jtype=="A3" || $jtype=="B1"){
               $len1=20;$len2=37;$len3=18;$len4=25;
               $data6=M("theory")->field($field)->where("cartype=1")->order('rand()')->limit(5)->cache(true,300)->select();
           }else if($jtype=="A2" || $jtype=="B2"){
               $len1=20;$len2=37;$len3=18;$len4=25;
               $data6=M("theory")->field($field)->where("cartype=2")->order('rand()')->limit(5)->cache(true,300)->select();
           }else if($jtype=="M"){
               $len1=20;$len2=37;$len3=18;$len4=25;
               $data6=M("theory")->field($field)->where("cartype=3")->order('rand()')->limit(5)->cache(true,300)->select();
           }
           $t=array('A1','A2','A3','B1','B2','M');
           $city=M('province')->field("id")->where("province like '%$address%'")->cache(true,300)->find()['id'];
           if(in_array($jtype,$t) && $city){
               $len1=20;$len2=32;$len3=18;$len4=20;
               $w=" or AddressId{$city}=1 ";
               $data5=M('theory')->field($field)->where("$where and classId!=2")->order('rand()')->limit(5)->cache(true,300)->select();
           }elseif(!in_array($jtype,$t) && $city){
               $len1=20;$len2=35;$len3=20;$len4=20;
               $w=" or AddressId{$city}=1 ";
               $data5=M('theory')->field($field)->where("$where and classId!=2")->order('rand()')->limit(5)->cache(true,300)->select();
           }
               $data1=M('theory')->field($field)->where("ChapterID=1 and $where and classId!=2")->order('rand()')->limit($len1)->cache(true,300)->select();
               $data2=M('theory')->field($field)->where("ChapterID=2 and $where and classId!=2")->order('rand()')->limit($len2)->cache(true,300)->select();
               $data3=M('theory')->field($field)->where("ChapterID=3 and $where and classId!=2")->order('rand()')->limit($len3)->cache(true,300)->select();
               $data4=M('theory')->field($field)->where("ChapterID=4 and $where and classId!=2")->order('rand()')->limit($len4)->cache(true,300)->select();
               if(isset($data5) && isset($data6)){
                   $data=array_merge($data1,$data2,$data3,$data4,$data5,$data6);
               }elseif(isset($data6) && !isset($data5)){
                   $data=array_merge($data1,$data2,$data3,$data4,$data6);
               }elseif (isset($data5) && !isset($data6)){
                   $data=array_merge($data1,$data2,$data3,$data4,$data5);
               }else{
                   $data=array_merge($data1,$data2,$data3,$data4);
               }
               foreach($data as $k=>$v){
                   $id=$v['id'];
                   $data[$k]['analysis']=M('reply')->field("analysis")->where("tid=$id")->cache(true,300)->find()['analysis'];
                   if($data[$k]["imgurl"]!=null){
					     $data[$k]["imgurl"]=C('conf.ip').$data[$k]["imgurl"];
				   }
               }
               $info=result(0,$data);
       } catch (Exception $e) {
           $info=type(1,'返回失败',-1);
       }
       echo $info;
   }
    public function truetheory4($subjects,$jtype){
       try {
		   $count=0;
           $where="subjects=$subjects";
           $w=" and AddressId=1 ";
           $field="id,question,answer,a,b,c,d,times,errortimes,degree,ChapterID,ClassId,imgurl";
		   $data1=M('theory')->field($field)->where("$where and ClassId!=2")->order('rand()')->limit(45)->cache(true,300)->select();
           $data2=M('theory')->field($field)->where("$where and ClassId=2")->order('rand()')->limit(5)->cache(true,300)->select();
           $data=array_merge($data1,$data2);
           foreach($data as $k=>$v){
				$id=$v['id'];
				$data[$k]['analysis']=M('reply')->field("analysis")->where("tid=$id")->cache(true,300)->find()['analysis'];
				 if($data[$k]["imgurl"]!=null){
						  $data[$k]["imgurl"]=C('conf.ip').$data[$k]["imgurl"];
				 }
			}
			//dump($data);
               $info=result(0,$data);
       } catch (Exception $e) {
           $info=type(1,'返回失败',-1);
       }
       echo $info;
   }
    public function getAnalysis($userid,$subjects,$jtype){
       try {
           $where="subjects=$subjects and AddressId=1 ";
           $wheres="userid='$userid' and subjectid=$subjects";
		   $allcount=M('theory')->where($where)->count();
           if(isset($_POST['start'])){
			   $start=$_POST['start'];
               if(isset($_POST['nextmode'])){
                   if($_POST['nextmode']==0){
                       $where.=" and id<$start";
						$data=M('theory')->field("id,question,answer,a,b,c,d,imgurl,classid,degree,ChapterId,times,errortimes")->where($where)->order("id desc")->cache(true,300)->find();
                       $index= --$_POST['index'];
                   }elseif($_POST['nextmode']==1){
                       $where.=" and id>$start";
					   $data=M('theory')->field("id,question,answer,a,b,c,d,imgurl,classid,degree,ChapterId,times,errortimes")->where($where)->cache(true,300)->find();
                       $index=  ++$_POST['index'];
                   }
				   }else{
						$data=M('theory')->field("id,question,answer,a,b,c,d,imgurl,classid,degree,ChapterId,times,errortimes")->where($where)->cache(true,300)->find();
				   }
               }else{
                   $tid=M('speed')->field("tid,indexs")->where($wheres)->cache(true,300)->find();
                   if($tid){
                      $data=M('theory')->field("id,question,answer,a,b,c,d,imgurl,classid,degree,ChapterId,times,errortimes")->where("subjects=$subjects")->cache(true,300)->find();
					   $index=1;
                   }else{
                       $id=$tid['tid'];
                       $index=$tid['indexs'];
					    $data=M('theory')->field("id,question,answer,a,b,c,d,imgurl,classid,degree,ChapterId")->where("id>$id and where")->cache(true,300)->find();
                   }
               }
               $tid=$data['id'];
               $data['analysis']=M("reply")->field('analysis')->where("tid=$tid")->cache(true,300)->find()['analysis'];
               if($data["imgurl"]!=null){
                   $data["imgurl"]=C('conf.ip').$data["imgurl"];
               }
               $data['index']=$index;
               $data['allcount']=$allcount;
               $num['tid']=$tid;
               $num['userid']=$userid;
               $num['subjectid']=$subjects;
               $num['indexs']=$index;
               $mm=M('speed')->field("tid")->where($wheres)->cache(true,300)->find()['tid'];
               if($mm){
                   M('speed')->where($wheres)->save($num);
               }else{
                   M('speed')->add($num);
               }
               $info=result(0,$data);
           } catch (Exception $e) {
               $info=type(1,'返回失败',-1);
           }
           echo $info;
       }  
    public function ctestnum1($subject=0,$jtype,$userid){//
        try {
			  
             $data=M("errortheory")->where("userid='$userid' and subjectid=$subject and jtype='$jtype'")->count();
             $info=result(0,$data);
            } catch (Exception $e){
                $info = type(1,'返回失败',-1);
            }
            echo $info; 
    }
   public function viewMyerror($userid='',$subjectid=0,$jtype='C1',$start,$index){
       try {
           $field="e.id,e.tid,e.ChapterId,e.myanswer,t.question,t.answer,t.a,t.b,t.c,t.d,t.degree,t.imgflag,t.imgurl,t.classid";
           $where="e.userid='$userid' and e.subjectid=$subjectid and e.jtype='$jtype' ";
           $countindex=M('errortheory')->where("userid='$userid' and jtype='$jtype' and subjectid=$subjectid")->count();
		   if(isset($_POST['nextmode'])){
					  if($_POST['nextmode']==0){
						   $where.="and e.id<$start";
							 $data=M('theory t')->field($field)->join("xueche1_errortheory e on e.tid=t.id and $where ")->order("e.id desc")->cache(true,300)->find();
						   --$index;
					   }elseif($_POST['nextmode']==1){
						   $where.="and e.id>$start";
						   ++$index;
							 $data=M('theory t')->field($field)->join("xueche1_errortheory e on e.tid=t.id and $where ")->cache(true,300)->find();
					   }
		   }else{
			    $data=M('theory t')->field($field)->join("xueche1_errortheory e on e.tid=t.id and $where")->cache(true,300)->find();
		   }
		   $tid=$data['tid'];
		   $data['analysis']=M('reply')->field('analysis')->where("tid=$tid")->cache(true,300)->find()['analysis'];
		   if($data["imgurl"]!=null){
			   $data["imgurl"]=C('conf.ip').$data["imgurl"];
		   }      		   
           $data['index']=$index;
           $data['allcount']=$countindex;
           $info=result(0,$data);
       } catch (Exception $e) {
           $info=type(1,'返回失败',-1);
       }
       echo $info;
   }
   /**
    * 练习所有错题
    */
   public function myerrorTest($userid='',$subjectid=0,$jtype,$start,$index){ 
       try {
           $field="e.id,e.tid,e.ChapterId,e.myanswer,t.question,t.answer,t.a,t.b,t.c,t.d,t.degree,t.imgflag,t.imgurl,t.classid";
		    $countindex=M('errortheory')->where("userid='$userid'")->count();
			$where="e.userid='$userid' and jtype='$jtype' ";
           if(isset($_POST['del']) && $_POST['del']==1 && $_POST['flag']==1){
               M('errortheory')->where("id=$start")->delete();
			    --$countindex;
				$index=$index>$countindex?$countindex:$index;
           }else{
			   if(isset($_POST['nextmode'])){
				     if($_POST['nextmode']==0){
					      $where.="and e.id<$start";
					      $data=M('theory t')->field($field)->join("xueche1_errortheory e on e.tid=t.id and $where")->order("e.id desc")->cache(true,300)->find();
					   --$index;
					 }elseif($_POST['nextmode']==1){
						     $where.="and e.id>$start";
						      $data=M('theory t')->field($field)->join("xueche1_errortheory e on e.tid=t.id and $where ")->cache(true,300)->find();
						   ++$index;
				     }
			   }else{
			           $data=M('theory t')->field($field)->join("xueche1_errortheory e on e.tid=t.id and $where")->cache(true,300)->find();
			   }
			if($_POST['flag']==2){
				  M('errortheory')->where("id=$start")->setInc("erros",1);
			 }    
           }
		   $tid=$data['tid'];
		   $data['analysis']=M('reply')->field('analysis')->where("tid=$tid")->cache(true,300)->find()['analysis'];
           if($data["imgurl"]!=null){
			   $data["imgurl"]=C('conf.ip').$data["imgurl"];
		   }
           $data['index']=$index;
           $data['allcount']=$countindex;
           $info=result(0,$data);
       } catch (Exception $e) {
           $info=type(1,'返回失败',-1);
       }
       echo $info;
   } 
   /**
    * 我的题库
    */
   public function Mytheory($subjects,$jtype,$userid){
       try {
		        $wheres="subjects=$subjects and userid='$userid'";
				$table='smallclass';
				if($subjects==1){
					$table="smallclass4";
				}
		        $data=M($table)->field('classname,classsmalltype')->group("classname")->cache(true,300)->select();
				   if($jtype=="A1" || $jtype=="A3" || $jtype=="B1"){
                    $data[5]['classname']='客车专用试题';
                    $data[5]['classsmalltype']='6';
                }else if($jtype=="A2" || $jtype=="B2"){
                    $data[5]['classname']='货车专用试题';
                    $data[5]['classsmalltype']='6';
                }else if($jtype=="M"){
                    $data[5]['classname']='轮式机械专用试题';
                    $data[5]['classsmalltype']='6';
                }
				foreach($data as $k=>$v){
					$chapterid=$v['classsmalltype'];
					$data[$k]['num']=M('quebank')->field("count(id) as num,ChapterId")->where("$wheres and ChapterId=$chapterid and jtype='$jtype'")->cache(true,300)->find();
					if($data[$k]['num']['chapterid']==""){
						$data[$k]['num']['chapterid']=$chapterid;
					}
					unset($v['classsmalltype']);
				}
           $info=result(0,$data);
       } catch (Exception $e) {
           $info=type(1,'返回失败',-1);
       }
       echo $info;
   }
   public function addMytheory($userid,$tid){
       try {
           $_POST['qtime']=date("Y-m-d H:i:s");
		   $mm=M("quebank")->where("userid='$userid' and tid=$tid")->count();
		   if($mm==1){
			   echo type(3,'您已收藏过该题',-1);
			   return;
		   }
          if(M('quebank')->add($_POST)){
              $info=type(0,'收藏成功');
          }else{
              $info=type(2,'收藏失败',-1);
          }
       } catch (Exception $e) {
           $info=type(1,'收藏失败',-1);
       }
	  
       echo $info;
   }
   public function savescore($userid){
       try {
           $_POST['stime']=date("Y-m-d H:i:s");
           //$userid=$_POST['userid'];
           if(M('userscore')->add($_POST)){
                $data=M('userscore')->field("id,userid,score,usetime,stime")->where("userid='$userid'")->order("id desc")->limit(1)->cache(true,300)->find();
                $info=result(0,$data);
           }else{
                $info=type(2,'保存失败',-1);
           }
       } catch (Exception $e) {
           $info=type(1,'保存失败',-1);
       }
       echo $info;
   }
   public function returnscore($userid){
       try {
           $data=M('userscore')->field("id,userid,score,usetime,stime")->where("userid='$userid'")->order("id desc")->limit(10)->cache(true,300)->select();
           $info=result(0,$data);
       } catch (Exception $e) {
           $info=type(1,'返回失败',-1);
       }
       echo $info;
   }
   public function MytheoryTest($userid,$chapterid,$subjects,$jtype){//
       try {
           $where="q.userid='$userid' and  q.subjects=$subjects and  q.ChapterId=$chapterid and q.jtype='$jtype'";
           $allcount=M('quebank q')->where($where)->count();
           $index=$_POST['index']>0?$_POST['index']:1;
           $field="q.id,q.tid,q.ChapterId,t.question,t.answer,t.a,t.b,t.c,t.d,t.times,t.errortimes,t.degree,t.ClassId,t.imgurl";
           $num=[];
           if($_POST['start']>0){
			    $start=$_POST['start'];
			   if($_POST['del']==1){
						$start=--$_POST['start'];
					   $allcount=M('quebank q')->where($where)->count();
                       $where.="and q.id>=$start";
			   }else{
               if(isset($_POST['nextmode'])){
                   if($_POST['nextmode']==0){
                       $where.="and q.id<$start";
					    $data=M('quebank q')->field($field)->join("xueche1_theory t on q.tid=t.id and $where")->order("q.id desc")->cache(true,300)->find();
                       --$index;
                   }elseif($_POST['nextmode']==1){
                       $where.="and q.id>$start";
					   $data=M('quebank q')->field($field)->join("xueche1_theory t on q.tid=t.id and $where")->cache(true,300)->find();
                       ++$index;
                   }
			     }else{
					     $data=M('quebank q')->field($field)->join("xueche1_theory t on q.tid=t.id and $where")->cache(true,300)->find();
                   }
               }
               $num['tid']=$start;
               $num['userid']=$userid;
               $num['subjectid']=$subjects;
               $num['ChapterId']=$chapterid;
               $num['indexs']=$index;
               $wheres="userid='$userid' and subjectid=$subjects and ChapterId=$chapterid";
               $mm=M('speed')->where($wheres)->count();
               if($mm==1){
                   if($_POST['flag']!=0){
                       M('speed')->where("userid='$userid' and subjectid=$subjects and ChapterId=$chapterid")->save($num);
                   }
               }else{
                   if($_POST['flag']!=0){
                       M('speed')->add($num);
                   }
               }
           }else{
                $data=M('quebank q')->field($field)->join("xueche1_theory t on q.tid=t.id and $where")->cache(true,300)->find();
           }
           $tid=$data['tid'];
           $data['analysis']=M("reply")->field('analysis')->where("tid=$tid")->cache(true,300)->find()['analysis'];
           if($data["imgurl"]!=null){
               $data["imgurl"]=C('conf.ip').$data["imgurl"];
           }
		   $index=$index>$allcount?$allcount:$index;
           $data['index']=$index;
           $data['allcount']=$allcount;
           $info=result(0,$data);
       } catch (Exception $e){
          $info=type(1,'返回失败');
       }
       echo $info;
   }
   public function trunMyBank($userid,$jtype){
       try {
           if(M('quebank')->where("userid='$userid' and jtype='$jtype'")->delete()){
               $info=type(0,'清空成功');
           }else{
               $info=type(2,'清空失败');
           }
       } catch (Exception $e) {
           $info=type(1,'清空失败');
       }
       echo $info;
   }
   public function delMyBank($userid,$id){
       try {
           if(M('quebank')->where("id=$id")->delete()){
               $info=type(0,'删除成功');
           }else{
               $info=type(2,'删除失败');
           }
       } catch (Exception $e) {
           $info=type(1,'删除失败');
       }
       echo $info;
   }
   public function trunMyerrorTheory($userid,$jtype){
       try {
           if(M('errortheory')->where("userid='$userid' and jtype='$jtype'")->delete()){
               $info=type(0,'清空成功');
           }else{
               $info=type(2,'清空失败');
           }
       } catch (Exception $e) {
           $info=type(1,'清空失败');
       }
       echo $info;
   }
 public function chapter1($userid='',$address='',$chapterid=1,$subjects=0,$flag=2,$jtype='C1'){
       try {
           $count=0;
           $where="subjects=$subjects ";
           if($subjects==0){
                if($jtype=="A1" || $jtype=="A3" || $jtype=="B1"){
                    $count+=M('theory')->where("cartype=1")->count();
                }else if($jtype=="A2" || $jtype=="B2"){
                    $count+=M('theory')->where("cartype=2")->count();
                }else if($jtype=="M"){
                    $count+=M('theory')->where("cartype=3")->count();
                }
            }
           
            if($subjects==0 && $chapterid==5){
                if(M('province')->where("province like '%$address%'")->count()==1){
                    $id=M('province')->field("id")->where("province='$address'")->cache(true,300)->find()['id'];
                    $count+=M('theory')->where("AddressId{$id}=1")->count();
                    $where.=" and AddressId{$id}=1 ";
                }
            }else{
                $where.=" and  ChapterId=$chapterid ";
            }
           $allcount=M('theory')->where($where)->count();
           $field="id,question,answer,a,b,c,d,times,errortimes,degree,ClassId,imgurl";
           $num=[];
		  if($chapterid!=1 && $_POST['index']==0 ){
			  unset($_POST['start']);
		  }
			if(isset($_POST['start'])){
				$start=$_POST['start'];
				$index=  $_POST['index']==0?2:$_POST['index'];
				 if($flag==2){
               $usererro['userid']=$userid;
               $usererro['subjectid']=$subjects;
               $usererro['ChapterId']=$chapterid;
               $usererro['jtype']=$jtype;
               $usererro['tid']=$start;
               $usererro['myanswer']=$_POST['myanswer'];
               $usererro['etime']=date("Y-m-d H:i:s");
              // $userid=$user;
               $tid=$start;
				 M("theory")->where("id=$tid")->setInc("errortimes",1);
               if(M('errortheory')->where("userid='$userid' and tid=$tid")->count()==1){
                  M('errortheory')->where("userid='$userid' and tid=$tid")->setInc("erros",1);
               }else{
                   M('errortheory')->add($usererro);
               }
            }
				if(isset($_POST['nextmode'])){
					if($_POST['nextmode']==0){
						 $start=$start<2?2:$start;
						 $where .="and id<$start";
						 $data=M('theory')->field($field)->where($where)->order("id desc")->cache(true,300)->find();
						 $num['indexs']=$index;
						 $index--;
					}else{
						$where .="and id>$start";
							$data=M('theory')->field($field)->where($where)->cache(true,300)->find();
							 $num['indexs']=$index;
						$index++;
						
					}
				}else{
					$where ="id=$start";
					$data=M('theory')->field($field)->where($where)->cache(true,300)->find();
				}
			}else{
				    $index=1;
					$data=M('theory')->field($field)->where($where)->cache(true,300)->find();
			} 
			if($subjects==1 && $_POST['index']==0){
				$where="subjects=$subjects and ChapterId=$chapterid";
			   $data=M('theory')->field($field)->where($where)->cache(true,300)->find();
		   }
               $wheres="userid='$userid' and subjectid=$subjects and ChapterId=$chapterid";
               $mm=M('speed')->where($wheres)->count();
               if($_POST['flag']!=0){
				   $num['dotime']=time();
               $num['tid']=$start;
               $num['userid']=$userid;
               $num['subjectid']=$subjects;
               $num['ChapterId']=$chapterid;
				  if($mm==1){
					$a=M('speed');
					$a->where("userid='$userid' and subjectid=$subjects and ChapterId=$chapterid")->save($num);
				   }else{
					 M('speed')->add($num);
				   }            
			    }
		      $tid=$data['id'];
			  M("theory")->where("id=$tid")->setInc("times",1);
			  $data['analysis']=M("reply")->field('analysis')->where("tid=$tid")->cache(true,300)->find()['analysis'];
           if($data["imgurl"]!=null){
			   $data["imgurl"]=C('conf.ip').$data["imgurl"];
		   }
           $data['index']=$index;
           $data['allcount']=$allcount;
           $info=result(0,$data);
       } catch (Exception $e){
            $info = type(1, '返回失败', -1);
       }
       echo $info;
   }
	public function chapter($userid='',$address='',$chapterid=1,$subjects=0,$flag=2,$jtype='C1'){
       try {
           $count=0;
           $where="subjects=$subjects ";
           if($subjects==0){
                if($jtype=="A1" || $jtype=="A3" || $jtype=="B1"){
                    $count+=M('theory')->where("cartype=1")->count();
                }else if($jtype=="A2" || $jtype=="B2"){
                    $count+=M('theory')->where("cartype=2")->count();
                }else if($jtype=="M"){
                    $count+=M('theory')->where("cartype=3")->count();
                }
            }
            if($subjects==0 && $chapterid==5){
                if(M('province')->where("province like '%$address%'")->count()==1){
                    $id=M('province')->field("id")->where("province='$address'")->cache(true,300)->find()['id'];
                    $count+=M('theory')->where("AddressId{$id}=1")->count();
                    $where.=" and AddressId{$id}=1 ";
                }
            }else{
                $where.=" and  ChapterId=$chapterid ";
            }
           $allcount=M('theory')->where($where)->count();
           $field="id,question,answer,a,b,c,d,times,errortimes,degree,ClassId,imgurl";
		  if($chapterid!=1 && $_POST['index']==0 ){
			  unset($_POST['start']);
		  }
			if(isset($_POST['start'])){
				$start=$_POST['start'];
				$index=  $_POST['index']==0?2:$_POST['index'];
				 if($flag==2){
				  $_POST['etime']=date("Y-m-d H:i:s");
				  $_POST['tid']=$_POST['start'];
				  $_POST['subjectid']=$_POST['subjects'];
				 $_POST['ChapterId']=$chapterid;
              // $tid=$start;
				 M("theory")->where("id=$start")->setInc("errortimes",1);
               if(M('errortheory')->where("userid='$userid' and tid=$start")->cache(true,300)->find()){
                  M('errortheory')->where("userid='$userid' and tid=$start")->setInc("erros",1);
               }else{
                   M('errortheory')->add($_POST);
               }
            }
				if(isset($_POST['nextmode'])){
					if($_POST['nextmode']==0){
						 $start=$start<2?2:$start;
						 $where .="and id<$start";
						 $data=M('theory')->field($field)->where($where)->order("id desc")->cache(true,300)->find();
						 $index--;
					}else{
						$where .="and id>$start";
							$data=M('theory')->field($field)->where($where)->cache(true,300)->find();
						$index++;
						
					}
				}else{
					$where ="id=$start";
					$data=M('theory')->field($field)->where($where)->cache(true,300)->find();
				}
			}else{
				    $index=1;
					$data=M('theory')->field($field)->where($where)->cache(true,300)->find();
			} 
			if($subjects==1 && $_POST['index']==0){
				$where="subjects=$subjects and ChapterId=$chapterid";
			   $data=M('theory')->field($field)->where($where)->cache(true,300)->find();
		    }
               $wheres="userid='$userid' and subjectid=$subjects and ChapterId=$chapterid";
               if($_POST['flag']!=0){
                   $_POST['dotime']=time();
                   $_POST['subjectid']=$subjects;
                   $_POST['tid']=$start;
		   $_POST['ChapterId']=$chapterid;
		   $_POST['indexs']=$index;
                   $a=M('speed');
				  if(M('speed')->where($wheres)->cache(true,300)->find()){
					$a->where($wheres)->save($_POST);
				   }else{
					 $a->add($_POST);
				   }            
			    }
		      $tid=$data['id'];
			  M("theory")->where("id=$tid")->setInc("times",1);
			  $data['analysis']=M("reply")->field('analysis')->where("tid=$tid")->cache(true,300)->find()['analysis'];
           if($data["imgurl"]!=null){
			   $data["imgurl"]=C('conf.ip').$data["imgurl"];
		   }
           $data['index']=$index;
           $data['allcount']=$allcount;
           $info=result(0,$data);
       } catch (Exception $e){
            $info = type(1, '返回失败', -1);
       }
       echo $info;
   }
	public function chapter_1($userid='0BC9E90D-7014-323C-F4C1-72E8C7F568F9',$chapterid=2,$subjects=1,$flag=0,$jtype='C1',$index=0,$start=0,$nextmode=1){
	  $count=0;
           $where="subjects=$subjects ";
           if($subjects==0){
                if($chapterid==5){
					if(isset($_POST['cityid'])){
						$id=$_POST['cityid'];
						$count+=M('theory')->where("AddressId{$id}=1")->count();
						$where.=" and AddressId{$id}=1 ";
					}
				}else{$where.=" and  ChapterId=$chapterid ";}
				$allcount=M('theory')->where($where)->count();
                $field="id,question,answer,a,b,c,d,times,errortimes,degree,ClassId,imgurl";
           }else{
			$where.=" and  ChapterId=$chapterid ";
		}
		   $allcount=M('theory')->where($where)->count();
		   //如果不是从开始做
			if($index>0){
				 if($flag==2){
				  $_POST['etime']=date("Y-m-d H:i:s");
				  $_POST['tid']=$_POST['start'];
				  $_POST['subjectid']=$_POST['subjects'];
				  $_POST['ChapterId']=$chapterid;
				 M("theory")->where("id=$start")->setInc("errortimes",1);
                 if(M('errortheory')->where("userid='$userid' and tid=$start")->cache(true,300)->find()){
                      M('errortheory')->where("userid='$userid' and tid=$start")->setInc("erros",1);
                 }else{
                      M('errortheory')->add($_POST);
                 }
			}
			 if($_POST['flag']!=0){
                   $_POST['dotime']=time();
                   $_POST['subjectid']=$subjects;
                   $_POST['tid']=$start;
		           $_POST['ChapterId']=$chapterid;
		           $_POST['indexs']=$index;
				  if(M('speed')->where("userid='$userid' and subjectid=$subjects and ChapterId=$chapterid")->cache(true,300)->find()){
					M('speed')->where("userid='$userid' and subjectid=$subjects and ChapterId=$chapterid")->save($_POST);
				   }else{ M('speed')->add($_POST);}            
			    }
			}
			 if($nextmode==1){//1右划下一题
				//如果刚开始做
				if($index==0){	$index=1;}else{$index++;	}
				$where .="and id>$start";
				
			$data=M('theory')->field($field)->where($where)->order("id asc")->cache(true,300)->find();
			}else{//上一题
				$where .="and id<$start";$index--;
				
				$data=M('theory')->field($field)->where($where)->order("id desc")->cache(true,300)->find();
			}
			//查题
		//	$data=M('theory')->field($field)->where($where)->order("id asc")->cache(true,300)->find();
			$tid=$data['id'];
				file_put_contents('/a/a.txt',$where);
			M("theory")->where("id=$tid")->setInc("times",1);
			$data['analysis']=M("reply")->field('analysis')->where("tid=$tid")->cache(true,300)->find()['analysis'];
			//如果有图片
			if($data["imgurl"]!=null){
			   $data["imgurl"]=C('conf.ip').$data["imgurl"];
		    }
		   $data['index']=$index;
                  $data['allcount']=$allcount;
		  $info=result(0,$data);
		  echo $info;
    }
	public function zjtestnum($subjects=0,$jtype='C1',$address='',$userid=''){
        try {
             $where="t.subjects=$subjects ";
             $w="and t.AddressId=1 ";
            if($subjects==0){
                $data=array();
                $w1=" and t.ChapterId >0 and t.ChapterId <=4";
                $where=$where.$w1;
                $mm=M('smallclass s')->field("count(t.id) as num,s.classname,t.ChapterId")->join("xueche1_theory t on classsmalltype=ChapterId and $where and cartype=0")->group("t.ChapterId")->cache(true,300)->select();
                if($jtype=="A1" || $jtype=="A3" || $jtype=="B1"){
                    $mm[4]['num']=M("theory")->field("count(id) as num")->where("cartype=1")->count();
                    $mm[4]['classname']='客车专用试题';
                    $mm[4]['chapterid']='6';
                }else if($jtype=="A2" || $jtype=="B2"){
                    $mm[4]['num']=M("theory")->field("count(id) as num")->where("cartype=2")->count();
                    $mm[4]['classname']='货车专用试题';
                    $mm[4]['chapterid']='6';
                }else if($jtype=="M"){
                    $mm[4]['num']=M("theory")->field("count(id) as num")->where("cartype=3")->count();
                    $mm[4]['classname']='轮式机械专用试题';
                    $mm[4]['chapterid']='6';
                }
				$privince=M('province')->field('id')->where("province like '%$address%'")->cache(true,300)->find();
                if($privince){
                    $id=$privince['id'];
                    $data[1]['num']=M("theory")->field("count(id) as num")->where("AddressId{$id}=1")->count();
                    $data[1]['chapterid']=5;
					$data[1]['cityid']=$id;
					$data[1]['classname']=$address."题库";
                }
            }else{
				$data=[];
                $mm=M('smallclass4 s')->field("count(t.id) as num,s.classname,t.ChapterId")->join("xueche1_theory t on s.classsmalltype=t.ChapterId and $where ")->group("s.classname")->cache(true,300)->select();
            }
           $mm=array_merge($mm,$data);	
			foreach($mm as $k=>$v){
				$chapterid=$mm[$k]['chapterid'];
				$mm[$k]['speed']=M("speed")->field("tid,indexs")->where("userid='$userid' and subjectid=$subjects and ChapterId=$chapterid")->cache(true,300)->find();
			}
            $info=result(0,$mm);
        } catch (Exception $e) {
            $info = type(1, '返回失败', -1);
        }
          echo $info; 
    }
}
