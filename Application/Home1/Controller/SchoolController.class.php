<?php
namespace Home1\Controller;
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
       $Data=M("school s"); // 实例化Data数据对象
       $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count,8);// 实例化分页类 传入总记录数
       $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
       $show=$Page->show($Page->nowPage);//分页显示输出
       //根据$schoolid 
       $schoolid=$Data->field("s.userid,s.cityid")->order("s.id")->limit($Page->firstRow.','.$Page->listRows)->select();
       // 进行分页数据查询
       $list = $Data->field("s.id,s.userid,s.nickname,s.img,s.phone,s.type,s.address,count(t.id) as traincount,s.connectteacher,s.cityid")
       ->join("left join xueche1_schooltrain t on t.schoolid=s.userid")->order("s.id")->limit($Page->firstRow.','.$Page->listRows)->group("s.userid")->select();
       foreach($schoolid as $k=>$v){
           $userid=$v['userid'];
           $cityid=$v['cityid'];
         //  $list[$k]['newscount']=M("news")->where("userid='$userid'")->count();//动态数
           $list[$k]['attcount']=M("attention")->where("objectid='$userid'")->count();//关注数
           $list[$k]['classcount']=M("trainclass")->where("masterid='$userid'")->count();//课程数
           $list[$k]['signcount']=M("sign")->where("masterid='$userid'")->count();//报名人数
           $list[$k]['cityname']=M("citys")->field("cityname")->where("id='$cityid'")->find()['cityname'];//所在城市
       }
       $this->assign('schoollist',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->assign('p',$Page->nowPage);// 赋值分页输出
       $this->assign('rowscount',$count);// 总得记录数
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
               echo "<script>alert('删除成功')</script>";
           }else{
               echo "<script>alert('删除失败')</script>";
           }
       }
       $nowpage=I('get.p');
       //驾校表
       $info=M("school")->field("account,userid,img,nickname,fullname,phone,pickrange,score,address,allcount,passedcount,price,evalutioncount,praisecount,introduction,cityid,connectteacher,timeflag")->where("userid='$userid'")->find();
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
       $counts=array_merge(array_diff($count,$id),array_diff($id,$count));
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
          echo 1;
      }else{
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
          echo 1;
      }else{
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
          echo 1;
      }else{
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
      $list = $Data->field("id,tcid,name,carname,officialprice,whole517price,prepay517price,waittime,finishtime")
      ->where("masterid='$userid'")->order("id")->limit($Page->firstRow.','.$Page->listRows)->select();
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
          echo $_POST['masterid'];
      }else{
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
      foreach($img as $k=>$v){
          $img[$k]['name']=preg_replace("/\/upload\/school\/small\//i", "", $v['imgname']);//i表示大小写
      }
      $list='';
      switch($type){
          case 'jx':
              $list="schoollist?p={$p}";
              break;
          case 'jl':
              $list="Coach/coachlist?p={$p}";
              break;
          case 'zdy':
              $list="Guider/guiderlist?p={$p}";
              break;
      }
      $this->assign("nickname",$nickname);
      $this->assign("img",$img);
      $this->assign("i",$i);
      $this->assign("type",$type);
      $this->assign("list",$list);
      $this->assign("userid",$userid);
      $this->assign("p",$p);
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
      $t=I('get.t');//通过t来判断是上传头像还是上传图片
      if($t==1){
          $return=$this->dirup($userid);
         if($return==''){
             $u=U('imgmanage?userid='.$userid.'&type='.$type.'&t=2');
         }else{
             $table=$this->shenfen($type);M($table)->where("userid='$userid'")->setField('img',$return);
             $u=U('imgmanage?userid='.$userid.'&type='.$type.'&t=1');
         }
      }else if($t==2){
          $c=M("img")->where("userid='$userid'")->count();
          if($c>=9){
              $u=U('imgmanage?userid='.$userid.'&type='.$type.'&t=3');
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
              $u=U('imgmanage?userid='.$userid.'&type='.$type.'&t=2');
          }else{
            foreach ($mm as $k => $v) {
                $mm[$k]['userid'] = $userid;
                $mm[$k]['imgtime'] = date("Y-m-d H:i:s");
            }M("img")->addAll($mm);
              $u=U('imgmanage?userid='.$userid.'&type='.$type.'&t=1');
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
            $push=pathinfo($_FILES['ImageFiled']['name'],PATHINFO_EXTENSION);
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
        //如果存在tid，说明用户在该基地删除某个教练
        if(isset($_GET['tid'])){
            $id=$_GET['tid'];
            if(M("coachtrain")->where("id=$id")->delete()){
                echo "<script>alert('删除成功')</script>";   
            }else{
                echo "<script>alert('删除失败')</script>";
            }
        }
        $userid=I('get.userid');
        $trainid=I('get.trainid');
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
          echo 1;
      }else{
          echo 0;
      }
    }
}