<?php
namespace Ment\Controller;
use Think\Controller;
use Think\Page;
use Ment\Model\SchoolModel;
use Ment\Model\GuiderModel;
class GuiderController extends CommonController {
    function zdy_list(){
        $Dao = M("guider");
        $where='';
        if(!empty($_GET)){
            foreach($_GET as $key=>$val) {
                if($key=='p'){
                    continue;
                }elseif($key=='nickname'){
                    $where.="$key like '%".urldecode($val)."%' and ";
                   
                }else{
                    $where.="$key='".urlencode($val)."' and ";
                }
            }$where=rtrim($where,'and ');
        }
        $count = $Dao->where($where)->count();
        $field="id,account,img,nickname,sex,evalutioncount,praisecount,score,ntime,address,cityid,verify,lastupdate";
	$p = new Page($count,14);
        $page = $p->show();
        $list = $Dao->field($field)->where($where)->order("id asc")->limit($p->firstRow.','.$p->listRows)->select();
        foreach($list as $k=>$v){
            $userid=$v['userid'];
            $cityid=$v['cityid'];
            //订单个数
            $list[$k]['listcount']=M('list')->where("objectid='$userid'")->count();
            //预约个数
            $list[$k]['rescount']=M('reservation')->where("objectid='$userid'")->count();
            //学员个数
            $list[$k]['stucount']=M('student')->where("masterid='$userid'")->count();
            //课程个数
            $list[$k]['classcount']=M('trainclass')->where("masterid='$userid'")->count();
            //城市
            $list[$k]['city']=M('citys')->field('cityname')->where("id=$cityid")->find()['cityname'];
        }
        //城市
        $citys=M('citys')->field('id,cityname')->where("flag=1")->select();
        $this->assign('page', $page);
        $this->assign('count', $count);
        $this->assign('city', $citys);
        $this->assign('list', $list);
        $this->assign('p', $p->nowPage);
        $this->display();
    }
    //删除指导员
    function del_guider($id,$p){
        if(M('guider')->delete($id)){
            $message='';
            $tt=0;
        }else{
            $message="<script>alert('删除失败')</script>";
            $tt=0.1;
        }
        $this->redirect("zdy_list",array('p'=>$p),$tt,$message);
    }
    //更新指导员
    function zdy_info($id,$p){
        if(!empty($_POST)){
            if(M('guider')->save($_POST)){
                $message="<script>alert('更新成功');</script>";
                $this->redirect('zdy_list',array('p'=>$p),0.1,$message);
            }else{
                echo "<script>alert('更新失败');</script>";
            }
        }
	$field="account,pass,sex,nickname,birthday,driverId,signature,timeflag,ntime,driverage,teachedage,evalutioncount,praisecount,allcount,passedcount,address,cityid,introduction";
        $zdy=M('guider')->field($field)->where("id=$id")->find();
        $city=M('citys')->field('id,cityname')->where("flag=1")->select();
        $this->assign('city',$city);
        $this->assign('zdy',$zdy);
        $this->assign('p',$p);
        $this->assign('id',$id);
        $this->display();
    }
    //添加指导员
    function add_zdy(){
        if(!empty($_POST)){
            $zdy=new GuiderModel();
            $guider=$zdy->create();
            if($guider){
                $guider['userid']=guid();
                $guider['ntime']=date('Y-m-d H:i:s');
                if($zdy->add($guider)){
                    $this->redirect("zdy_list",array(),0.1,"<script>alert('添加成功')</script>");
                }else{
                    echo "<script>alert('添加失败')</script>";
                }
            }else{
                $this->assign("errorInfo",$zdy->getError());
            }
        }
        $city=M('citys')->field("id,cityname")->where("flag=1")->select();
        $this->assign("city",$city);
        $this->display();
    }
    
//     function left(){
//         //(1)根据管理员获得其角色，进而获得角色对应的权限
//         $admin_id=session('admin_id');//$admin_id=2
//         $manager_info=D('Manager')->find($admin_id);
//         $role_id=$manager_info['mg_role_id'];//$role_id=10
//         //(2)根据$role_id 获得本身记录信息
//         $role_info=D('Role')->find($role_id);
//         $auth_ids = $role_info['role_auth_ids'];//
//         // (3)根据$auth_ids获得具体权限
//         //$auth_info=D('Auth')->select($auth_ids);
//         $auth_infoA=D('Auth')->where("auth_level=0 and auth_id in ($auth_ids)")->select();//父级
//         $auth_infoB=D('Auth')->where("auth_level=1 and auth_id in ($auth_ids)")->select();//子级
//         $this->assign('auth_infoA',$auth_infoA);
//         $this->assign('auth_infoB',$auth_infoB);
//         $this->display();
//     }

//     function left(){
//         //(1)根据管理员获得其角色，进而获得角色对应的权限
//         $admin_id=session('admin_id');
//         dump($admin_id);exit;
//         $manager_info=D('Manager')->find($admin_id);
//         $role_id=$manager_info['mg_role_id'];
//         dump($role_id);exit;
//         //(2)根据$role_id 获得本身记录信息
//         $role_info=D('Role')->find($role_id);
//         dump($role_info);exit;
//         $auth_ids = $role_info[role_auth_ids];
//         dump($auth_ids);exit;
//         // (3)根据$auth_ids获得具体权限
//         //$auth_info=D('Auth')->select($auth_ids);
//         $auth_infoA=D('Auth')->where("auth_level=0 and auth_id in ($auth_ids)")->select();//父级
//         $auth_infoB=D('Auth')->where("auth_level=1 and auth_id in ($auth_ids)")->select();//子级
//         //dump($auth_infoA);exit;
//         $this->assign('auth_infoA',$auth_infA);
//         $this->assign('auth_infoB',$auth_infoB);
//         $this->display();
//     }
    function left(){
        //(1)根据管理员获得其角色，进而获得角色对应的权限
        $admin_id=session('admin_id');
        //dump($admin_id);exit;
        $manager_info=D('Manager')->find($admin_id);
        $role_id=$manager_info['mg_role_id'];
        //dump($role_id);exit;
         
        //(2)根据$role_id 获得本身记录信息
        $role_info=D('Role')->find($role_id);
    
        //dump($role_info);exit;
        $auth_ids = $role_info[role_auth_ids];
        //dump($auth_ids);exit;
         
        // (3)根据$auth_ids获得具体权限
        //$auth_info=D('Auth')->select($auth_ids);
         
        $auth_infoA=D('Auth')->where("auth_level=0 and auth_id in ($auth_ids)")->select();//父级
        $auth_infoB=D('Auth')->where("auth_level=1 and auth_id in ($auth_ids)")->select();//子级
        //dump($auth_infoA);exit;
        $this->assign('auth_infoA',$auth_infA);
        $this->assign('auth_infoB',$auth_infoB);
        $this->display();
    }
    
    
}
