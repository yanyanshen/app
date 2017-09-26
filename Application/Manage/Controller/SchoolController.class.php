<?php
namespace Manage\Controller;
use Think\Controller;
use Think\Db2;
use Org\Util\Page;
use Think\Upload1;
use Think\Model;
class SchoolController extends CommonController {
   public function getfeedback(){
       $Data=M('feedback'); // 实例化Data数据对象
       $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count);// 实例化分页类 传入总记录数
       $show=$Page->show();// 分页显示输出
       // 进行分页数据查询
       $list = $Data->field("id,feedid,feedname,feedphone,feedtime,content,flag")->limit($Page->firstRow.','.$Page->listRows)->select();
       foreach($list as $k=>$v){
           if($v['feedid']!=null){
               $uid=$v['feedid'];
               $m=M('user')->field("nickname,phone")->where("userid='$uid'")->select();
               $list[$k]['feedname']=$m[0]['nickname'];
               $list[$k]['feedphone']=$m[0]['phone'];
           }
       }
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->display(); // 输出模板
   }
   //查看反馈的详细内容
   public function feedinfo(){
       $type=I('get.type');
       $typeid=I("get.id");
       $info=M('feedback')->field("id,feedid,feedname,feedphone,feedtime,content,flag")->where("id=$typeid")->select();
           if($info[0]['feedid']!=null){
               $uid=$info[0]['feedid'];
               $m=M('user')->field("nickname,phone")->where("userid='$uid'")->select();
               $info[0]['feedname']=$m[0]['nickname'];
               $info[0]['feedphone']=$m[0]['phone'];
           }
       $this->assign("info",$info[0]);
       $this->display();
   }
   public function returnType($type){
       switch($type){
           case 'jx':
               $table= 'school';
               break;
           case 'jl':
               $table= 'coach';
               break;
           case 'zdy':
               $table= 'guider';
               break;
           default:
               $table= 'user';
               break;
       }
       return $table;
   }
  
   /**
    * 驾校列表
    */
   public function schoolList(){
	   if(!empty($_SESSION['cityid'])){
		   $cityid=$_SESSION['cityid'];
		    $where="s.cityid=$cityid";
	   }
		if($_POST['nickname']!='' && $_POST['city']!=''){
			  $nickname=I('post.nickname');
			  $where.="s.nickname like '%$nickname%' ";
			   $cityname=rtrim(I('post.city'),'市');
			  $cityid=M('citys')->field("id")->where("cityname like '%$cityname%'")->find()['id'];
			  $where.=" and s.cityid=$cityid";
			  $_SESSION['cityid']=$cityid;
		}else if($_POST['city']!='' &&  $_POST['nickname']==''){
			  $cityname=rtrim(I('request.city'),'市');
			  $cityid=M('citys')->field("id")->where("cityname like '%$cityname%'")->find()['id'];
			  $where="s.cityid=$cityid";
			  $_SESSION['cityid']=$cityid;
		}else if($_POST['city']=='' &&  $_POST['nickname']!=''){
			  $nickname=I('post.nickname');
			  $where="s.nickname like '%$nickname%' ";
		}
       $Data=M("school s"); // 实例化Data数据对象
       $count=$Data->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count,8);// 实例化分页类 传入总记录数
       $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
       $show=$Page->show($Page->nowPage);//分页显示输出
       //根据$schoolid 
       $schoolid=$Data->field("s.userid,s.cityid")->order("s.id")->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
       // 进行分页数据查询
       $list = $Data->field("s.id,s.account,s.userid,s.nickname,s.img,s.phone,s.type,s.address,count(t.id) as traincount,s.connectteacher,s.cityid,s.verify,s.lastupdate")
       ->join("left join xueche1_schooltrain t on t.schoolid=s.userid")->where($where)->order("s.id")->limit($Page->firstRow.','.$Page->listRows)->group("s.userid")->select();
       foreach($schoolid as $k=>$v){
           $userid=$v['userid'];
           $cityid=$v['cityid'];
           $list[$k]['attcount']=M("attention")->where("objectid='$userid'")->count();//关注数
           $list[$k]['classcount']=M("trainclass")->where("masterid='$userid'")->count();//课程数
           $list[$k]['signcount']=M("sign")->where("masterid='$userid'")->count();//报名人数
           $list[$k]['cityname']=M("citys")->field("cityname")->where("id='$cityid'")->find()['cityname'];//所在城市
           $list[$k]['stu']=M("student s")->field("u.img,u.userid,s.truename,s.masterid,s.note,s.phone,s.address,s.pickupaddress,s.trainclass,s.subjects,s.paytype")
           ->join("xueche1_user u on s.userid=u.userid and s.masterid='$userid'")->count();
           $coachs=M('coach')->field("nickname,userid")->where("masterid='$userid'")->select();
           foreach ($coachs as $kk=>$vv){
               $cid=$vv['userid'];
               $list[$k]['stu']+=M("student s")->join("xueche1_user u on s.userid=u.userid and s.masterid='$cid'")->count();
           }
       }
       $this->assign('schoollist',$list);// 赋值数据集
      // $this->assign('row',$list);// 赋值数据集
	  $this->assign('rowscount',$count);// 总得记录数
       $this->assign('page',$show);// 赋值分页输出
       $this->assign('p',$Page->nowPage);// 赋值分页输出
       $this->display(); //输出模板
   }
   //------------------------------------------------------------------------------------------------------------
   /**
    *  编辑驾校
    */
   public function jxinfo(){
       $userid=I('get.userid');
       if(isset($_GET['sid'])){
           $sid=$_GET['sid'];
           if(M('schoolland')->where("id=$sid")->delete()){
               lastupdate('jx', $userid);
               echo "<script>alert('删除成功')</script>";
           }else{
               echo "<script>alert('删除失败')</script>";
           }
       }
       $nowpage=I('get.p');
       //驾校表
       $info=M("school")->field("account,userid,img,nickname,fullname,phone,pickrange,score,address,allcount,passedcount,price,evalutioncount,praisecount,introduction,cityid,connectteacher,timeflag,piccount")->where("userid='$userid'")->find();
       $land=M("schoolland s")->field("s.id as sid,l.id,l.landname,l.masterid,c.countyname")->join("xueche1_landmark l on s.landmarkid=l.id and s.schoolid='$userid'")->join("xueche1_countys c on c.id=l.masterid")->select();
        foreach($land as $k=>$v){
            $masterid=$v['masterid'];
            $county[$k]=M("countys")->field("countyname")->where("id=$masterid")->find()['countyname'];
        }
        $county=array_unique($county);
        //地区、地标
        foreach($county as $k=>$v){
            foreach($land as $kk=>$vv){
                if($vv['countyname']==$v){
                    $countys[$v][$kk]['landname']=$vv['landname'];
                    $countys[$v][$kk]['id']=$vv['id'];
                    $countys[$v][$kk]['sid']=$vv['sid'];
                }
            }
        }
        //城市
        $city=M("citys")->field("id,cityname")->select();
        //每一个城市的地标默认是数据库保存的城市
        $cityid=$info['cityid'];
        //根据用户选择的城市来显示县和区
        $count=M("countys")->field("id,countyname")->where("masterid=$cityid")->select();
        $landid=$count[0]['id'];
        //地标
        $lands=M("landmark")->field("id,landname")->where("masterid=$landid")->select();
        foreach ($lands as $k=>$v){
            foreach ($countys as $kk=>$vv){
                foreach($vv as $kkk=>$vvv){
                    if($vvv['landname']==$v['landname']){
                        unset($lands[$k]);
                    }
                }
            }
        }
       // $lands=$this->noYet($lands, $countys);
        //通过率和好评率
        if($info['passedcount']==0){
            $tgl=0;
        }else{
            $tgl=$info['passedcount']/$info['allcount'];
        }
        if($info['praisecount']==0){
            $hpl=0;
        }else{
            $hpl=$info['praisecount']/$info['evalutioncount'];
        }
        $this->assign("info",$info);
        $this->assign("json",$this->result($info['introduction']));
        $this->assign("city",$city);
        $this->assign("count",$count);
        $this->assign("lands",$lands);//还未添加的地标
        $this->assign("county",$county);
        $this->assign("countys",$countys);
        $this->assign("userid",$userid);
        $this->assign("p",$nowpage);
        $this->assign("passrate",$this->percent($tgl));//通过率
        $this->assign("goodrate",$this->percent($hpl));//好评率
        $this->assign("cityid",$info['cityid']);
        $this->display();
   }
   public function county(){
       $cityid=$_POST['type'];
       $count=M("countys")->field("id,countyname")->where("masterid=$cityid")->select();
       echo $this->result($count);
   }
   public function landmark(){
       $conutyid=$_POST['type'];
       $userid=$_POST['userid'];
       $count=M("landmark")->field("id,landname")->where("masterid=$conutyid")->select();
       //查出已经勾选的地标
       $id=M('schoolland s')->field("l.id,l.landname")->join("xueche1_landmark l on l.id=landmarkid and l.masterid=$conutyid")->where("schoolid='$userid'")->select();
       $counts=array_diff(array_merge(array_diff($count,$id),array_diff($id,$count)));
       foreach ($count as $key => $value) {
           if(!in_array($value,$id)){
               $counts[]=$value;
           }
       }
       echo $this->result($counts);
   }
   
   //------------------------------------------7.18------------------------------------------------------
  //更新驾校的基本信息
  function jxinfoupdate(){
      $userid=I("post.userid");
      if(M('school')->where("userid='$userid'")->save($_POST)){
          lastupdate('jx', $userid);
		  addlog("更新{$userid}成功");
          echo 1;
      }else{
		  addlog("更新{$userid}失败");
          echo 0;        
      }
  }
  //给驾校添加地标
  function addjxland(){
      $userid=I("request.userid");
      unset($_REQUEST['userid']);
      foreach($_REQUEST  as $k=>$v){
          if(is_numeric($k)){
              $arr[$k]['landmarkid']=$v;
              $arr[$k]['schoolid']=$userid;
          }
      }if(M('schoolland')->addAll($arr)){
          lastupdate('jx', $userid);
		   addlog("给{$userid}驾校添加地标成功");
          echo 1;
      }else{
		  addlog("给{$userid}驾校添加地标失败");
          echo 0;
      }
  }
  //添加驾校页面提供城市信息
  public function addjx(){
      $city=$this->returncity();
      $this->assign("city",$city);
      $this->assign("pass",C('con.pass'));
      $this->display();
  }
  //添加驾校信息
  public function addjxinfo(){
      $account=I('post.account');
      if(M('school')->where("account='$account'")->find()){
          echo 3;return;
      }
      $_POST['userid']=$this->guid();
        foreach($_POST as $k=>$v){
            if(empty($v)){
                echo 2;
                return;
            }
        }
      if(M('school')->add($_POST)){
          lastupdate('jx', $_POST['userid']);
		   addlog("添加{$_POST['userid']}驾校");
          echo $_POST['userid'];
      }else{
          echo 0;
      }
  }
  //删除驾校
  public function delschool(){
      $userid=I('get.userid');
      $p=I('get.p');
      //M('school s')->join("left join xueche1_trainclass t on t.masterid=s.userid")->join("left join xueche1_news n on n.userid=s.userid")
      //->join("left join xueche1_img i on i.userid=s.userid")->join("left join xueche1_schoolland l on l.schoolid=s.userid")
      //->join("left join xueche1_schooltrain r on r.schoolid=s.userid")->where("s.userid='$userid'")->delete();
      M('school')->where("userid='$userid'")->delete();
      M('trainclass')->where("masterid='$userid'")->delete();
      M('news')->where("userid='$userid'")->delete();
      M('img')->where("userid='$userid'")->delete();
      M('newsimg')->where("userid='$userid'")->delete();//删除tupian信息
      M('schoolland')->where("schoolid='$userid'")->delete();
      M('schooltrain')->where("schoolid='$userid'")->delete();
    //$m=new Model();$m->execute("delete from xueche1_school s,xueche1_trainclass t,xueche1_news n,xueche1_img i,xueche1_schoolland l,xueche1_schooltrain r where t.masterid=s.userid and n.userid=s.userid and i.userid=s.userid and l.schoolid=s.userid and r.schoolid=s.userid and s.userid='$userid'");
      $u=U('schoolList?userid='.$userid.'&p='.$p);
      header("location:$u");
  }
  //----------------------------------------------7.19添加基地----------------------------------------------------
  public function train(){
      $userid=I('get.userid');
      if(isset($_GET['sid'])){
          $sid=$_GET['sid'];
           if(M("schooltrain")->where("id=$sid")->delete()){
               lastupdate('jx', $userid);
               echo "<script>alert('删除成功')</script>";
           }else{
               echo "<script>alert('删除失败')</script>";
           }
      }
      $cityid=I('get.cityid');
      $nickname=M("school")->field("nickname")->where("userid='$userid'")->find()['nickname'];
      $schooltrain=M('schooltrain s')->field("s.id as sid,t.id,t.trname")->join("xueche1_train t on s.trainid=t.id and s.schoolid='$userid'")->select();
      if(empty($schooltrain)){
          $train=M("train")->field("id,trname")->where("cityid=$cityid")->select();
      }else{
          $id='';
          foreach($schooltrain as $v){
              $id.=$v['id'].",";
          }
          $id=rtrim($id,',');
          $train=M("train")->field("id,trname")->where("cityid=$cityid and id not in($id)")->select();
      }
      $city=M("citys")->field("cityname")->where("id=$cityid")->find()['cityname'];
      $this->assign("nickname",$nickname);
      $this->assign("city",$city);
      $this->assign("schooltrain",$schooltrain);
      $this->assign("train",$train);
      $this->assign("cityid",$cityid);
      $this->assign("userid",$userid);//dump($schooltrain);exit;
      $this->display();
  }
  //添加驾校基地
  public function addjxtrain(){
      $userid=I("request.userid");
      unset($_REQUEST['userid']);
      foreach($_REQUEST  as $k=>$v){
          if(is_numeric($k)){
              $arr[$k]['trainid']=$v;
              $arr[$k]['schoolid']=$userid;
          }
      }if(M('schooltrain')->addAll($arr)){
          lastupdate('jx', $userid);
		  addlog("给{$userid}驾校添加基地成功");
          echo 1;
      }else{
		  addlog("给{$userid}驾校添加基地失败");
          echo 0;
      }
  }
  //从schoollist点击课程跳转到这里
  public function trainclassList(){  
      $userid=I('get.userid');
      $Data=M("trainclass"); // 实例化Data数据对象
      $nickname=M("school")->field("nickname")->where("userid='$userid'")->find()['nickname'];
      $count=$Data->where("masterid='$userid'")->count();// 查询满足要求的总记录数 $map表示查询条件
      $Page=new Page($count,17);// 实例化分页类 传入总记录数
      $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
      $show=$Page->show($Page->nowPage);//分页显示输出
      // 进行分页数据查询
      $list = $Data->field("id,tcid,name,carname,officialprice,whole517price,prepay517price,prepay517deposit,waittime,classtime")
      ->where("masterid='$userid'")->order("id")->limit($Page->firstRow.','.$Page->listRows)->select();
	 // dump($list);exit;
      $this->assign('trainlist',$list);// 赋值数据集
      $this->assign('page',$show);// 赋值分页输出
      $this->assign('nickname',$nickname);// 赋值分页输出
      $this->assign('rowscount',$count);// 赋值分页输出
      $this->assign('p',$Page->nowPage);// 当前页
      $this->assign('userid',$userid);// 用户id
      $this->display(); //输出模板
  }
  //-------------------------------------------------7.20-------------------------------------------
  //驾校删除课程的课程信息
  public function deljxclass(){
      $id=I('get.id');//id是唯一标示符
      $p=I('get.p');
      $userid=I('get.userid');
      $u=U('trainclassList?userid='.$userid.'&p='.$p);
      M('trainclass')->where("id=$id")->delete();
      lastupdate('jx', $userid);
      header("location:$u");
  }
  //添加驾校的课程信息界面
  public function addjxclass(){
      $userid=I('get.userid');
      $nickname=M('school')->field("nickname")->where("userid='$userid'")->find()['nickname'];
      $this->assign("nickname",$nickname);
      $this->assign("userid",$userid);
      $this->display();
  }
  //添加课程信息
  public function addclass(){
        foreach($_POST as $k=>$v){
            if(empty($v)){
                echo 2;
                return;
            }
        }
      if(M('trainclass')->add($_POST)){
          lastupdate('jx', $_POST['masterid']);
		  addlog("给{$_POST['masterid']}驾校添加课程成功");
          echo $_POST['masterid'];
      }else{
		  addlog("给{$_POST['masterid']}驾校添加课程失败");
          echo 0;
      }
  }
  //教练端图片的管理imgmanage
  public function imgmanage(){
      if(isset($_GET['t'])){
          if($_GET['t']==1){
              echo "<script>alert('上传成功')</script>";
          }else if($_GET['t']==2){
              echo "<script>alert('上传失败')</script>";
          }else{
              echo "<script>alert('最多只能上传9张图片')</script>";
          }
      }  
	  $userid=I('get.userid');
      $type=I('get.type');
      if(isset($_GET['id'])){
          $id=$_GET['id'];
          M('img')->where("id=$id")->delete();
		   switch ($type) {
            case 'jx':
                $cc = C('con.schoolupimg');
                break;
            case 'jl':
                $cc = C('con.coachupimg');
                break;
            case 'zdy':
                $cc = C('conf.guiderupimg');
                break;
        }
          unlink("{$cc}/{$_GET['name']}"); unlink("{$cc}/small/{$_GET['name']}");
      }
      $p=I('get.p');
      $table=$this->shenfen($type);
      $nickname=M($table)->field("id,nickname,img")->where("userid='$userid'")->find();
      $img=M("img")->field("id,imgname,imgtime")->where("userid='$userid'")->select();
      if(empty($img)){
          $i="还没有任何图片";
      }else{$i='';}
      $list='';
      switch($type){
          case 'jx':
              $list="schoolList?p={$p}";
              $imgurl=C("con.schoolupimg");
              foreach($img as $k=>$v){
                  $img[$k]['name']=preg_replace("/\/upload\/school\/small\//i", "", $v['imgname']);//i表示大小写
              }
              break;
          case 'jl':
              $list="Coach/coachList?p={$p}";
              $imgurl=C("con.coachupimg");
              foreach($img as $k=>$v){
                  $img[$k]['name']=preg_replace("/\/upload\/coach\/me\/small\//i", "", $v['imgname']);//i表示大小写
              }
              break;
          case 'zdy':
              $list="Guider/guiderList?p={$p}";
              $imgurl=C("con.guiderupimg");
              foreach($img as $k=>$v){
                  $img[$k]['name']=preg_replace("/\/upload\/guider\/me\/small\//i", "", $v['imgname']);//i表示大小写
              }
              break;
      }//dump($img);exit;
      $this->assign("nickname",$nickname);
      $this->assign("img",$img);
      $this->assign("i",$i);
	  $this->assign("p",$p);
      $this->assign("type",$type);
      $this->assign("list",$list);
      $this->assign("userid",$userid);
      $this->assign("bigurl",$imgurl.'/');
      //$this->assign("p",$p);
      $this->display();
  }
  public function shenfen($type){
      switch($type){
          case 'jx':
              $table='school';
              break;
          case 'jl':
              $table='coach';
              break;
          case 'zdy':
              $table='guider';
              break;
      }
      return $table;
  }
  //实现上传
  public function imgupload(){
      $userid=I('get.userid');
      $type=I('get.type');
      $p=I('get.p');
      $t=I('get.t');//通过t来判断是上传头像还是上传图片
      if($t==1){
          $return=$this->dirup($userid);
         if($return==''){
             $u=U('imgmanage?userid='.$userid.'&p='.$p.'&type='.$type.'&t=2');
         }else{
             $table=$this->shenfen($type);M($table)->where("userid='$userid'")->setField('img',$return);
             $u=U('imgmanage?userid='.$userid.'&p='.$p.'&type='.$type.'&t=1');
             lastupdate('jx', $_POST['masterid']);
         }
      }else if($t==2){
          $c=M("img")->where("userid='$userid'")->count();
          if($c>=9){
              $u=U('imgmanage?userid='.$userid.'&p='.$p.'&type='.$type.'&t=3');
              header("location:$u");
              return;
          }
		   switch ($type) {
            case 'jx':
                $cc = C('con.schoolupimg');
                break;
            case 'jl':
                $cc = C('con.coachupimg');
                break;
            case 'zdy':
                $cc = C('con.guiderupimg');
                break;
        }
          $upload=new Upload1();
          $mm=$upload->dirup($cc,9-$c);
          if(empty($mm)){
              $u=U('imgmanage?userid='.$userid.'&type='.$type.'&t=2&p='.$p);
          }else{
            foreach ($mm as $k => $v) {
                $mm[$k]['userid'] = $userid;
                $mm[$k]['imgtime'] = date("Y-m-d H:i:s");
            }M("img")->addAll($mm);
            lastupdate('jx', $_POST['masterid']);//最后更新人函数
              $u=U('imgmanage?userid='.$userid.'&type='.$type.'&t=1&p='.$p);
          }
      }
      header("location:$u");
  }
    /**
     * 文件上传
     */
    public function dirup($account, $dir="./upload/big")
    {
        $j = 0;
        $arr=['jpg','png'];
        if ($_FILES['ImageFiled']['size'] < 1024*1024*2) {
            $push=strtolower(pathinfo($_FILES['ImageFiled']['name'],PATHINFO_EXTENSION));
            if (in_array($push,$arr)) {
                move_uploaded_file($_FILES['ImageFiled']['tmp_name'], $dir . '/' . $account . '.' . $push);
                ++$j;
            }
            if ($j > 0) {
                $this->img_cl($account, "./upload/big", 120,$push);
                return $account.'.'.$push;
            }else{
                return '';
            }
        }
    }
    
    /**
     * 生成缩略图
     */
    public function img_cl($userid, $dir, $w,$push)
    {
    
        $dirname = $dir . '/' . $userid . '.'.$push;
        //  $path = pathinfo($dirname, PATHINFO_EXTENSION);
        $dirr = pathinfo($dirname, PATHINFO_DIRNAME);
        if($push==png){
            $i = imagecreatefrompng($dirname);
        }else{
            $i = imagecreatefromjpeg($dirname);
        }
         
        $ww = imagesx($i);
        $hh = imagesy($i);
        $h = floor($hh * ($w / $ww));
        $img = imagecreatetruecolor($w, $h);
        imagecopyresized($img, $i, 0, 0, 0, 0, $w, $h, $ww, $hh);
        if($push=='png'){
            header("content-type:image/png");
        }else{
            header("content-type:image/jpeg");
        }
        // $filename = pathinfo($dir . '/' . $userid. '.'.$push, PATHINFO_BASENAME); // 返回文件名.拓展名
        imagejpeg($img, './upload/small/' . $userid . '.' . $push);
    }
    //---------------------------------------------------------7.21-------------------------------------------
    //从驾校列表---》基地---》添加教练 的界面
    public function trainaddcoach(){
        $userid=I('get.userid');
        $trainid=I('get.trainid');
        //如果存在tid，说明用户在该基地删除某个教练
        if(isset($_GET['tid'])){
            $id=$_GET['tid'];
            if(M("coachtrain")->where("id=$id")->delete()){
                lastupdate('jx', $userid);
                echo "<script>alert('删除成功')</script>";   
            }else{
                echo "<script>alert('删除失败')</script>";
            }
        }
        
        $trname=M("train")->field("trname")->where("id=$trainid")->find()['trname'];
        //先查询已有的教练信息(id,和姓名)
        $traincoach=M("coachtrain c")->field("c.id as tid,cc.id,cc.userid,cc.nickname")->join("xueche1_coach cc on cc.userid=c.coachid and  c.schoolid='$userid'")->select();
        $nickname=M("school")->field("nickname")->where("userid='$userid'")->find()['nickname'];
        if(empty($traincoach)){
            $coachs=M("coach")->field("id,userid,nickname")->where("masterid='$userid'")->select();
        }else{
            //取出已添加过的教练的id
            foreach($traincoach as $v){
                $ids.=$v['id'].',';
            }
            $ids=rtrim($ids,',');
            //还未添加的教练的
            $coachs=M("coach")->field("id,userid,nickname")->where("masterid='$userid' and id not in($ids)")->select();
        }
        $this->assign("nickname",$nickname);//驾校的昵称
        $this->assign("traincoach",$traincoach);//已添加的教练
        $this->assign("coachs",$coachs);//还未添加的教练
        $this->assign("trname",$trname);
        $this->assign("userid",$userid);//驾校的id
        $this->assign("trainid",$trainid);//基地id
        $this->display();
    }
    //从驾校列表---》基地---》添加教练 的实现
    public function addtraincoach(){
      $userid=I("request.userid");
      $trainid=I("request.trainid");
      unset($_REQUEST['userid']);
      unset($_REQUEST['trainid']);
      foreach($_REQUEST  as $k=>$v){
          if(is_numeric($k)){
              $arr[$k]['coachid']=$v;
              $arr[$k]['schoolid']=$userid;
              $arr[$k]['trainid']=$trainid;
          }
      }if(M('coachtrain')->addAll($arr)){
          lastupdate('jx', $userid);
		  addlog("给{$userid}驾校{trainid}基地添加教练成功");
          echo 1;
      }else{
		  addlog("给{$userid}驾校{trainid}基地添加教练成功");
          echo 0;
      }
    }
	//-----------------------------------------------8.2---------------------------------------
    //更新课程界面
    public function updatejxclass(){
        //p/userid/classid
        $tcid=I('get.classid');
        $userid=I('get.userid');
        $nickname=M('school')->field("nickname")->where("userid='$userid'")->find()['nickname'];
        $class=M('trainclass')->field("name,carname,include,traintype,picktype,officialprice,whole517price,prepay517price,prepay517deposit,waittime,classtime")->where("tcid=$tcid")->find();
        $this->assign('p',$_GET['p']);
        $this->assign("class",$class);
        $this->assign("tcid",$tcid);
        $this->assign("userid",$userid);
        $this->assign("nickname",$nickname);//驾校名
        $this->display();
    }
    //保存更新
    public  function updateclass(){
        unset($_POST['nickname']);
        $tcid=I('post.tcid');
        if(M('trainclass')->where("tcid=$tcid")->save($_POST)){
            $userid=M('trainclass')->field("masterid")->where("tcid=$tcid")->find()['masterid'];
            lastupdate('jx', $userid);
			addlog("更新id为{$tcid}的课程信息成功");
            echo 1;
        }else{
			addlog("更新id为{$tcid}的课程信息失败");
            echo 0;
        }
    }
	//
	   //根据输入的名字去找城市
   public function returnallcity(){
       $cityname=I('post.cityname');
       $citys=M('citys')->field("id,cityname")->where("cityname like '%$cityname%'")->select();
       echo $this->result($citys);
   }
   //修改用户的认证状态
   public function verify(){
	   $userid=I('get.userid');
	   $p=I('get.p');
	   $verify=I('get.verify');
	   if($verify==3){
			$verify=1;
	   }else{
			$verify=3;
	   }
	  // addlog("修改{$userid}驾校的认证状态");
	   if(M("school")->where("userid='$userid'")->setField("verify",$verify)){
	       lastupdate('jx', $userid);
		   $this->redirect("schoolList?p={$p}");
	   }else{
		   echo "<script>alert('修改失败');location.href='schoolList?p={$p}'</script>";
	   }
   }
   public function qx(){
		   $_SESSION['cityid']='';
		   $this->redirect("schoolList");
   }
   //------------------------------------------10.25--------------------------
   //查看教练端注册使用的证件
   public function zhengjian($userid,$p,$type){
       $zj=M('papersimg')->field('id,imgurl,imgtime,paperstype')->where("userid='$userid'")->select();
       switch($type){
           case 'jx':
               $url='schoolList';
               $types='驾校';
               $table='school';
               break;
           case 'jl':
               $url='coach/coachList';
               $types='教练';
               $table='coach';
               break;
           case 'zdy':
               $url='guider/guiderList';
               $types='指导员';
               $table='guider';
               break;
       }
       if(empty($zj)){
           $this->redirect($url,array('p'=>$p),0.1,"<script>alert('还未上传证件照')</script>");
           return;
       }
       $nickname=M($table)->field("nickname")->where("userid='$userid'")->find()['nickname'];
       $this->assign("zj",$zj);
       $this->assign("userid",$userid);
       $this->assign('p',$p);
       $this->assign('url',$url);
       $this->assign('types',$types);
       $this->assign('type',$type);
       $this->assign('nickname',$nickname);
       $this->display();
   }
   //删除证件照
   public function delzj($id,$userid,$type,$p){
       if(M('papersimg')->delete($id)){
           $message="<script>删除成功</script>";
       }else{ 
           $message="<script>删除失败</script>";
       }
       $this->redirect("zhengjian",array("userid"=>$userid,'p'=>$p,'type'=>$type),0.1,$message);
   }
}
