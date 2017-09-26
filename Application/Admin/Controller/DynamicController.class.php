<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
class DynamicController extends CommonController
{
    public function getMyDynamic($userid,$start)  
    {
        $m = M('news');
        if ($m->count() == 0) {
            echo type(0, "还没有任何动态", - 1);
            return;
        }
        $field="newsid,userid as masterid,objectid,time,content,evalutioncount,praisecount,transmitcount,extras,piccount";
        // 0代表只返回数据
        $mm=M("news")->field($field)->where("userid='$userid'")->order("id desc")->limit("$start,10")->cache(true,300)->select();
        foreach ($mm as $k=>$v){
            $id=$v['newsid'];
            $mm[$k]['imgurl']=M("newsimg")->field("imgname as imgurl")->where("imgid=$id and userid='$userid'")->cache(true,300)->select();
            $mm[$k]['flag']=M("dynamic")->where("masterid='$userid' and objecthingid=$id and mode=1")->count();
            foreach($mm[$k]['imgurl'] as $kk=>$vv){
				$mm[$k]['imgurl'][$kk]=C('conf.ip').$vv['imgurl'];
			}
        }
        echo result(0,$mm);
    }
    public function getAllDynamic($userid='',$start)
    {
        $m = M('news');
        if ($m->count() == 0) {
            $mm=[];
            echo result(0, $mm);
            return;
        }
        $field="newsid,userid as masterid,objectid,time,content,evalutioncount,piccount,praisecount,transmitcount,extras,tranid,type";
        // 0代表只返回数据
        $mm['news']=M("news")->field($field)->order("id desc")->limit("$start,10")->cache(true,300)->select();
        foreach ($mm['news'] as $k=>$v){
            $id=$v['newsid'];
            $uid=$v['masterid'];
            $table=shenfen($v['type']);
            $mm['news'][$k]['nickname']=M($table)->field("nickname")->where("userid='$uid'")->cache(true,300)->find()['nickname'];
            $mm['news'][$k]['img']=M($table)->field("img")->where("userid='$uid'")->cache(true,300)->find()['img'];
            $mm['news'][$k]['imgurl']=M("newsimg")->field("imgname as imgurl")->where("imgid=$id")->cache(true,300)->select();
			foreach($mm['news'][$k]['imgurl'] as $kk=>$vv){
				$mm['news'][$k]['imgurl'][$kk]=C('conf.ip').$vv['imgurl'];
			}
        	if($userid==''){
				 $mm['news'][$k]['flag']=0;
			}else{
		        $mm['news'][$k]['flag']=M("dynamic")->where("masterid='$userid' and objecthingid=$id and mode=1")->count();
			}
        }
		//与我相关 
		if(isset($_POST['userid'])){
				$mm["about"]['aboutme']=M("dynamic")->where("objectid='$userid' and flag=0")->count();
		}else{
				$mm["about"]['aboutme']=0;		
		}
        echo result(0, $mm);
    }
    public function getNewsDetail($newsid){
        try {
            $field="evalutioncount,piccount,praisecount,transmitcount,extras,tranid";
            //评论
            $mm=M("news")->field($field)->where("newsid=$newsid")->cache(true,300)->find();
            $contents=M('dynamic')->field("type,id")->where("objecthingid=$newsid and (mode=3 or mode=4)")->cache(true,300)->select();
            $fields="s.id,s.masterid,s.objectid,s.rtime as time,s.objecthingid,s.content,u.nickname,u.img,s.dynamicid";
			foreach($contents as $k=>$v){
			    $table=shenfen($v['type']);	
			    $sid=$v['id'];
			    $where="xueche1_$table u on u.userid=s.masterid and  s.id=$sid";
			    $mm['message'][$k]=M('dynamic s')->field($fields)->join($where)->where("s.mode=3 or s.mode=4")->cache(true,300)->find();
			}
            $praise=M('dynamic')->field("masterid,type")->where("objecthingid=$newsid and mode=1")->cache(true,300)->select();
            $tran=M('dynamic')->field("masterid,type")->where("objecthingid=$newsid and mode=2")->cache(true,300)->select();
            foreach($praise as $k=>$v){
                $uid=$v['masterid'];
                $table=shenfen($v['type']);
                $praise[$k]['nickname']=M($table)->field("nickname")->where("userid='$uid'")->cache(true,300)->find()['nickname'];
            }
            foreach($tran as $k=>$v){
                $uuid=$v['masterid'];
                $table=shenfen($v['type']);
                $tran[$k]['nickname']=M($table)->field("nickname")->where("userid='$uuid'")->cache(true,300)->find()['nickname'];
            }
            $C=count($praise);
            $T=count($tran);
            if($C>0){
                if($C>10){
                    foreach($praise as $k=>$v){
                        if($k<=9){
                            $mm['praise'].=$praise[$k]['nickname'].'、';
                        }
                    }
                    $mm['praise']=rtrim($mm['praise'],'、').'...等{$C}人点过赞';
                }else{
                    foreach($praise as $k=>$v){
                        $mm['praise'].=$praise[$k]['nickname'].'、';
                    }
                    $mm['praise']=rtrim($mm['praise'],'、').'点过赞';
                }
            }else{
                $mm['praise']=null;
            }
          if($T>0){
            if($T>10){
                foreach($tran as $k=>$v){
                    if($k<=9){
                        $mm['tran'].=$tran[$k]['nickname'].'、';
                    }
                }
                $mm['tran']=rtrim($mm['tran'],'、').'...等{$T}人转发了该条动态';
            }else{
                foreach($tran as $k=>$v){
                    $mm['tran'].=$tran[$k]['nickname'].'、';
                }
                $mm['tran']=rtrim($mm['tran'],'、').'转发了该条动态';
            }
          }else{
              $mm['tran']=null;
          }
           $info=result(0,$mm);
        } catch (Exception $e) {
           $info=type(1, "发表失败", '');
        }
        echo $info;
    }
    public function moreEvaluation($start,$newsid){
        try {
            $objectid = $_POST['objectid'];
            $mm=M('dynamic')->field("id,masterid,rtime as time,objecthingid,content,type")->where("objecthingid=$newsid and objectid='$objectid' and mode=2")->order("e.id desc")->limit("$start,10")->cache(true,300)->select();
            foreach($mm as $k=>$v){
                $uuid=$v['masterid'];
                $table=shenfen($v['type']);
                $v['nickname']=M($table)->field("nickname")->where("userid='$uuid'")->cache(true,300)->find()['nickname'];
            }
            $info=result(0,$mm);
        } catch (Exception $e){
            $info=type(1, "返回失败", '');
        }
        echo $info;
    }
	public function sendDynamic()
    {
        $_POST['time'] = date('Y-m-d H:i:s');
        $m = M('news');
		$userid=$_POST['userid'];
        $id=$m->field("max(id) as id")->cache(true,300)->find()['id']+1;
		if(!isset($_POST['objectid'])){
			$_POST["objectid"]=$_POST["userid"];
		}
        $_POST["newsid"]=$id+1;
        if ($m->add($_POST)) {
                $field="userid as masterid,objectid,time,content,evalutioncount,piccount,praisecount,transmitcount,extras,newsid";
                $mm=M("news")->field($field)->where("userid='$userid'")->order("id desc")->cache(true,300)->find();
            if(count($_FILES)>0){
                $up=new Upload();
                $img=$up->dirup(C('conf.udynamicurl'));
                foreach($img as $k=>$v){
                    $img[$k]['imgid']=$id+1;
                    $img[$k]['userid']=$userid;
                    $img[$k]['imgtime']=$_POST['time'];
                }
                M('newsimg')->addAll($img); 
                $imgid=$mm['newsid'];
                $mm['imgurl']=M("newsimg")->field("imgname as imgurl")->where("imgid=$imgid")->cache(true,300)->select();
				  foreach ( $mm['imgurl'] as $k=>$v){
                          $mm['imgurl'][$k]=C('conf.ip').$v['imgurl'];
                 }
            }
			  echo result(0,$mm);
        } else {
            echo type(1, "发表失败", '');
        }
    }
    /**
     * @发表动态
     */
 
    public function forwDynamic($newsid,$userid,$objectid,$type)
    {
		if(empty($userid)){
				echo type(1, "转发失败", '');
				return;
		}
        $m=M('news');
        $news = $m->field("content,piccount,newsid")->where("newsid=$newsid")->cache(true,300)->find();
        $id=$m->field("max(newsid) as nid")->cache(true,300)->find()['nid']+1;
        $news['userid'] =$userid;
        $news['objectid'] =$objectid;
        $news['time'] = date('Y-m-d H:i:s');
        $news['newsid'] =$id;
        $news['content'] =$_POST['objectname'].':【'.$news['content'].'】';
        $news['tranid'] =$newsid;
        $news['type'] =$type;
        if ($m->add($news)) {
            $m->where("newsid=$newsid")->setInc("transmitcount",1);
            $img=M('newsimg')->field("userid,imgname,imgtime")->where("imgid=$newsid")->cache(true,300)->select();
            foreach($img as $k=>$v){
                $v['userid']=$userid;
                $img[$k]['imgid']=$id; 
                $img[$k]['imgtime']=date("Y-m-d H:i:s");
				$news['imgurl'][$k]=C("conf.ip").$img[$k]['imgname'];
            }
            M('newsimg')->addAll($img);
            $tran['masterid']=$userid;
            $tran['objecthingid']=(int)$newsid;
			$tran['objectid']=$objectid;
            $tran['rtime']=date("Y-m-d H:i:s");
			$tran['mode']=2;
            M('dynamic')->add($tran);
            echo result(0,$news);
        } else {
            echo type(1, "转发失败", '');
        }
    }
    public function evaluate()
    {
        $_POST['rtime'] = date('Y-m-d H:i:s');
		$_POST['mode'] =3;
        $newsid=$_POST['objecthingid'];
        if(M('dynamic')->add($_POST)){
             M('news')->where("newsid=$newsid")->setInc("evalutioncount",1);
            echo type(0, "评论成功",-1);
        }else{
            echo type(1, "评论失败", -1);
        }
    }
    public function thumb($masterid,$objecthingid,$objectid){
        try {
			if(empty($masterid)){
				$info = type(1,"点赞失败", - 1);
				echo $info;
				return;
			}
            $_POST['rtime']=date("Y-m-d H:i:s");
            if(M('dynamic')->where("masterid='$masterid' and objecthingid=$objecthingid and mode=1")->count()==0){
				 $_POST['mode']=1;
                if(M('dynamic')->add($_POST)){
                    M('news')->where("newsid=$objecthingid")->setInc("praisecount",1);
                    $info = type(0,"点赞成功", - 1);
                }else{
                    $info = type(1,"点赞失败", - 1);
                }
            }else{
                $info = type(2,"您已经点过赞了", - 1);
            }
        } catch (Exception $e) {
            $info = type(1, "点赞失败", - 1);
        }
        echo $info;
    }
    public function cancelthumb($masterid,$objecthingid){
        try {
			if(empty($masterid)){
				$info = type(1,"点赞失败", - 1);
				echo $info;
				return;
			}
            if(M('news')->where("newsid=$objecthingid")->setDec("praisecount",1) && M("dynamic")->where("masterid='$masterid' and objecthingid=$objecthingid and mode=1")->delete()){
                $info = type(0, "取消成功", - 1);
            }else{
                $info = type(1, "取消点赞失败", - 1);
            }
        } catch (Exception $e) {
            $info = type(1, "取消点赞失败", - 1);
        }
        echo $info;
    }
    public function userDetails($userid='',$objectid='',$start=0){
		try {
			$type=$_POST['type'];
			//	$type='xy';
			$table=shenfen($type);
			$t=M($table);
			$m = M('news');
			if ($m->count() == 0) {
				$data=null;
				echo result(0,$data);
				return;
			}
			$field="newsid,userid as masterid,objectid,time,content,evalutioncount,piccount,praisecount,transmitcount,extras,tranid";
			$data=$t->field("nickname,signature,img,birthday,sex")->where("userid='$objectid'")->cache(true,300)->find();
			$data['news']=M("news n")->field($field)->where("userid='$objectid'")->order("id desc")->limit("$start,10")->cache(true,300)->select();
			if(M('attention')->where("userid='$userid' and objectid='$objectid'")->count()==1){
				$data['flag']=1;
			}else{
				$data['flag']=0;
			}
			if(empty($data['news'])){
					$data['news']=null;
			}else{
				foreach ($data['news'] as $k=>$v){
					$id=$v['newsid'];
					$data['news'][$k]['imgurl']=M("newsimg")->field("imgname as imgurl")->where("imgid=$id and userid='$objectid'")->cache(true,300)->select();
					$data['news'][$k]['flag']=M("dynamic")->where("masterid='$objectid' and objecthingid=$id and mode=1")->count();
						foreach($data['news'][$k]['imgurl'] as $kk=>$vv){
						   $data['news'][$k]['imgurl'][$kk]['imgurl']=C('conf.ip').$vv['imgurl'];
						}
				}
		}
         $info= result(0, $data);
    }  catch (Exception $e) {
            $info = type(1, "返回失败", - 1);
    }
     	echo $info;
	}
	 public function aboutMe($userid,$start=0){ 
        try {
			$field="id,masterid,objectid,objecthingid,rtime as time,flag,mode,dynamicid,type";
			$where="objectid='$userid'";
			$where1="masterid='$userid'";
			if($start==0){
				$return1=M("dynamic")->field($field.",content")->where($where)->order("id desc")->limit("$start,5")->cache(true,300)->select();
				$return2=M("dynamic")->field($field.",content")->where($where1)->order("id desc")->limit("$start,5")->cache(true,300)->select();
				$return=array_merge($return1,$return2);
			}else{
				$return1=M("dynamic")->field($field.",content")->where($where)->order("id desc")->limit("$start,5")->cache(true,300)->select();
				$return2=M("dynamic")->field($field.",content")->where($where1)->order("id desc")->limit("$start,5")->cache(true,300)->select();
				$return=array_merge($return1,$return2);
			}
			 if(empty($return)){
					$return=null;
			 }else{
					foreach($return as $k=>$v){
						$id=$return[$k]['id'];
						$uid=$return[$k]['masterid'];
						$thingid1=$return[$k]['objecthingid'];
						$table=shenfen($v['type']);
						$return[$k]['nickname']=M($table)->field("nickname")->where("userid='$uid'")->cache(true,300)->find()['nickname'];
						$return[$k]['img']=M($table)->field("img")->where("userid='$uid'")->cache(true,300)->find()['img'];
						$return[$k]['news']=M("news")->field("userid,newsid,content,time,content,evalutioncount,piccount,praisecount,transmitcount,extras,tranid")
							->where("newsid=$thingid1")->cache(true,300)->find();
						$img=M("newsimg")->field("imgname")->where("imgid=$thingid1")->cache(true,300)->select();
					   foreach($img as $kk=>$vv){
							 $return[$k]['news']['imgurl'][$kk]=C("conf.ip").$img[$kk]['imgname'];
					   }
						 M("dynamic")->where("id=$id")->setField("flag",1);
					}
			 }
            $info=result(0, $return);
        } catch (Exception $e) {
            $info = type(1, "返回失败");
        }
        echo $info;
    }
    public function returnEvaluate(){ 
        try {
            $_POST['rtime']=date("Y-m-d H:i:s");
			$userid=$_POST['masterid'];
			$_POST['mode']=4;
            if(M("dynamic")->add($_POST)){
               $data=M("dynamic")->field("masterid,objectid,objecthingid,content,rtime as time,flag,dynamicid")->where("masterid='$userid' and  mode=4")->order("id desc")->cache(true,300)->find();
			   $thingid1=$data['objecthingid'];
			   $data['news']=M("news")->field("newsid,content,time,content,evalutioncount,piccount,praisecount,transmitcount,extras,tranid")->where("newsid=$thingid1")->cache(true,300)->find();
			 $img1=M("newsimg")->field("imgname")->where("imgid=$thingid1")->cache(true,300)->select();
			   foreach($img1 as $kk=>$vv){
					 $data['news']['imgurl'][$kk]=C("conf.ip").$img1[$kk]['imgname'];
			   }
			   $info=result(0, $data);
            }else{
                $info = type(2, "回复失败");
            }
        } catch (Exception $e) {
            $info = type(1, "回复失败");
        }
        echo $info;
    }
    public function returnDeal($id){
        try {
                if(M('dynamic')->where("id in($id)")->setField("flag",1)){
                    $info = type(0, "设置成功");
                }else{
                    $info = type(2, "设置失败");
                }
        } catch (Exception $e) {
            $info = type(1, "设置失败");
        }
        echo $info;
    } 
   public function delDynamic($newsid){ 
       try {
           if(M('news')->where("newsid=$newsid")->delete() ){
			   if(M('newsimg')->where("imgid=$newsid")->cache(true,300)->find()){
					M("newsimg")->where("imgid=$newsid")->delete();
			   }
			   if(M('dynamic')->where("objecthingid=$newsid")->cache(true,300)->find()){
					  M('dynamic')->where("objecthingid=$newsid")->delete();
			   }
               $info=type(0,'删除成功');
           }else{
               $info=type(2,'删除失败');
           }
       } catch (Exception $e) {
           $info=type(1,'删除失败');
       }
       echo $info;
   }
}

