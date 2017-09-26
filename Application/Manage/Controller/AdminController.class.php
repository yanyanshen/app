<?php
namespace Manage\Controller;
use Think\Controller;
use Org\Util\Page;
class AdminController extends CommonController {
   
   /**
    * 获得管理员列表
    */
    //-----------------------------------------8.8
   public function usermanage(){
       $Data=M("admin"); // 实例化Data数据对象
       $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
       $Page=new Page($count,10);// 实例化分页类 传入总记录数
       $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
       $show=$Page->show($Page->nowPage);//分页显示输出
       // 进行分页数据查询
       $list = $Data->field("id,account,masgroup,flag,role,username,phone,note,email,ntime")->order("id asc")->limit($Page->firstRow.','.$Page->listRows)->select();
       $this->assign('list',$list);// 赋值数据集
       $this->assign('page',$show);// 赋值分页输出
       $this->assign('count',$count);// 赋值分页输出
       $this->assign('p',$Page->nowPage);// 赋值分页输出
       $this->display(); // 输出模板
   }
   /**
    * 编辑管理员信息
    */
    public function userinfo($account){
		addlog("编辑{$account}用户信息");
        $adinfo=M('admin')->field('account,pass,username,masgroup,phone,email,role,flag,note,ntime')->where("account='$account'")->find();
        $this->assign("userinfo",$adinfo);
        $this->display();
    }
    
    //添加用户的界面
    public function useradd(){
        $this->display();
    }
    //实现添加用户
    public function adduser(){
        $account=I('post.account');
        if(M('admin')->where("account='$account'")->find()){
            echo 3;return;
        }
		$_POST['pass']=mym($_POST['pass'],$_POST['account']);
        foreach($_POST as $k=>$v){
            if($v==''){
                echo 2;
                return;
            }
        }
        if(M('admin')->add($_POST)){
			addlog("添加{$account}用户成功");
            echo 1;
        }else{
			addlog("添加{$account}用户失败");
            echo 0;
        }
    }
    //删除用户
    public function deluser(){
        $account=I('get.account');
        $p=I('get.p');
        if(M("admin")->where("account='$account'")->delete()){
			addlog("删除{$account}用户成功");
            $this->redirect("admin/usermanage?p={$p}");
        }else{
			addlog("删除{$account}用户失败");
            echo "<script>alert('删除失败');location.href='usermanage?p={$p}'</script>";
        }
    }
    //修改用户是否为启用状态
    public function updateflag(){
        $account=I('get.account');
        $flag=I('get.flag');$p=I('get.p');
        if($flag=='n'){
            $reflag='y';
        }else{
            $reflag='n';
        }
        if(M("admin")->where("account='$account'")->setField("flag",$reflag)){
			addlog("修改{$account}用户启用状态成功");
		}else{
			addlog("修改{$account}用户启用状态失败");
		}
         $this->redirect("usermanage?p={$p}");
    }
    //修改用户信息
    public function updateuser(){
        $account=I('post.account');
        $p=I("post.p");
		$_POST['pass']=mym($_POST['pass'],$account);
        if(M("admin")->where("account='$account'")->save($_POST)){
			addlog("修改{$account}用户信息成功");
            echo "<script>alert('更新成功');location.href='usermanage?p={$p}'</script>";
        }else{
			addlog("修改{$account}用户信息失败");
            echo "<script>alert('更新失败');location.href='userinfo?p={$p}&account={$account}'</script>";
             //$this->redirect("userinfo?p={$p}&account={$account}");
             //echo "<script>alert('更新失败')</script>";
        }
    }
	 //----------------------------------------------8.9
    //组列表
    public function groupmanage(){
        $Data=M("usergroup"); // 实例化Data数据对象
        $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
        $Page=new Page($count,10);// 实例化分页类 传入总记录数
        $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
        $show=$Page->show($Page->nowPage);//分页显示输出
        // 进行分页数据查询
        $list = $Data->field("id,groupname,description")->order("id asc")->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($list as $k=>$v){
            $id=$v['id'];
            $list[$k]['user']=M("admin")->field("username")->where("masgroup=$id")->select();
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('count',$count);// 赋值分页输出
        $this->assign('p',$Page->nowPage);// 赋值分页输出
        $this->display(); // 输出模板
    }
    //添加组
    public function addgroup(){
        $p=I('post.p');
        if(M('usergroup')->add($_POST)){
			addlog("添加{$_POST['groupname']}组成功");
            echo "<script>alert('添加成功');location.href='groupmanage?p={$p}'</script>";
        }else{
			addlog("添加{$_POST['groupname']}组失败");
            echo "<script>alert('添加失败');location.href='groupmanage?p={$p}'</script>";
        }
    }
    //删除组
    public function delgroup(){
        $id=I('get.id');
        $p=I('get.p');
        if(M('usergroup')->where("id=$id")->delete()){
			addlog("删除{$id}组成功");
            $this->redirect("groupmanage?p={$p}");
        }else{
			addlog("删除{$id}组失败");
            echo "<script>alert('删除失败');location.href='groupmanage?p={$p}'</script>";
        }
    }
    //组详情
    public function groupuser(){
        $id=I('get.id');
        $p=I('get.p');
        $group=M("usergroup")->field("id,groupname,description")->where("id=$id")->find();
		addlog("查看{$group['groupname']}组详情");
        $groups=M("usergroup")->field("id,groupname")->select();//找到所有的组
        $user=M("admin")->field("account,username")->where("masgroup=$id")->select();//找到该组所有的用户
        $this->assign("group",$group);
        $this->assign("groups",$groups);
        $this->assign("user",$user);
        $this->assign("p",$p);
        $this->assign("id",$id);
        $this->display();
    }
    //移至其他组
    public function  changegroup(){
        $masname=I('post.masname');
        unset($_POST['masname']);
        foreach($_POST as $k=>$v){
           if(!M("admin")->where("account='$v'")->setField("masgroup",$masname)){
               echo 0;break;
           }
		   addlog("移{$v}用户至{$masname}组");
        }
    }
	//-----------------------------------------------8.10---------------------------------------
    //权限管理列表
    public function permissmanage(){
        $Data=M("permissions"); // 实例化Data数据对象
        $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
        $Page=new Page($count,10);// 实例化分页类 传入总记录数
        $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
        $show=$Page->show($Page->nowPage);//分页显示输出
        // 进行分页数据查询
        $list = $Data->field("id,premissname,description")->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $k=>$v){
            $id=$v['id'];
            $list[$k]['user']=M("admin")->field("account,username")->where("permissId like '%$id%'")->select();
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('count',$count);// 赋值分页输出
        $this->assign('p',$Page->nowPage);// 赋值分页输出
        $this->display(); // 输出模板
    }
    //添加新权限
    public function permissadd(){
        $p=I('post.p');
        if(M('permissions')->add($_POST)){
			addlog("添加{$_POST['permissname']}成功");
            echo "<script>alert('添加成功');location.href='permissmanage?p={$p}'</script>";
        }else{
			addlog("添加{$_POST['permissname']}失败");
            echo "<script>alert('添加失败');location.href='permissmanage?p={$p}'</script>";
        }
    }
    //删除权限
    public function delpremiss(){
        $id=I('get.id');
        $p=I('get.p');
		$per=M('permissions')->field("permissname")->where("id=$id")->find()['permissname'];
        if(M('permissions')->where("id=$id")->delete()){
			addlog("删除{$per}权限成功");
            $this->redirect("permissmanage?p={$p}");
        }else{
			addlog("删除{$per}权限失败");
            echo "<script>alert('删除失败');location.href='permissmanage?p={$p}'</script>";
        }
    }
    //分配权限
    public function assignpermiss(){
		//addlog("查看权限列表");
        $account=I('get.account');
        $user=M("admin")->field("username,permissId")->where("account='$account'")->find();
        $permissId=rtrim($user['permissid'],',');//去掉最后的逗号
        $permiss=explode(',', $permissId);//
        $list=M("permissions")->field("id,premissname")->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign("user",$user);
        $this->assign("p",I('get.p'));
        $this->assign("account",$account);
        $this->assign("permiss",$permiss);
        $this->display(); // 输出模板
    }
    //更新用户的权限
    public function permissupdate(){
        $p=I('post.p');
        $account=I('post.account');
        $post='';
        unset($_POST['p']);unset($_POST['account']);
        foreach($_POST as $ki=>$v){
            $post.=$v.',';
        }
        if(M("admin")->where("account='$account'")->setField('permissId',$post)){
			addlog("更新{$account}权限成功");
            echo 1;
        }else{
			addlog("更新{$account}权限失败");
            echo 0;
        }
    }
	//--------------------------------------------------------------------------------8.15 日志管理
	public function adminlog(){
		$where='';
		foreach($_POST as $k=>$v){
			if($v!=null){
				switch($k){
					case "startdate":
						addlog("查看{$v}之后的日志");
						$v=strtotime($v);
						$where.="ntime>= '$v' and ";break;
					case "enddate":
						addlog("查看{$v}之前的日志");
						$v=strtotime($v);
						$where.="ntime<= '$v' and  ";break;
					default:
						addlog("查看用户{$v}的日志");
						$where.="account like '%$v%' and ";break;
				}
			}
		}
		$where =substr($where,0,strlen($where)-4);
	    $Data=M("adminlog"); // 实例化Data数据对象
        $count=$Data->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
        $Page=new Page($count,17);// 实例化分页类 传入总记录数
        $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
        $show=$Page->show($Page->nowPage);//分页显示输出
        // 进行分页数据查询
        $list = $Data->field("id,account,info,ntime,nip")->where($where)->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign("list",$list);
		$this->assign("count",$count);
		$this->assign("p",$Page->nowPage);
		$this->assign("page",$show);
		$this->display();
	}
	//日志管理
	public function deladminlog(){
		$id=I("get.id");
		$p=I("get.p");
		if(M('adminlog')->where("id=$id")->delete()){
			 $this->redirect("adminlog?p={$p}");
		}else{
			 echo "<script>alert('删除失败');location.href='adminlog?p={$p}'</script>";
		}
	}
}