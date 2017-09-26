<?php
namespace Admin\Controller;
use Think\Controller;
/**
 *
 * @author webrx
 *         驾考学堂
 *        
 */
class DrivingController extends CommonController
{
  public function dirsch($userid)
    {
        try {
            $data = M('drisch')->field("myreservation,myspeed,myscnum,myrenum,myconum,myspnum,mygunum,myschoolid,mycoachid,myguiderid,classid,classjlid,classzdyid")
                ->where("userid='$userid'")
                ->find();
            $myschoolid = $data['myschoolid'];
            $mycoachid = $data['mycoachid'];
            $myguiderid = $data['myguiderid'];
            $data['myschool'] = M("school")->field("nickname")
                ->where("userid='$myschoolid'")
                ->find()['nickname'];
            $data['mycoach'] = M("coach")->field("nickname")
                ->where("userid='$mycoachid'")
                ->find()['nickname'];
            $data['myguider'] = M("guider")->field("nickname")
                ->where("userid='$myguiderid'")
                ->find()['nickname'];
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    public function mydrisch($jtype='C1',$userid='1ACCA599-8206-F1DA-90D3-BCFC6243E415',$classid='1',$myschoolid='D18CF959-EDB7-B2C2-C8D0-85CFB1415721')
    {
        try {
            // 获得驾校的名字
            $data['school'] = M('school')->field("img,nickname,timeflag")->where("userid='$myschoolid'")->find();
            if (M('attention')->where("userid='$userid' and objectid='$myschoolid'")->count() == 1) {
                $data['school']['flag'] = 1;
            } else {
                $data['school']['flag'] = 0;
            }
            // 获取课程信息
            if ($classid ==0) {
		$data['class']=null;
            } else {
                $data['class'] = M('trainclass')->field("tcid,masterid,jtype,name,carname,mode,officialprice,whole517price,prepay517price,prepay517deposit,classtime")
                    ->where("masterid='$myschoolid' and tcid=$classid ")
                    ->find();
            }
            // 获取该驾校的训练场
                $data['train'] = M('schooltrain s')->field("t.trname,t.id,t.address")
                    ->join("xueche1_train t on s.trainid=t.id and s.schoolid='$myschoolid' ")
                    ->select();//dump($data);
                // 预约教练
                $field = "c.img,c.nickname,c.birthday,c.timeflag,c.userid,c.score,c.teachedage,c.jtype,c.passedcount,c.masterid,c.allcount,c.praisecount,c.evalutioncount,c.teachingcount,c.trainid,t.subjects";
                // 对基地教练人数的多少进行排序
                for ($i = 0; $i < count($data['train']); $i ++) {
                    for ($j = 0; $j < count($data['train']) - $i - 1; $j ++) {
                        $jj = $data['train'][$j]['id'];
                        $jjj = $data['train'][$j + 1]['id'];
                        $cc = M('coachtrain')->where("schoolid='$myschoolid' and trainid=$jj and jtype='$jtype'")->count();
                        $ccc = M('coachtrain')->where("schoolid='$myschoolid' and trainid=$jjj and jtype='$jtype'")->count();
                        if ($ccc > $cc) {
                            $t = $data['train'][$j];
                            $data['train'][$j] = $data['train'][$j + 1];
                            $data['train'][$j + 1] = $t;
                        }
                    }
                }//dump($data);
                foreach ($data['train'] as $k => $v) {
                    $id = $data['train'][$k]['id'];
                    if (M('coachtrain')->where("schoolid='$myschoolid' and trainid=$id")->find()) {
			
                        $data['train'][$k]['coachcount'] += M('coachtrain')->where("schoolid='$myschoolid' and trainid=$id and jtype='$jtype'")->count();//统计每个基地的教练人数
                        $data['coach'][$k]= M('coach c')->field($field)->join("xueche1_coachtrain t on t.trainid=$id  and t.coachid=c.userid and t.schoolid='$myschoolid' and  t.jtype='$jtype'")
                            ->select();
                        foreach ($data['coach'] as $kk => $vv) {
                            $data['train'][$k]['stucount'] += $data['coach'][$kk]['teachingcount'];
                        }
                        $data['coach']['index'] = $data['train'][0]['id'];
                        if ($k > 0) {
                            unset($data['coach'][$k]);
                        }
                        $data['coach'][0] = array_slice($data['coach'][0], 0, 5);
                    } else {
				
                        $data['train'][$k]['coachcount'] = 0;
                        $data['coach']=[];
                        $data['train'][$k]['stucount'] = 0;
                    }
                }
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }

    /**
     * 我的驾校里面的加载更多训练场
     */
   public function getMoreCoach($start,$schoolid,$jtype,$subjects,$trainid)
    {
        try {
            $field = "c.img,c.nickname,c.timeflag,c.userid,c.score,c.teachedage,c.passedcount,c.masterid,c.allcount,c.praisecount,c.evalutioncount,c.teachingcount,c.trainid,t.subjects";
            $data = M('coach c')->field($field)->join("xueche1_coachtrain t on t.trainid=$trainid  and t.coachid=c.userid and t.schoolid='$schoolid' and t.subjects=$subjects and t.jtype='$jtype'")
                ->limit("$start,10")
                ->select();
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    // 根据用户传过来的基地，驾校，科目来提供教练
    public function getTrainCoach()
    {
        try {
            $trainid = $_POST['trainid']; // 用户传过来的基地的id
            $schoolid = $_POST['schoolid'];
            $field = "c.img,c.nickname,c.timeflag,c.birthday,c.userid,c.score,c.teachedage,c.passedcount,c.jtype,c.masterid,c.allcount,c.praisecount,c.evalutioncount,c.teachingcount,t.trainid,t.subjects";
            $data = M('coach c')->field($field)
                ->join("xueche1_coachtrain t on t.trainid=$trainid  and t.coachid=c.userid and t.schoolid='$schoolid'")
                ->limit(5)
                ->select();
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
  public function addMyTeacher()
    {
        try {
            $userid = $_POST['userid'];
            $objectid = $_POST['objectid'];
            $type = $_POST['type'];
            if (M('drisch')->where("userid='$userid'")->count() == 0) {
                M('drisch')->add($_POST);
            }
            switch ($type) {
                case 'jx':
                    $field = 'myschoolid';
                    break;
                case 'jl':
                    $field = 'mycoachid';
                    break;
                case 'zdy':
                    $city = $_POST['city'];
                    $field = 'myguiderid';
                    break;
            }
            if (M('drisch')->where("userid='$userid'")->setField($field, $objectid)) {
                $info = type(0, "添加成功");
            } else {
                $info = type(2, "添加失败");
            }
        } catch (Exception $e) {
            $info = type(1, "添加失败");
        }
        echo $info;
    }
 public function getCoachinfo($userid,$mycoachid,$classid,$date)
    {
        try {
            // 获得教练的基本信息
            $field = "nickname,img,userid,teachedage,masterid,driverage,allcount,passedcount,teachablecount,teachingcount,pricetwo,pricethree,evalutioncount,praisecount,timeflag,piccount,jtype";
            $coach['coach'] = M('coach')->field($field)
                ->where("userid='$mycoachid'")
                ->find();
$jtype=$coach['coach']['jtype'];
            $schoolid = $coach['coach']['masterid'];
            // 返回所属驾校的名字
            $coach['coach']['mastername'] = M("school")->field("nickname")
                ->where("userid='$schoolid'")
                ->find()['nickname'];
            $coach['coach']['counttime'] = 90;
            if ($classid ==0) {
                $coach['class'] = null;
            } else {
                $coach['class'] = M("trainclass")->field("tcid as classid,name,carname,include,mode,picktype,traintype,officialprice,whole517price,prepay517price,objectthirdcount,prepay517deposit,classtime")->where("tcid=$classid")
                    ->find();
            }
            if (M('attention')->where("userid='$userid' and objectid='$mycoachid'")->count() == 1) {
                $coach['flag'] = 1;
            } else {
                $coach['flag'] = 0;
            }
			 // 训练场
            $coach['train'] = M("train t")->field("t.trname,t.id")
                ->join(" xueche1_coachtrain c on c.coachid='$mycoachid' and c.trainid=t.id and c.jtype='$jtype' and c.schoolid='$schoolid'")
                ->find();
            if ($coach['coach']['timeflag'] == 0) {
                echo result(0, $coach);
                return;
            }
            // 教练的预约情况
            $fields = "r.masterid,r.num,c.nickname";
                // 其他日期
                $reser = M('reservation r')->field($fields)
                ->join("xueche1_user c on c.userid=r.masterid and r.objectid='$mycoachid' and r.stamp='$date' ")
                ->select();
            if($coach['coach']['jltype']==0 || $coach['coach']['jltype']=3){
                $count=M('price')->where("masterid='$mycoachid' and jtype='$jtype'")->find();
                if(empty($count)){
                    if($coach['coach']['jltype']==0){
                        $mycoachid=$schoolid;
                    }else{
                        $mycoachid=M('coach')->field("boss")->where("userid='$mycoachid'")->find()['boss'];
                    }
                }
            }
            $coach['price']=M("price")->field("id,weekdays,timebucket,price,jtype,subjects")->where("masterid='$mycoachid' and jtype='$jtype'")->select();
	    $coach['reser'] = $reser;
            $coach['date'] =$date;
            $info = result(0, $coach);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    // 从我的驾校点击教练
     public function reserCoach($jtype='C1',$userid,$objectid,$date)
    {
        try {
            // 获得教练的基本信息
            $field = "nickname,img,userid,teachedage,masterid,driverage,allcount,passedcount,teachablecount,teachingcount,pricetwo,pricethree,evalutioncount,praisecount,timeflag,piccount,jtype";
            $coach['coach'] = M('coach')->field($field)
                ->where("userid='$objectid'")
                ->find();
            $schoolid = $coach['coach']['masterid'];
            // 返回所属驾校的名字
            $coach['coach']['mastername'] = M("school")->field("nickname")
                ->where("userid='$schoolid'")
                ->find()['nickname'];
            $coach['coach']['counttime'] = 90;
            if ($coach['coach']['timeflag'] == 0) {
                echo result(0, $coach);
                return;
            }
            if (M('attention')->where("userid='$uesrid' and objectid='$objectid'")->count() == 1) {
                $coach['flag'] = 1;
            } else {
                $coach['flag'] = 0;
            }
            // 教练的预约情况
            $fields = "r.masterid,r.num,c.nickname";
                $reser = M('reservation r')->field($fields)
                                     ->join("xueche1_user c on c.userid=r.masterid  and r.stamp='$date'")
                                     ->select();
            $counts=M("price")->where("masterid='$objectid' and jtype='$jtype'")->find();
            if(empty($counts)){
                $coach['price']=M("price")->field("id,weekdays,timebucket,price,jtype,subjects")->where("masterid='$schoolid' and jtype='$jtype'")->select();
            }else{
                $coach['price']=M("price")->field("id,weekdays,timebucket,price,jtype,subjects")->where("masterid='$objectid' and jtype='$jtype'")->select();
            }
	    // 一天之内不能被预约的时间
            $coach['reser'] = $reser;
            $coach['date'] = date("Y-m-d");
            // 训练场
            $coach['train'] = M("train t")->field("t.trname,t.id")
                ->join(" xueche1_coachtrain c on c.coachid='$objectid' and c.trainid=t.id   and c.schoolid='$schoolid'")
                ->find();
            $info = result(0, $coach);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    /**
     * 我的指导员
     */
   public function getGuiderinfo()
    {
        try {
            $userid= $_POST['userid'];
			$objectid=$_POST['myguiderid'];
			if($objectid=='0'){
			    echo type(2, "还没有指导员");
			    return;
			}
            // 获得指导员的基本信息
            $field = "userid,nickname,img,teachedage,driverage,allcount,passedcount,teachablecount,jtype,teachingcount,evalutioncount,praisecount,timeflag,piccount";
            $guider = M('guider')->field($field)
                ->where("userid='$objectid'")
                ->find();
            if (M('attention')->where("userid='$userid' and objectid='$objectid'")->count() == 1) {
                $guider['flag'] = 1;
            } else {
                $guider['flag'] = 0;
            }
            $info = result(0, $guider);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
//--------------------------------------------9.13开始
public function returnGuiderRes($guiderid,$date,$jtype){
        try {
            $coach['price']=trainprice($guiderid,$jtype);
            // 教练的预约情况
            $fields = "r.masterid,r.num,c.nickname";
            $reser = M('reservation r')->field($fields)
            ->join("xueche1_user c on c.userid=r.masterid and r.objectid='$guiderid' and r.stamp='$date'")
            ->select();
            // 一天之内不能被预约的时间
            $coach['reser'] = $reser;
            $info=result(0, $coach);
        } catch (Exception $e) {
            $info=result(1, "返回失败");
        }
        echo $info;
    }     

    public function updateTeacher($userid='8CF687BE-A1AF-5F50-5C3F-C051BB0218A7',$type='jx',$objectid='25A2AD99-C4EC-BC54-C629-AEB1949D4AEE')
    {
        try {
            switch ($type) {
                case 'jl':
                    $field['mycoachid'] =$objectid;
		    $field['classjlid']=0;
                    break;
                case 'jx':
		    $field['myschoolid'] =$objectid;
                    $field['classid']=0;
                    break;
                case 'zdy':
		     $field['myguiderid'] =$objectid;
                    $field['classzdyid']=0;
                    break;
            }
            if (M('drisch')->where("userid='$userid'")->save($field)) {
		
                $info = type(0, "更换成功");
            } else {
                $info = type(2, "更换失败");
            }
        } catch (Exception $e) {
            $info = type(1, "更换失败");
        }
        echo $info;
    }
    /**
     * @接口：驾考学堂-我的预约接口
     */
    public function myreser()
    {
        try {
            $userid = $_POST['userid'];
            $field = "objectid,duration,status,prices,paidnum,retime,type";
            $reser = M("reservation r")->field($field)
                ->where("masterid='$userid'")
                ->select();
            foreach ($reser as $k=>$v){
                $table=shenfen($v['type']);
                $objid=$v['objectid'];
                $v['nickname']=M($table)->field("nickname")->where("userid='$objid'")->find()['nickname'];
            }
            $info = result(0, $reser);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    public function addmyreser()
    {
        try {
            $listcount=M('list')->count();
            $countcus=M('admin')->where("masgroup=4")->count();
            $_POST['coach']=$_POST['objectid'];
            $_POST['coachname']=$_POST['listname'];
            if($listcount==0){
                $_POST['customer']=M('admin')->where("masgroup=4")->field("id")->find()['id'];
            }else if($listcount<$countcus){
                $id=M('list')->field("customer")->order("id desc")->find()['customer'];
                $_POST['customer']=M('admin')->where("masgroup=4")->field("id")->where("id>$id")->find()['id'];
            }else{
                $t=array();
                $cuscount=M("list")->field("customer,count(*) as customerid")->group("customer")->select();
                $count=count($cuscount);
                for($i=0;$i<$count;$i++){
                    for($j=0;$j<$count-$i-1;$j++){
                        if($cuscount[$j]['customerid']>$cuscount[$j+1]['customerid']){
                            $t=$cuscount[$j+1];
                            $cuscount[$j+1]=$cuscount[$j];
                            $cuscount[$j]=$t;
                        }
                    }
                }
                $_POST['customer']=$cuscount[0]['customer'];
            }
            $masterid = $_POST['masterid'];
            $_POST['coach']=$_POST['objectid'];
            $_POST['coachname']=$_POST['listname'];
            $time=date('Y-m-d H:i:s');
            $id=$_POST['customer'];
            $_POST['lastupdate']=M("admin")->field("username")->find($id)['username'];
            $_POST['returndate']=date("Y-m-d");
            $_POST['listid'] = date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            $_POST['retime'] = $_POST['listtime'] = date('Y-m-d H:i:s');
            if (M("list")->add($_POST) && M("reservation")->add($_POST)) {
                $field="l.listname,l.objectid,l.phone,l.masterid,l.id,l.trname,l.masname,l.stucount,l.applymode,l.prices,l.address,l.remark,l.mode,l.description,l.total_fee,l.couponmode,l.listid,l.listtime,l.state,l.classid,l.description,l.flag,l.preferentialprice,r.duration,r.masname,r.num,r.retime as time";
                $list = M('list l')->field($field)
                    ->join("xueche1_reservation r on r.masterid=l.masterid and  l.masterid='$masterid'")
                    ->order("l.id desc")
                    ->find();
		    $objectid=$_POST['objectid'];
                    $stu['userid']=$masterid;
                    $stu['masterid']=$objectid;
                    $stu['truename']=$_POST['masname'];
                    $stu['phone']=$_POST['phone'];
                    $stu['address']=$_POST['address'];
                    //先看学员表里面有没有
                    if(M('student')->where("userid='$masterid' and masterid='$objectid'")->find()){
                        M('student')->where("userid='$masterid'")->save($stu);
                    }else{
                        M('student')->add($stu);
                    }
                $info = result(0, $list);
            } else {
                $info = type(2, "预约失败");
            }
        } catch (Exception $e) {
            $info = type(1, "预约失败");
        }
        echo $info;
    }
	  public function mylist()
    {
        try {
  //先看数据库已有的订单数量有没有超过客服的人数
            $listcount=M('list')->count();
            $countcus=M('admin')->where("masgroup=4")->count();
            $_POST['coach']=$_POST['objectid'];
            $_POST['coachname']=$_POST['listname'];
            if($listcount==0){
                $_POST['customer']=M('admin')->where("masgroup=4")->field("id")->find()['id'];
            }else if($listcount<$countcus){
                $id=M('list')->field("customer")->order("id desc")->find()['customer'];
                $_POST['customer']=M('admin')->where("masgroup=4")->field("id")->where("id>$id")->find()['id'];
            }else{
                $t=array();
                $cuscount=M("list")->field("customer,count(*) as customerid")->group("customer")->select();
                $count=count($cuscount);
                for($i=0;$i<$count;$i++){
                    for($j=0;$j<$count-$i-1;$j++){
                        if($cuscount[$j]['customerid']>$cuscount[$j+1]['customerid']){
                            $t=$cuscount[$j+1];
                            $cuscount[$j+1]=$cuscount[$j];
                            $cuscount[$j]=$t;
                        }
                    }
                }
                $_POST['customer']=$cuscount[0]['customer'];
            }
			$time=date('Y-m-d H:i:s');
			$id=$_POST['customer'];
			$_POST['lastupdate']=M("admin")->field("username")->where("id=$id")->find()['username'];
			$_POST['returndate']=date("Y-m-d");
            $userid = $_POST['masterid'];
            $_POST['listid'] = date('YmdHis'). substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            $_POST['listtime'] =$time ;
            unset($_POST['session_id']);
			if (M('list')->add($_POST)) {
                foreach ($_POST as $k => $v) {
                    $field .= $k . ',';
                }
                $field .= "state,flag,preferentialprice";
                $list = M('list')->field($field)
                    ->where("masterid='$userid'")
                    ->order("id desc")
                    ->find();
		$objectid=$_POST['objectid'];
                $stu['userid']=$userid;
                $stu['masterid']=$objectid;
                $stu['truename']=$_POST['masname'];
                $stu['phone']=$_POST['phone'];
                $stu['address']=$_POST['address'];
                //先看学员表里面有没有
                if(M('student')->where("userid='$userid' and masterid='$objectid'")->find()){
                    M('student')->where("userid='$userid'")->save($stu);
                }else{
                    M('student')->add($stu);
                }
                $info = result(0, $list);
            } else {
                $info = type(2, "订单创建失败");
            }
        } catch (Exception $e) {
            $info = type(1, "订单创建失败");
        }
        echo $info;
    }
    public function listcenter($masterid,$start,$mode,$state)
    {
		try{
            $fields = "l.listname,l.objectid,l.phone,l.masterid,l.id,l.masname,l.stucount,l.trname,l.applymode,l.prices,l.address,l.remark,l.mode,l.description,l.total_fee,l.couponmode,l.listid,l.listtime,l.type,l.state,l.classid,l.description,l.flag,preferentialprice,l.gmt_payment as paytime";
            $where = "";
            if ($state < 5) {
                $where .= "l.state=$state and ";
            }
            $where .= "l.masterid='$masterid' and l.mode=$mode and l.flag=1";
		    $data = M("list l")->field($fields)
				->where(" $where")
				->order("l.id desc")
				->limit("$start,10")
				->select();
		  if($mode !=4){
			  foreach($data as $k=>$v){
				  $tcid=$v['classid'];
				  $data[$k]['classname']=M("trainclass")->field("name")->where("tcid=$tcid")->find()['name'];
			  }
		  }else{
			    foreach($data as $k=>$v){
				  $data[$k]['classname']=$data[$k]['description'];
			  }
		  }
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    /**
     * 删除订单
     */
    public function dellist($masterid,$listid)
    {
        // 删除订单并不是真的删除，只是把它的flag变为0，
        try {
            if (M('list')->where("masterid='$masterid' and listid='$listid'")->setField("flag", 0)) {
                $info = type(0, "订单删除成功");
            } else {
                $info = type(2, "订单删除失败");
            }
        } catch (Exception $e) {
            $info = type(1, "订单删除失败");
        }
        echo $info;
    }
    /**
     * 取消订单
     */
  public function cancellist($listid,$mode)
    {
        try {
            if (M('list')->where("listid='$listid'")->setField("state", 4)) {
                if($mode==4){
                    M('reservation')->where("listid='$listid'")->delete();
                }
                $info = type(0, "订单取消成功");
            } else {
                $info = type(2, "取消失败");
            }
        } catch (Exception $e) {
            $info = type(1, "取消失败");
        }
        echo $info;
    }
    public function confirmlist($listid='2016111415121410151541')
    {
        try {
            if (M('list')->where("listid='$listid'")->setField("state", 2)) {
		M('reservation')->where("listid='$listid'")->setField('statuss',2);
                $info = type(0, "确认成功");
            }
        } catch (Exception $e) {
            $info = type(1, "确认失败");
        }
        echo $info;
    }
    public function myspeed($userid)
    {
        try {
            // 返回考试进度
            $myspeed = M('drisch')->field("myspeed")
                ->where("userid='$userid'")
                ->select();
            // 考试记录暂未返回
            $info = result(0, $myspeed);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    /**
     * 接口：自学直考（没有教练，只有指导员）
     */
    public function selfStudy($userid)
    {
        try {
            $myself = M("drisch")->field("myguider")
                ->where("userid='$userid'")
                ->select();
            $info = result(0, $myself);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    public function getAllnews($masterid,$start)
    {
        try {
            $data = M('news')->field("id as newsid,userid as masterid,time,content,piccount,evalutioncount,praisecount,transmitcount,extras")
                ->order("id desc")
                ->limit("$start 10")
                ->select();
            foreach ($data as $k => $v) {
                $id = $v['newsid'];
                $pid = $v['newsid']; // --循环得到那条动态的id
                $userid = $v['masterid'];
                $data[$k]['nickname'] = M('user')->field("nickname")
                    ->where("userid='$userid'")
                    ->select()[0]['nickname'];
                $data[$k]['imgname'] = C("conf.ip") . M('img')->field("imgname")
                    ->where("userid=$id")
                    ->select();
                $data[$k]['flag'] = 0;
                if (M("praise")->where("objectid=$pid and masterid='$masterid'")->count() == 1) {
                    $data[$k]['flag'] = 1; // 表示已经点过赞了
                }
            }
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    public function evaluatlist($objectid)
    {
        try {
            $_POST['time'] = date('Y-m-d H:i:s');
            if (M('evaluating')->where("objectid='$objectid'")->count()<=2) {
				if(M('evaluating')->add($_POST)){
					$type=I("post.type");
					$evaluate=I("post.evaluate");
					$objecthingid=$_POST['objecthingid'];//驾校的id
					$table=shenfen($type);
					M('list')->where("listid='$objectid'")->setField("state", 3);
					M($table)->where("userid='$objecthingid'")->setInc("evalutioncount",1);
					if($evaluate==1){
						send($objecthingid,"您有一条新评论",array("code"=>1007),'jl');
						M($table)->where("userid='$objecthingid'")->setInc("praisecount",1);
					}
					$info = type(0, "评价成功");
				}else{
					$info = type(2, "评价失败");
				}
            }else{
				 $info = type(3, "评价失败,最多评价2条");
			}
        } catch (Exception $e) {
            $info = type(1, "评论失败");
        }
        echo $info;
    }
    public function returnAllTeacher($type)
    {
        try {
            switch ($type) {
                case 'jl':
                    $table = "coach";
                    $masterid = $_POST['schoolid'];
                    break;
                case 'jx':
                    $table = "school";
                    break;
                case 'zdy':
                    $table = "guider";
                    break;
            }
            if ($type == 'jl') {
                $data = M($table)->field("userid,nickname")
                    ->where("masterid='$masterid'")
                    ->select();
            } else {
		if(isset($_POST['city'])){
			$city=$_POST['city'];
		}else{
		$city=$_POST['address'];
		}
		 $cityid=M("citys")->field("id")->where("cityname like '%$city%'")->find()['id'];
                $data = M($table)->field("userid,nickname")->where("cityid=$cityid")->select();
            }
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, "返回错误");
        }
        echo $info;
    }
    // 教练添加不可被预约的时间
    public function addDisable()
    {
        try {
            $userid = $_POST['userid']; // 教练自己的id
            $time = $_POST['time']; // 传过来的时间值 1-24
            if (M('disable')->where("masterid='$userid'")->setField("notime", "notime" . $time . "-")) {
                $info = type(0, "设置成功");
            } else {
                $info = type(2, "设置失败");
            }
        } catch (Exception $e) {
            $info = type(1, "设置失败");
        }
        echo $info;
    }
    public function alipayCallBack()
    {
        try {
            $listid = $_REQUEST['out_trade_no'];
            $state = $_REQUEST['trade_status'];
            M("list")->where("listid='$listid'")->save($_REQUEST);
            if ($state == 'TRADE_SUCCESS' || $state == 'TRADE_FINISHED') {
                M("list")->where("listid='$listid'")->setField("state", 1);
		$objectid=M("list")->field("masterid,objectid,type,classid,mode,total_fee")->where("listid='$listid'")->find();
		$masterid=$objectid['masterid'];
		switch($objectid['type']){
		        case 'jx': $field="myschoolid";$class="classid";break;
			case 'jl': $field="mycoachid";$class="classjlid";break;
		        case 'zdy': $field="myguiderid";$class="classzdyid";break;
		}
		if($objectid['total_fee']==$objectid['prices']){
		    $mode=0;
		}else{
		    $mode=1;
		}
		M('student')->where("userid='$masterid'")->setField('paytype',$mode);
		$post[$field]=$objectid['objectid'];
		$post[$class]=$objectid['classid'];
		M("drisch")->where("userid='$masterid'")->save($post);
		if($objectid['mode']==4){
			$res['statuss']=1;
			$res['paidnum']=$objectid['total_fee'];
			M("reservation")->where("listid='$listid'")->save($res);
		}
		echo 'success';
            }
        } catch (Exception $e) {}
    }
	function aaa(){
		M("reservation")->where("listid='2016120914390254521005'")->setField("paidnum",0.01);
	}
	//admin/driving/returnOrderState
    // 返回支付状态
    public function returnOrderState()
    {
        try {
            $listid = $_POST['listid'];
            $data = M("list")->field("state")
                ->where("listid='$listid'")
                ->find()['state'];
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, '返回失败');
        }
        echo $info;
    }

    public function newOrderState($listid='2016112812470153535698')
    {
        try {
            $data = M("list")->field("state,gmt_payment,total_fee")
                ->where("listid='$listid'")
                ->find();
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, '返回失败');
        }
        echo $info;
    }
	//-----------------------------------------------------------8.16-----------------------------------------------------------
	public function returnAlltrain(){
		 try {
				//所传参数   userid，session_id,city
				$city=I("post.city");
				//找城市的id
				$cityid=M("citys")->field("id")->where("cityname like '%$city%'")->find()['id'];
				$trains=M("train")->field("id,trname")->where("cityid=$cityid")->select();
				$info = result(0, $trains);
			} catch (Exception $e) {
            $info = type(1, '返回失败');
		 }
		 echo $info;
	}
	//---------------------------------------8.18------------------------------------
  public function coachListcenter($start=0,$userid='',$state=5,$type='jl'){//驾校在这里只能看到驾校的订单
	    try {
	        $where='';
	        foreach($_POST as $k=>$v){
				if(!empty($v)){
					if($k=='starttime'){
						$where.="l.listtime >= '$v' and ";
					}elseif($k=='endtime'){
						$where.="l.listtime < '$v' and ";
					}
				}
	        } //echo $where;
	        switch($state){
	            case 0:
	                $where.="l.state=0  and ";break;
                case 1:
                    $where.="l.state>=1 and l.state<=3  and ";break;
                case 4:
                    $where.="l.state=4 and ";break;
	        }
	        $where.="l.Cl_type='y'";
	        $field="l.listid,l.mode,l.listname,l.phone,l.masterid,l.masname,l.address,l.stucount,l.applymode,l.preferentialprice,l.remark,l.state,l.prices,l.objectid,l.total_fee,l.trname,l.listtime,l.description,u.nickname,u.img,l.coach,l.coachname";
	        if($type=='jx' || $type=='sjl'){
	            $wheres="xueche1_user u on u.userid=l.masterid and l.objectid='$userid'";
	        }else{
	            $wheres="xueche1_user u on u.userid=l.masterid and l.coach='$userid'";
	        }//echo $where;
	        $list=M("list l")->field($field)->join($wheres)->where($where)->order("l.id desc")->limit("$start,10")->select();
	        $info = result(0, $list);
	    } catch (Exception $e) {
	        $info = type(1, '返回失败');
	    }
	    echo $info;
	}
	//驾校给教练分配订单
	public function allocateList($listid,$coach){
	    try {
	        $coachname=M("coach")->field("nickname")->where("userid='$coach'")->find()['nickname'];
	        $post['coach']=$coach;
	        $post['coachname']=$coachname;
	        if(M('list')->where("listid='$listid'")->save($post)){
	            $userid=M('list')->field('masterid')->where("listid='$listid'")->find()['masterid'];
	            $stu['masterid']=$coach;
	            $stu['coachid']=1;
	            M('student')->where("userid='$userid'")->save($stu);
		     send($coach,"您有1条新订单，点击查看",array('code'=>1003),'jl');
		    $info = type(0, '分配成功');
	        }else{
	            $info = type(1, '分配失败');
	        }
	    } catch (Exception $e) {
	        $info = type(2, '分配失败');
	    }
	    echo $info;
	}
 //分配订单时，选择教练，如果是驾校的话，通过选择不同的基地来返回教练，小老板直接返回教练
    public function reusefulCoach($userid){
        try {
            if(isset($_POST['trainid'])){
                $trainid=I("post.trainid");
                $data=M("coachtrain t")->field("c.nickname,c.userid,c.jltype")->join("xueche1_coach c on c.userid=t.coachid and t.schoolid='$userid' and c.jltype=0 and t.trainid='$trainid'")->select();
            }else{
                $data=M("coachtrain t")->field("c.nickname,c.userid,c.jltype")->join("xueche1_coach c on c.userid=t.coachid and c.boss='$userid' and c.jltype=3")->select();
            }
            $info=result(0, $data);
        } catch (Exception $e) {
            $info=type(1, "返回失败");
        }
        echo $info;
    }
	//--------------------------------9.22
    //教练端计时预约
    public function reservationInfo($userid,$date,$index,$jtype,$master){
	
        try {
           $fields = "r.masterid,r.num,r.statuss,c.nickname,r.jtype,r.phone,r.subjects";
           $reser = M('reservation r')->field($fields)
             ->join("xueche1_user c on c.userid=r.masterid and r.objectid='$userid' and r.stamp='$date' ")
           ->select();
             if($index==1){
         	if($master!=''){

	        	$count=M('price')->where("masterid='$userid'")->find();
			if(empty($count)){
			     $userid=M('coach')->field($master)->where("userid='$userid'")->find()[$master];
			}
		}
                 $coach['price']=M("price")->field("id,weekdays,timebucket,price,jtype,subjects")->where("masterid='$userid' and jtype='$jtype'")->select();
             }
            $coach['reser'] = $reser;
            $coach['date']=$date;
            $info=result(0, $coach);
        } catch (Exception $e) {
          $info=type(1,"返回失败");
        }
       echo $info;
    }
    //---------------------------------9.23
    //教练端添加修改删除价格详情
    //添加价格详情
    public function addPriceInfo($masterid=''){// 添加价格详情  Driving/addPriceInfo  传值：session_id，masterid，weekdays timeBucket  price  jtype subjects
        try {
            if(M('price')->add($_POST)){
                $_POST['id']=M("price")->field("id")->where("masterid='$masterid'")->order("id desc")->find()['id'];
                $info=result(0, $_POST);
            }else{
                $info=type(1,"添加失败");
            }
        } catch (Exception $e) {
            $info=type(2,"添加失败");
        }
        echo $info;
    }
    //修改价格详情
    public function updatePriceInfo($id,$key,$value){// 修改价格详情  Driving/updatePriceInfo  传值：session_id，id，key value
        try {
            if(M('price')->where("id=$id")->setField($key,$value)){
                $info=type(0,"修改成功");
            }else{
                $info=type(1,"修改失败");
            }
        } catch (Exception $e) {
            $info=type(2,"修改失败");
        }
        echo $info;
    }
    //修改价格详情
    public function delPriceInfo($id){// 删除价格详情  Driving/delPriceInfo  传值：session_id，id
        try {
            if(M('price')->delete($id)){
                $info=type(0,"删除成功");
            }else{
                $info=type(1,"删除失败");
            }
        } catch (Exception $e) {
            $info=type(2,"删除失败");
        }
        echo $info;
    }
    //查看价格详情
    public function queryPriceInfo($masterid,$jtype){// 查看价格详情  Driving/queryPriceInfo  传值：session_id，masterid,jtype
        try {
            $data=trainprice($masterid,$jtype);
            $info=result(0, $data);
        } catch (Exception $e) {
            $info=type(1,"返回失败");
        }
        echo $info;
    }
	/**********************************************************************************************************/  
    public function mydrisch1($subjects=2,$jtype='C1',$userid='8CF687BE-A1AF-5F50-5C3F-C051BB0218A7',$classid=0,$myschoolid='5196C331-1DA3-E56C-DAC1-5EF8655FF96B')
    {
        try {
            // 获得驾校的名字
            $data['school'] = M('school')->field("img,nickname,timeflag")->where("userid='$myschoolid'")->find();
            if (M('attention')->where("userid='$userid' and objectid='$myschoolid'")->count() == 1) {
                $data['school']['flag'] = 1;
            } else {
                $data['school']['flag'] = 0;
            }
            // 获取课程信息
            if ($classid ==0) {
                $data['class']=null;
            } else {
                $data['class'] = M('trainclass')->field("tcid,masterid,jtype,name,carname,mode,officialprice,whole517price,prepay517price,prepay517deposit,classtime")
                ->where("masterid='$myschoolid' and tcid=$classid ")
                ->find();
            }
            //找到驾校所属的基地
            $train=M('schooltrains')->field("trainid")->where("schoolid='$myschoolid'")->find()['trainid'];
            if($train){
	    /*	$train=explode(',',rtrim($train,','));
            	foreach ($train as $k=>$v){
                	$data['train'][$k]=M('train')->field("id,trname,address")->where("id=$v")->find();
                	$data['train'][$k]['coachcount']=M('coachtrains')->where("schoolid='$myschoolid' and locate($v,trainid)")->count();
			$data['train'][$k]['coach']=M('coach')->field("img,nickname,userid,birthday,score,teachedage,timeflag,passedcount,masterid,allcount,jtype,praisecount,evalutioncount,teachingcount,trainid,subjects")->where("masterid='$myschoolid' and trainid=$v and jltype=0")->limit(5)->select();
           	 }*/
		 $train=rtrim($train,',');
	    	    $where = array();$where['id'] = array('in',"$train");
	    	    $data['train']=M('train')->field("id,trname,address")->where($where)->select();
            	foreach ($data['train'] as $k=>$v){
            	     $id=$v['id'];
                	 $data['train'][$k]['coachcount']=M('coachtrains')->where("schoolid='$myschoolid' and locate($id,trainid)")->count();
		    	 $data['train'][$k]['coach']=M('coach')->field("img,nickname,userid,birthday,score,teachedage,timeflag,passedcount,masterid,allcount,jtype,praisecount,evalutioncount,teachingcount,trainid,subjects")->where("masterid='$myschoolid' and trainid=$id and jltype=0")->limit(5)->select();
           	    }
	    }else{
	    	$data['train']=[];
	    }
//            $data['coach']=M('coach')->field("img,nickname,userid,birthday,score,teachedage,passedcount,masterid,allcount,jtype,praisecount,evalutioncount,teachingcount,trainid,subjects")->where("masterid='$myschoolid'")->select();
            $info = result(0,$data);
        } catch (Exception $e) {
            $info = type(1,"返回出错");
        }
        echo $info;
    }
    
    /**
     * 我的驾校里面的加载更多训练场
     */
    public function getMoreCoach1($start=0,$schoolid='D18CF959-EDB7-B2C2-C8D0-85CFB1415721',$jtype='C1',$subjects=0,$trainid=1)//$subjects不用传递
    {
        try {
            $field = "c.img,c.nickname,c.timeflag,c.userid,c.score,c.teachedage,c.passedcount,c.masterid,c.allcount,c.praisecount,c.evalutioncount,c.teachingcount,c.trainid,t.subjects";
            $coach=M("coach c")->field($field)->join("xueche1_coachtrains t on t.trainid=$trainid  and t.coachid=c.userid and t.schoolid='$schoolid' and t.jtype='$jtype'")->limit("$start,10")->select();
            foreach ($coach as $k=>$v){
                $trainid=$v['trainid'];
                $coach[$k]['trainid']=rtrim($trainid,',');
            }
            $info = result(0, $coach);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    // 根据用户传过来的基地，驾校来提供教练
    public function getTrainCoach1($trainid,$schoolid)
    {
        try { 
            $field = "c.img,c.nickname,c.timeflag,c.birthday,c.userid,c.score,c.teachedage,c.passedcount,c.jtype,c.masterid,c.allcount,c.praisecount,c.evalutioncount,c.teachingcount,c.trainid,t.subjects";
            $coach=M("coach c")->field($field)->join("xueche1_coachtrains t on t.trainid=$trainid  and t.coachid=c.userid and t.schoolid='$schoolid'")->limit(5)->select();
            //foreach ($coach as $k=>$v){
              //  $trainid=$v['trainid'];
               // $coach[$k]['trainid']=rtrim($trainid,',');
            //}
            $info = result(0, $coach);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
	 // 根据用户传过来的基地，驾校来提供教练
    public function getTrainCoach2($trainid,$schoolid)
    {
        try { 
            $field = "img,nickname,timeflag,birthday,userid,score,teachedage,passedcount,jtype,masterid,allcount,praisecount,evalutioncount,teachingcount";
            $trainid=$trainid.',';
            $train=M('coachtrains')->field('trainid,subjects,coachid as userid')->where("trainid='$trainid' and schoolid='$schoolid'")->limit(5)->select();
            foreach($train as $k=>$v){
                $userid.=$v['userid'].',';
            }
            $userid=rtrim($userid,',');
            $where = array();$where['userid'] = array('in',$userid);
            $coach=M('coach')->field($field)->where($where)->select();
            foreach($coach as $k=>$v){
                $coach[$k]['subjects']=$train[$k]['subjects'];
            }
            $info = result(0, $coach);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    public function getCoachinfo1($userid='1ACCA599-8206-F1DA-90D3-BCFC6243E415',$mycoachid='F3443A9E-B21B-493D-0407-3AA87D760D6B',$classid=0,$date='2016-12-06')
    {
        try {
            // 获得教练的基本信息
            $field = "nickname,img,userid,teachedage,masterid,driverage,boss,allcount,passedcount,teachablecount,teachingcount,pricetwo,pricethree,evalutioncount,praisecount,timeflag,piccount,jtype,jltype";
            $coach['coach'] = M('coach')->field($field)
            ->where("userid='$mycoachid'")
            ->find();
            $jtype=$coach['coach']['jtype'];
            $schoolid = $coach['coach']['masterid'];
            $boss = $coach['coach']['boss'];
            // 返回所属驾校的名字
            $coach['coach']['mastername']= M("school")->field("nickname")
            ->where("userid='$schoolid'")
            ->find()['nickname'];
            $coach['coach']['counttime'] = 90;
            if ($classid ==0) {
                $coach['class'] = null;
            } else {
                $coach['class'] = M("trainclass")->field("tcid,name,carname,include,mode,picktype,traintype,officialprice,whole517price,prepay517price,objectthirdcount,prepay517deposit,classtime")->where("tcid=$classid")
                ->find();
            }
            if (M('attention')->where("userid='$userid' and objectid='$mycoachid'")->count()==1) {
                $coach['flag'] = 1;
            } else {
                $coach['flag'] = 0;
            }
            $trainid=M('coachtrains')->field("trainid")->where("coachid='$mycoachid'")->find()['trainid'];
            if(empty($trainid)){
                $coach['train']=null;
            }else{
                $trainid=rtrim($trainid,',');
                $coach['train']=M('train')->field('id,trname')->where("id=$trainid")->find()['trname'];
            }
            if ($coach['coach']['timeflag'] == 0) {
                echo result(0, $coach);
                return;
            }
            // 教练的预约情况
            $fields = "r.masterid,r.num,c.nickname";
            // 其他日期
            $reser = M('reservation r')->field($fields)
            ->join("xueche1_user c on c.userid=r.masterid and r.objectid='$mycoachid' and r.stamp='$date' ")
            ->select();
            if($coach['coach']['jltype']==0){
               $mycoachid=$schoolid;
            }elseif($coach['coach']['jltype']==3){
               $mycoachid=$boss;
            }
            $coach['price']=M("price")->field("id,weekdays,timebucket,price,jtype,subjects")->where("masterid='$mycoachid' and jtype='$jtype'")->select();
            $coach['reser'] = $reser;
            $coach['date'] =$date;
            $info = result(0, $coach);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    // 从我的驾校点击教练

    public function reserCoach1($jtype='C1',$userid='0BC9E90D-7014-323C-F4C1-72E8C7F568F9',$objectid='4F35E74E-38DE-EC47-A0C9-9E18303ACDBB',$date='2016-12-13')
    {
        try {
            // 获得教练的基本信息
            $field = "nickname,img,userid,teachedage,masterid,driverage,allcount,passedcount,teachablecount,teachingcount,pricetwo,pricethree,evalutioncount,praisecount,timeflag,piccount,jtype";
            $coach['coach'] = M('coach')->field($field)
            ->where("userid='$objectid'")
            ->find();
            $schoolid = $coach['coach']['masterid'];
            // 返回所属驾校的名字
            $coach['coach']['mastername'] = M("school")->field("nickname")
            ->where("userid='$schoolid'")
            ->find()['nickname'];
            $coach['coach']['counttime'] = 90;
            if (M('attention')->where("userid='$userid' and objectid='$objectid'")->count() == 1) {
                $coach['flag'] = 1;
            } else {
                $coach['flag'] = 0;
            }
            if ($coach['coach']['timeflag'] == 0) {
                echo result(0, $coach);
                return;
            } 
            // 教练的预约情况
            $fields = "r.masterid,r.num,c.nickname";
            $reser = M('reservation r')->field($fields)
            ->join("xueche1_user c on c.userid=r.masterid  and r.stamp='$date' and r.objectid='$objectid'")
            ->select();
            $counts=M("price")->where("masterid='$schoolid' and jtype='$jtype'")->select();
	    if($counts){
		$coach['price']=$counts;	
	    }else{
	    	$coach['price']=[];
	    }
            // 一天之内不能被预约的时间
            $coach['reser'] = $reser;
            $coach['date'] = date("Y-m-d");
            $train=M('coachtrains')->field("trainid")->where("coachid='$objectid'")->find()['trainid'];
	    if($train){	
            	 $train=rtrim($train,',');
           	 $coach['train'] = M("train")->field("id,trname")->where("id=$train")->find();
	    }else{
	    
           	 $coach['train'] =[];
		}
            $info = result(0, $coach);
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }
        echo $info;
    }
    //分配订单时，选择教练，如果是驾校的话，通过选择不同的基地来返回教练，小老板直接返回教练
    public function reusefulCoach1($userid='5196C331-1DA3-E56C-DAC1-5EF8655FF96B'){
        try {
            if(isset($_POST['trainid'])){
                $trainid=$_POST['trainid'];
                $data=M("coachtrains t")->field("c.nickname,c.userid")->join("xueche1_coach c on c.userid=t.coachid and t.schoolid='$userid' and c.jltype=0 and locate($trainid,t.trainid)")->select();
	    }else{
                $data=M("coachtrains t")->field("c.nickname,c.userid")->join("xueche1_coach c on c.userid=t.coachid and c.boss='$userid' and c.jltype=3")->select();
            }
            $info=result(0, $data);
        } catch (Exception $e){
            $info=type(1, "返回失败");
        }
        echo $info;
    }
}
