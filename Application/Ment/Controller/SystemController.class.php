<?php
namespace Ment\Controller;
use Think\Controller;
use Think\Page;
use Ment\Model\AdminModel;
use Ment\Model\UsergroupModel;
use Ment\Model\LandmarkModel;
use Think\MUpload;
class SystemController extends CommonController {
       //管理员列表
       function admin_list(){
           $Dao = M("admin");
           $where='';
           $count = $Dao->where($where)->count();
           $field="id,account,name,masgroup,flag,role,username,phone,note,email,ntime";
           $p = new Page($count,27);
           $page = $p->show();
           $list = $Dao->where($where)->limit($p->firstRow.','.$p->listRows)->select();
           $this->assign('page', $page);
           $this->assign('list', $list);
           $this->assign('p', $p->nowPage);
           $this->display();
       }
       //删除管理员
       function del_admin($account,$p){
           if(M('admin')->where("account='$account'")->delete()){
               $message='';
           }else{
               $message="<script>alert('删除失败')</script>";
           }
           $this->redirect('admin_list',array('p'=>$p),0,$message);
       }
       //改变是否是启用状态
       function changeflag($id,$flag,$p){
           if($flag=='y'){
               $flag='n';
           }else{
               $flag='y';
           }
           if(M('admin')->where("id=$id")->setField("flag",$flag)){
               $message='';
           }else{
               $message="<script>alert('修改失败')</script>";
           }
           $this->redirect('admin_list',array('p'=>$p),0.1,$message);
       }
       //添加管理员
       function add_admin(){
           if(!empty($_POST)){
               $ad=new AdminModel();
               $admin=$ad->create();
               if($admin){
                   $admin['pass']=mym($admin['pass'], $admin['account']);
                   $admin['ntime']=time();
                   if($ad->add($admin)){
                       $this->redirect("admin_list");
                   }
               }else{
                   $this->assign("errorInfo",$ad->getError());
               }
           }
           $group=M('usergroup')->field('id,groupname')->select();
           $this->assign('group',$group);
           $this->display();
       }
       //编辑管理员
       function edit_admin($account,$p){
           if(!empty($_POST)){
               if($_POST['pass']!=$_POST['oldpass']){
                   $_POST['pass']=mym($_POST['pass'], $_POST['account']);
               }
               $flag=M('admin')->save($_POST);
               if($flag){
                   $message="<script>alert('更新成功')</script>";
                   $this->redirect('admin_list',array('p'=>$p),0.1,$message);
               }else{
                   echo "<script>alert('更新失败')</script>";
               } 
           }
           $field='account,name,masgroup,flag,role,username,phone,note,email';
           $admin=M('admin')->where("account='$account'")->find();
           $group=M('usergroup')->field('id,groupname')->select();
           $this->assign('group',$group);
           $this->assign('admin',$admin);
           $this->assign('p',$p);
           $this->display();
       }
       //分配权限
       function allot($id,$p){
           if(isset($_GET['ids'])){
               $ids=(string)str_replace('/','',$_GET['ids']);
               if(M('admin')->where("id=$id")->setField("permissId",$ids)){
                   $message="<script>alert('分配成功')</script>";
                   $this->redirect('admin_list',array('p'=>$p),0.1,$message);
               }else{
                   echo "<script>alert('分配失败')</script>";
               }
           }
           //该用户已经有的权限
           $pers=M('admin')->field('account,username,permissId')->where("id=$id")->find();
           $per=explode(',',rtrim($pers['permissid'],','));
           $permiss=M('permissions')->field("id,premissname")->select();
           $this->assign('per',$per);
           $this->assign('pers',$pers);
           $this->assign('id',$id);
           $this->assign('p',$p);
           $this->assign('permiss',$permiss);
           $this->display();
       }
       //权限与组
       function per_group(){
           if(isset($_GET['type'])){
               $id=$_GET['id'];
               if($_GET['type']==0){
                   M('usergroup')->delete($id);
               }else{
                   M('permissions')->delete($id);
               }
           }
           //组
           $group=M('usergroup')->field('id,groupname')->select();
           //权限
           $per=M('permissions')->field('id,premissname')->select();
           $this->assign("group",$group);
           $this->assign("per",$per);
           $this->display();
       }
       //添加组和权限
       function add_group(){
           if(!empty($_POST)){
               if(isset($_POST['type'])){
                   $g=new UsergroupModel();
                   $url="add_permiss";
               }else{
                   $url="add_group";
                   $g=new UsergroupModel();
               }
               $group=$g->create();
               if($group){
                   if($g->add($group)){
                       $message="<script>alert('添加成功')</script>";
                       $this->redirect('per_group',array(),0.1,$message);
                   }else{
                        echo "<script>alert('添加失败')</script>";
                   }
               }else{
                   $this->assign("errorInfo",$g->getError());
               }
           }
           $this->display($url);
       }
       //基地管理
       function train_Manage(){
           //城市
           $city=M('citys')->field("id,cityname")->where("flag=1")->select();
           if(isset($_REQUEST['cityid'])){
               $cityid=$_REQUEST['cityid'];
           }else{
               $cityid=$city[0]['id'];
           }
           $Dao = M("train");
           $where="cityid=$cityid";
           $count = $Dao->where($where)->count();
           $field="id,trname,address";
           $p = new Page($count,27);
           $page = $p->show();
           $train = $Dao->field($field)->where($where)->limit($p->firstRow.','.$p->listRows)->select();
           $this->assign('page', $page);
           $this->assign('count', $count);
           $this->assign("city",$city);
           $this->assign("cityid",$cityid);
           $this->assign("p",$p->nowPage);
           $this->assign("train",$train);
           $this->display();
       }
       //添加基地
       function add_train($cityid){
           if(!empty($_POST)){
               if(M('train')->add($_POST)){
                   $message="<script>alert('添加成功')</script>";
                   $this->redirect("train_Manage",array("cityid"=>$cityid),0.1,$message);
               }else{
                   echo "<script>alert('添加失败')</script>";
               }
           }
           $this->assign("cityid",$cityid);
           $this->display();
       }
       //删除基地
       function del_train($id,$p,$cityid){
           if(M('train')->delete($id)){
               $message="";
               $tt=0;
           }else{
               $tt=0.1;
               $message="<script>alert('删除失败')</script>";
           }
           $this->redirect("train_Manage",array('cityid'=>$cityid,'p'=>$p),$tt,$message);
       }
       //地标管理
       function land_Manage(){
           //城市
           $city=M('citys')->field("id,cityname")->where("flag=1")->select();
           if(isset($_REQUEST['cityid'])){
               $cityid=$_REQUEST['cityid'];
           }else{
               $cityid=$city[0]['id'];
           }
           //区
           $county=M('countys')->field("id,countyname")->where("masterid=$cityid")->select();
           if(isset($_REQUEST['countyid'])){
               $countyid=$_REQUEST['countyid'];
           }else{
               $countyid=$county[0]['id'];
           }
           $land=M('landmark')->field("id,landname,longitude,latitude")->where("masterid='$countyid'")->select();
           $this->assign("land",$land);
           $this->assign("cityid",$cityid);
           $this->assign("city",$city);
           $this->assign("count",count($land));
           $this->assign("county",$county);
           $this->assign("countyid",$countyid);
           $this->display();
       }
       //返回区
       function returncounty($cityid){
           $county=M('countys')->field("id,countyname")->where("masterid=$cityid")->select();
           echo result($county);
       }
       //删除地标
       function del_land($id,$cityid){
           //删除地标
           if(M('landmark')->delete($id)){
               $message='';
               $tt=0;
           }else{
               $tt=0.1;
               $message="<script>alert('删除失败')</script>";
           }
           $this->redirect("land_Manage",array('cityid'=>$cityid),$tt,$message);
       }
       //修改密码
       function edit_pass(){
           if(!empty($_POST)){
               $newpass=$_POST['newpass'];
               $account=session('account');
               $pass=mym($newpass, $account);
               $id=session('id');
               if(M('admin')->where("id=$id")->setField("pass",$pass)){
                   $message="<script>alert('修改成功')</script>";
               }else{
                   $message="<script>alert('修改失败')</script>";
               }
               $this->redirect("Index/right",array(),0.1,$message);
           }
          $this->display();
       }
       //用户日志
       function user_log(){
           $Dao = M("userlog");
           $count = $Dao->count();
           $field="id,userid,ip,dotime,url,phone";
           $p = new Page($count,27);
           $page = $p->show();
           $list = $Dao->field($field)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
           foreach($list as $k=>$v){
               $userid=$v['userid'];
//               $list[$k]['nickname']=M('user')->field("nickname")->where("userid='$userid'")->find()['nickname'];
               $list[$k]['nickname']=M('user')->field("nickname")->where("userid='$userid'")->find();

           }
           $this->assign('page', $page);
           $this->assign('count', $count);
           $this->assign("p",$p->nowPage);
           $this->assign("list",$list);
           $this->display();
       }
       //后台日志
       function admin_log(){
           $Dao = M("adminlog");
           $count = $Dao->count();
           $field="id,account,info,ntime,nip";
           $p = new Page($count,27);
           $page = $p->show();
           $list = $Dao->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
           $this->assign('page', $page);
           $this->assign('count', $count);
           $this->assign("p",$p->nowPage);
           $this->assign("list",$list);
           $this->display();
       }
       //添加新地标
       //添加地标
       function addnewland($masterid){
           if(isset($_POST['landname'])){
//               $_POST['cityid']=M('countys')->field('masterid')->where("id=$masterid")->find()['masterid'];
               $_POST['cityid']=M('countys')->field('masterid')->where("id=$masterid")->find();
               if(M('landmark')->add($_POST)){
                   echo 0;
               }else{
                   echo 1;
               }
           }
       }
	 //咨询提问
      function consult_list(){
          $Dao = M("consult");
          $count = $Dao->count();
          $field="id,masterid,objectid,time,content,type";
          $p = new Page($count,27);
          $page = $p->show();
          $list = $Dao->field($field)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
          foreach($list as $k=>$v){
              $u=$v['masterid'];
              $o=$v['objectid'];
              $t=$v['type'];
              switch($t){
                  case 'jx':$table='school';break;
                  case 'jl':$table='coach';break;
                  case 'zdy':$table='guider';break;
              }
              $list[$k]['user']=M('user')->field("nickname,account")->where("userid='$u'")->find();
//              $list[$k]['coach']=M($table)->field("nickname")->where("userid='$o'")->find()['nickname'];
              $list[$k]['coach']=M($table)->field("nickname")->where("userid='$o'")->find();
          }
          $this->assign('list', $list);
          $this->assign('page', $page);
          $this->assign('count', $count);
          $this->assign("p",$p->nowPage);
          $this->assign("list",$list);
          $this->display();
      }      
	//订单评论
      function comment_list(){
          $Dao = M("evaluating");
          $count = $Dao->count();
          $field="id,masterid,objecthingid,score,time,content,type";
          $p = new Page($count,27);
          $page = $p->show();
          $list = $Dao->field($field)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
          foreach($list as $k=>$v){
              $u=$v['masterid'];
              $o=$v['objecthingid'];
              $t=$v['type'];
              switch($t){
                  case 'jx':$table='school';break;
                  case 'jl':$table='coach';break;
                  case 'zdy':$table='guider';break;
              }
              $list[$k]['user']=M('user')->field("nickname,account")->where("userid='$u'")->find();
//              $list[$k]['coach']=M($table)->field("nickname")->where("userid='$o'")->find()['nickname'];
              $list[$k]['coach']=M($table)->field("nickname")->where("userid='$o'")->find();
          }
          $this->assign('list', $list);
          $this->assign('page', $page);
          $this->assign('count', $count);
          $this->assign("p",$p->nowPage);
          $this->assign("list",$list);
          $this->display();
      }
      //删除评论
      function del_eval($id,$p,$y){
          if($y==1){
              $table='evaluating';
              $url='comment_list';
          }else{
              $table='consult';
              $url='consult_list';
          }
          if(M($table)->delete($id)){
            $message='';
            $t=0;
          }else{
            $message="<script>alert('删除失败');</script>";
            $t=0.1;
          }
          $this->redirect($url,array('p'=>$p),$t,$message);
      }       
      //推送消息
      function sendPush(){
          if(!empty($_POST)){
              //推送类型
              $message=$_POST['message'];
              $type=$_POST['type'];
              //是否存在用户类型
              if(isset($_POST['xtype'])){
                  $xtype=$_POST['xtype'];
                  switch ($xtype){
                      case 1:
                          //学员
                          $phone=$_POST['phone'];
//                          $userid=M('user')->field('userid')->where("account='$phone'")->find()['userid'];
                          $userid=M('user')->field('userid')->where("account='$phone'")->find();
                          send_admin($userid, $message,'');
                          break;
                      case 2:
                          //驾校个人
                          $phone=$_POST['phone'];
//                          $userid=M('school')->field('userid')->where("account='$phone'")->find()['userid'];
                          $userid=M('user')->field('userid')->where("account='$phone'")->find();
                          send_admin($userid, $message,'');
                          break;
                      case 3:
                          //驾校所有
                          $type='jx';
                          send_admin('', $message,$type);
                          break;
                      case 4:
                          //教练个人
                          $phone=$_POST['phone'];
//                          $userid=M('coach')->field('userid')->where("account='$phone'")->find()['userid'];
                          $userid=M('user')->field('userid')->where("account='$phone'")->find();
                          send_admin($userid, $message,'');
                          break;
                      case 5:
                          //教练所有
                          $type='jl';
                          send_admin('', $message,$type);
                          break;
                      case 6:
                          //指导员
                          $phone=$_POST['phone'];
//                          $userid=M('guider')->field('userid')->where("account='$phone'")->find()['userid'];
                          $userid=M('guider')->field('userid')->where("account='$phone'")->find();
                          send_admin($userid, $message,'');
                          break;
                      case 7:
                          $type='zdy';
                          send_admin('', $message,$type);
                          break;
                  }
              }else{
                  switch ($type){
                      case 1:
                            $type="all";
                          break;
                      case 2:
                          $type="xy";
                          break;
                      case 3:
                          $type="jld";
                          break;
                  }
                  send_admin('', $message,$type);
              }
              $_POST['type']=$type;
              $_POST['manager']=session('account');
              $_POST['ntime']=date('Y-m-d H:i:s');
              M('jpush')->add($_POST);
              echo "<script>alert('推送成功')</script>";
          }
          $this->display();
      }
	 //banner图片
	function banner(){
          if(!empty($_FILES)){
//              $id=M('banner')->field('max(id) as id')->find()['id'];
              $id=M('banner')->field('max(id) as id')->find();
              if($id){
                  $id=$id+1;
              }else{
                  $id=0;
              }
              $_POST['imgname']="activity_item_{$id}.png";
	      $img=move_uploaded_file($_FILES['file']['tmp_name'],"./Upload/Banner/activity_item_{$id}.png");
             if($img){
		 $_POST['manager'] = session('account');
             	 $_POST['ntime'] = date("Y-m-d H:i:s");
             	 if(M('banner')->add($_POST)){
              	    echo "<script>alert('添加成功')</script>";
             	 }else{
                    echo "<script>alert('上传失败')</script>";
              	 }
	     }
          }
          $banner=M('banner')->field('id,imgname,imgurl,ntime,manager')->select();
          $this->assign("banner",$banner);
          $this->display();
      }
	function delimg($id,$imgname){
          if(M('banner')->delete($id)){
              unlink('./Upload/Banner/'.$imgname);
              $message='';
              $t=0;
          }else{
              $message="<script>alert('删除失败')</script>";
              $t=0.1;
          }
          $this->redirect('banner',array(),$t,$message);
      }
}
