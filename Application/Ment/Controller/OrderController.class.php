<?php
namespace Ment\Controller;
use Think\Controller;
use Think\Page;
class OrderController extends CommonController {
   //订单列表
   public function order_list(){
       if(isset($_GET['t'])){
           unset($_GET['t']);
           $id=$_GET['id'];
           unset($_GET['id']);
           $user=M('list')->field("masterid,objectid")->where("id=$id")->find();
           $stuid=$user['masterid']; $objid=$user['objectid'];
           M('list')->where("id=$id")->setField('Cl_type','y');
           M('student')->where("userid='$stuid'")->setField('Cl_type','y');
           send($objid,"您有1条新订单，点击查看",array('code'=>1003),'jl');
       }
       $Dao = M("list");
       $where='';
       if(!empty($_GET)){
           foreach($_GET as $key=>$val) {
               if($key=='p'){
                   continue;
               }
               if($key=='masterid'){
                   $where.="$key='".urlencode($val)."' and ";
               }elseif($key!='listname'&& $key!='masname'){
                   $where.="$key=".urlencode($val)." and ";
               }else{
                   $where.="$key like '%".urldecode($val)."%' and ";
               }
           }$where=rtrim($where,'and ');
       }
       $count = $Dao->where($where)->count();
       $p = new Page($count,27);
       if(!empty($_GET)){
           foreach($_POST as $key=>$val) {
                $p->parameter.= "$key=".urlencode($val)."&";
           }
       }
       $field="id,masterid,listid,mode,listtime,masname,listname,description,state,payment_type,customer,returndate,Cl_type,lastupdate";
       $page = $p->show();
       $list = $Dao->field($field)->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
       $list=order($list);
       $this->assign('count', $count);
       $this->assign('page', $page);
       $this->assign('list', $list);
       $this->assign('p', $p->nowPage);
       $this->display();
   }
   //只改变状态
   public function cl_list($cl,$p,$id,$u){
       if($cl=='n'){
           $cl='y';
       }else{
           $cl='n';
       }
	M('student')->where("userid='$u'")->setField('Cl_type',$cl);
       $result=M('list')->where("id=$id")->setField('Cl_type',$cl);
       if($result){
           $this->redirect('order_list',array('p'=>$p),0,'');
       }else{
           $this->redirect('order_list',array('p'=>$p),0,"<script>alert('更改失败')</script>");
       }
   }
   //订单详情
    public function list_info($id,$p){
        //保存订单状况信息 
        if(!empty($_POST)){
            $list=M('list')->save($_POST);
            return_result($list);
        }
	$field='id,listid,mode,state,returndate,listtime,payment_type,customer,masname,phone,address,note,objectid,description,trname,prices,total_fee,stucount,preferentialprice,classid';
        //订单详情
        $list=M('list')->field($field)->where("id=$id")->find();
        //客户姓名/电话
        $userid=$list['masterid'];
        $user=M("user")->field("nickname,phone")->where("userid='$userid'")->find();
        //城市
        $citys=M('citys')->field('id,cityname')->where("flag=1")->select();
        //跟单客服
        $customers=M('admin')->field('id,username')->select();
        //所报课程的全款价，全包价
        $tcid=$list['classid'];
        $price=M('trainclass')->field("officialprice,whole517price")->where("tcid=$tcid")->find();
        $listid=$list['listid'];
        //ajax
        $city=M('citys')->field("id,cityname")->where("flag=1")->select();
        $school=M('school')->field('userid,nickname')->where("cityid=1")->select();
        $coach=M('coach')->field('userid,nickname,jltype')->select();
        foreach ($coach as $k=>$v){
            switch($coach[$k]['jltype']){
                case 0: $coach0[]=$coach[$k];break;
                case 1: $coach1[]=$coach[$k];break;
                case 2: $coach2[]=$coach[$k];break;
                case 3: $coach3[]=$coach[$k];break;
            }
        }
        $guider=M('guider')->field('userid,nickname')->select();
        //跟单记录
        $jilu=M('listdocumentary')->field("id,documenttime,content,nextreturndate,operator")->where("listid=$listid")->order("id desc")->limit(5)->select();
        $class=returnclass($list['type'], $list['objectid']);
        $type=$list['type'];
        if($list['type']=='jl'){
            $coachid=$list['objectid'];
            $coach=M('coach')->field("masterid,userid")->where("userid='$coachid'")->find();
            $schoolid=$coach['masterid'];
            $type=$list['jltype'];
        }elseif ($list['type']=='jx'){
            $schoolid=$list['objectid'];
        }else{
            $schoolid='';
        }
        $this->assign("list",$list);
        $this->assign("class",$class);
        $this->assign("jilu",$jilu);
        $this->assign("price",$price);
        $this->assign("id",$id);
        $this->assign("city",$city);
        $this->assign("type",$type);
        $this->assign("school",$school);
        $this->assign("schoolid",$schoolid);
        $this->assign("guider",$guider);
        $this->assign("coach0",$coach0);
        $this->assign("coach1",$coach1);
        $this->assign("coach2",$coach2);
        $this->assign("coach3",$coach3);
        $this->assign("user",$user);
        $this->assign("p",$p);
        $this->assign("customers",$customers);
        $this->display();
    }
    //修改意向课程
    //返回驾校
    public function return_school($cityid){
        $data=M('school')->field('nickname')->where("cityid=$cityid")->select();
        echo result($data);
    }
    //返回课程
    public function return_class($nickname,$cityid){
//        $userid=M('school')->field('userid')->where("cityid=$cityid and nickname like '%$nickname%'")->find()['userid'];
        $userid=M('school')->field('userid')->where("cityid=$cityid and nickname like '%$nickname%'")->find();
        $data=M('trainclass')->field('tcid,name')->where("masterid='$userid'")->select();
        echo result($data);
    }
    //返回基地
    public function return_train($nickname,$cityid){
//        $userid=M('school')->field('userid')->where("cityid=$cityid and nickname like '%$nickname%'")->find()['userid'];
        $userid=M('school')->field('userid')->where("cityid=$cityid and nickname like '%$nickname%'")->find();
        $data=M('schooltrain s')->field('t.id,t.trname')->join("xueche1_train t on t.id=s.trainid and s.schoolid='$userid'")->select();
        echo result($data);
    }
    //返回教练
    public function return_coach($nickname,$cityid,$trainid){
        $userid=M('school')->field('userid')->where("cityid=$cityid and nickname like '%$nickname%'")->find();
        $data=M('coachtrain s')->field('c.userid,c.nickname')->join("xueche1_coach c on c.userid=s.coachid and s.trainid=$trainid and s.schoolid='$userid'")->select();
        echo result($data);
    }
    //设置下次回访日期
    public function returndate($id){
        unset($_POST['id']);
        $_POST['documenttime']=date('Y-m-d');
        $_POST['operator']=session('username');
        if(M('listdocumentary')->add($_POST)){
            $post['customer']=session('id');
            $post['returndate']=$_POST['nextreturndate'];
            $post['lastupdate']=session('username');
            M('list')->where("id=$id")->save($post);
            $message="<script>alert('添加成功')</script>";
        }else{
            $message="<script>alert('添加失败')</script>";
        }
        $this->redirect("list_info",array('id'=>$id,'p'=>$_POST['p']),0.1,$message);
    }
    //取消订单
    public function cencel_list($id,$p){
        if(M('list')->where("id=$id")->setField('state',4)){
            $message="<script>alert('取消成功')</script>";
        }else{
            $message="<script>alert('取消失败')</script>";
        }
        $this->redirect("list_info",array('id'=>$id,'p'=>$p),0.1,$message);
    }
    //返回json课程
    function returnclass($masterid){
        $data=M('trainclass')->field('tcid,name')->where("masterid='$masterid'")->select();
        echo result($data);
    }
    //返回价格
    function returnprices($tcid){
        $data=M('trainclass')->field("officialprice,whole517price")->where("tcid=$tcid")->find();
        echo result($data);
    }
    //保存课程
    function classs($id,$p,$type){
        switch($type){
            case 'jx':
                $post['objectid']=$_POST['jx'];break;
            case 0:
                $post['objectid']=$_POST['jl0'];break;
            case 1:
                $post['objectid']=$_POST['jl1'];break;
            case 2:
                $post['objectid']=$_POST['jl2'];break;
            case 3:
                $post['objectid']=$_POST['jl3'];break;
            case 'zdy':
                $post['objectid']=$_POST['zdy'];break;
        }
        if($_POST['tcid']!=''){
            $tcid=$_POST['tcid'];
            $post['classid']=$tcid;
//            $post['description']=M('trainclass')->field("name")->where("tcid=$tcid")->find()['name'];
            $post['description']=M('trainclass')->field("name")->where("tcid=$tcid")->find();
        }
        if(M('list')->where("id=$id")->save($post)){
            $message="<script>alert('保存成功')</script>";
        }else{
            $message="<script>alert('保存失败')</script>";
        }
        $this->redirect("list_info",array('id'=>$id,'p'=>$_POST['p']),0.1,$message);
    }
    //修改支付 这里只修改学员人数，和预付报名费
    function zhifu($id,$p,$stucount){
        if(M('list')->where("id=$id")->save($_POST)){
            $message="<script>alert('保存成功')</script>";
        }else{
            $message="<script>alert('保存失败')</script>";
        }
        $this->redirect("list_info",array('id'=>$id,'p'=>$_POST['p']),0.1,$message);
    }
    //飘红的新订单
    function newlist(){
        $data=M('list')->where("Cl_type='n'")->count();
        echo $data;
    }
    //未处理订单列表
    public function neworder_list(){
        $Dao = M("list");
        $where="Cl_type='n'";
        $count = $Dao->where($where)->count();
        $p = new Page($count,27);
        $field="id,mode,listtime,masname,listname,description,state,payment_type,customer,returndate,Cl_type,lastupdate";
        $page = $p->show();
        $list = $Dao->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
        $list=order($list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('list', $list);
        $this->assign('p',$p->nowPage);
        $this->display();
    }
	 function add_order(){
        if(!empty($_POST)){
            $phone=$_POST['phone'];
//            $_POST['masterid']=M('user')->field('userid')->where("account='$phone'")->find()['userid'];
            $_POST['masterid']=M('user')->field('userid')->where("account='$phone'")->find();
            $_POST['listtime']=date('Y-m-d H:i:s');
            $_POST['listid']=date('YmdHis'). substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            $m=M('list')->add($_POST);
            if($m){
                $message="<script>alert('添加成功')</script>";
            }else{
                $message="<script>alert('添加失败')</script>";
            }
        }
        $school=M('school s')->field("userid,nickname,cityname")->join("xueche1_citys c on c.id=s.cityid")->select();
        $coach=M('coach s')->field("userid,nickname,cityname")->join("xueche1_citys c on c.id=s.cityid")->select();
        $guider=M('guider s')->field("userid,nickname,cityname")->join("xueche1_citys c on c.id=s.cityid")->select();
        $this->assign('school',$school);
        $this->assign('coach',$coach);
        $this->assign('guider',$guider);
        $this->display();
    }
    function returnobj(){
       $json=file_get_contents("php://input");
       $obj=json_decode($json);
       if($obj->id==1){
           $table='school';
       }else if($obj->id==2){
           $table='coach';
       }else{
           $table='guider';
       }
       $cityid=$obj->cityid;
       $data=M($table)->field("userid,nickname")->where("cityid=$cityid")->select();
       echo result($data);
    }
    //返回基地
    function returntrain(){
        $json=file_get_contents("php://input");
        $obj=json_decode($json);
        $userid=$obj->userid;
        if($obj->type==1){
            $table="schooltrains";
            $field="schoolid";
            $class=$userid;
        }else if($obj->type==2){
            $table="coachtrains";
            $field="coachid";
            $jltype=M('coach')->field('masterid,jltype,boss')->where("userid='$userid'")->find();
            if($jltype['jltype']==0){
                $class=$jltype['masterid'];
            }else if($jltype['jltype']==3){
                $class=$jltype['boss'];
            }else{
                $class=$userid;
            }
        }else{
            $table="guidertrains";
            $field="guiderid";
            $class=$userid;
        }
        $data['trainclass']=M("trainclass")->field("tcid,name")->where("masterid='$class'")->select();
//        $train=M($table)->field("trainid")->where("$field='$userid'")->find()['trainid'];
        $train=M($table)->field("trainid")->where("$field='$userid'")->find();
        if($train){
            $train=explode(',',rtrim($train,','));
            foreach ($train as $k=>$v){
                $data['train'][$k]=M("train")->field("id,trname")->where("id=$v")->find();
            }
        }else{
            $data=array();
        }
        echo result($data);
    } 
}
