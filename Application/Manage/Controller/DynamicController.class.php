<?php
namespace Manage\Controller;
use Think\Controller;
use Org\Util\Page;
/**
 * 
 * @author 陈龙龙
 *  2016.7.25始 对学员的操作
 */
class DynamicController extends CommonController {
    public function newslist(){
		 $Data=M("news"); // 实例化Data数据对象
         $count=$Data->count();// 查询满足要求的总记录数 $map表示查询条件
         $Page=new Page($count,12);// 实例化分页类 传入总记录数
         $Page->nowPage=isset($_GET['p'])?$_GET['p']:1;//p是返回列表时需要传递的当前页数，就是返回列表时还在哪一页
         $show=$Page->show($Page->nowPage);//分页显示输出
         // 进行分页数据查询
         $list = $Data->field("userid,content,time,newsid,type")->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		 foreach($list as $k=>$v){
			    $userid=$v['userid'];
				$imgid=$v['newsid'];
				$table=$this->shenfen($v['type']);
				$list[$k]['user']=M($table)->field("nickname,img")->where("userid='$userid'")->find();
				$list[$k]['img']=M("newsimg")->field("imgname")->where("imgid=$imgid")->select();
		 }
		 $this->assign('list',$list);// 赋值数据集
         $this->assign('page',$show);// 赋值分页输出
         $this->assign('p',$Page->nowPage);// 第几页
         $this->assign('rowscount',$count);// 总得记录数
         $this->display(); //输出模板
	}
	public function delnews(){
		$newsid=I("get.newsid");
		$p=I("get.p");
		M("news")->where("newsid=$newsid")->delete();
		M("newsimg")->where("imgid=$newsid")->delete();
			  $u=U('newslist?&p='.$p);
              header("location:$u");
	}
}