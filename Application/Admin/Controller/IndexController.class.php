<?php
namespace Admin\Controller;
use Think\Controller;
use Think\AlipayNotify;
use Think\Notify;
class IndexController extends CommonController
{
	function a(){
	send();
	}
    public function check()
    {
        if (empty($_POST)) {
            echo urldecode(json_encode(urlencode("未收到任何参数")));
            return;
        }
        $account = I('post.account');
        $pass = I('post.pass');
        $type = I('post.type');
        pd($account, $pass, $type);
    }
      public function adduser($account='13661977296',$nickname='000',$auth='808573')
    {
         $t = time() - (int) $_COOKIE[$account.'b'];
	$po=M('auth')->field("auth,time")->where("phone='$account'")->cache(true,300)->find();
	$t=time()-$po['time'];
	if ($t < 600) {
		$tureauth=$po['auth'];
            if ($tureauth== $auth) {
                $type=$_POST['type'];
                    $table=shenfen($type);
                    $m = M($table);
                    if (issetAccount($account)>0) {
                        $info = type(4, '该帐号已存在', - 1);
                        echo $info;
                        return;
                    }
                    $_POST['truename'] = $_POST['nickname'];
                    $_POST['ntime']=date('Y-m-d H:i:s');
                    $_POST['birthday']=date('Y-m-d');
		    $_POST['userid'] =guid();
					$_POST['phone']=$_POST['account'];
                    if ($m->add($_POST)) {
                        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
                        $u = M('conf');
                        $_POST['downloadURL'] = $u->field("downloadURL")->cache(true,300)->find()['downloadURL'];
                        $info = type(0, "注册成功",$_POST, phone($agent),$type);
                        unsession($account);
			unset($_SESSION[$account.'a']);
			unset($_SESSION[$account.'b']);
                        echo $info;
                        return;
                    } else {
                        unsession($account);
			unset($_SESSION[$account.'a']);
			unset($_SESSION[$account.'b']);
                        $info = type(2, '注册失败', - 1);
                        echo $info;
                        return;
                    }
            } else {
                echo type(3,'验证码错误', - 1);
                return;
            }
        } else {
            unsession($account);
            echo $info = type(1, '验证码超时,请重新获取', - 1);
            return;
        }
    }
    public function passupdate($userid,$pass,$type)
    {
        $table=shenfen($type);
        $mm = M($table)->field("pass")
            ->where("userid='$userid'")
            ->cache(true,300)->find();
        if ($mm['pass'] == $pass) {
           if (M($table)->where("userid='$userid'")->setField("pass",$_POST['newpass'])) {
                $info = type(0, "密码修改成功", - 1);
            } else {
                $info = type(1, "密码修改失败，请检查", - 1);
            }
        } else {
            $info = type(2, "原密码错误", - 1);
        }
        echo $info;
    }
    public function userinfo($type)
    {
        try {
            $table=shenfen($type);
            $userid = $_POST['userid'];
            $user = M($table)->field("account,nickname,truename,sex,birthday,img,signature,grade,jtype,address")
                ->where("userid='$userid'")
                ->cache(true,300)->find();
            $info = result(0, $user);
        } catch (Exception $e) {
            $info = type(1, "返回信息失败，请检查");
        }
        echo $info;
    }
	public function coachUinfo($type='jl')
    {
        try {
            $table=shenfen($type);
            $userid = $_POST['userid'];//='A25BF70C-D5FC-2956-5760-06611F40EBC7';
			$field="account,nickname,sex,birthday,img,signature,grade,jtype,address";
			if($type=='jl'){
				  $field.=",masterid,teachedage,driverage,jltype";
				  $user=M($table)->field($field) ->where("userid='$userid'")
                ->cache(true,300)->find();
				  $user['train']=M("coachtrain c")->field("t.id,t.trname")->join("xueche1_train t on t.id=c.trainid and c.coachid='$userid'")->cache(true,300)->find();
				  if($user['jltype']==3){
					    $coachlid=$user['masterid'];
					    $user['master']=M("school")->field("userid,nickname")->where("userid='$coachlid'")->cache(true,300)->find();
						//$user['train']=M("coachtrain c")->field("t.trname")->join("xueche1_train t on t.id=c.trainid and c.coachid='$userid'")->cache(true,300)->find()['trname'];
				  }else{
					  	$schoolid=$user['masterid'];
						$user['master']=M("school")->field("userid,nickname")->where("userid='$schoolid'")->cache(true,300)->find();
				  }
			}else{
				 if($type=='jx'){
                   $field.=',fullname';
                }else{
                    $field.=",teachedage,driverage";
                }
                $user= M($table)->field($field)
                ->where("userid='$userid'")
                ->cache(true,300)->find();
			}
            $info = result(0, $user);
        } catch (Exception $e) {
            $info = type(1, "返回信息失败，请检查");
        }
        echo $info;
    }
    public function myconcern($userid,$start)
    {
        try {
            $becon = M('attention')->field("objectid,type")
                ->where("userid='$userid'")->limit("$start,10")
                ->cache(true,300)->select();
            foreach ($becon as $v) {
				$table=shenfen($v['type']);
				$m = M($table);
                $objectid = $v['objectid'];
                $cons[]= $m->field('userid,account,sex,nickname,signature,type,img')->where("userid='$objectid'")->cache(true,300)->find();
            }
            $info =result(0, $cons);
        } catch (Exception $e) {
            $info = type(1, "返回失败", - 1);
        }
        echo $info;
    }
    public function removemycon($userid,$objectid){
        if(M('attention')->where("userid='$userid' and objectid='$objectid'")->delete()){
            $info = type(0, "取消关注成功");
        }else{
            $info = type(1, "取消关注失败");
        }
        echo $info;
    } 
    public function addmycon($userid,$objectid){
            $cc=M('attention')->where("userid='$userid' and objectid='$objectid'")->count();
           if($cc<=100){
               if($cc==0){
                   $_POST['atime']=date("Y-m-d H:i:s");
                   if(M('attention')->add($_POST)){
                       $info = type(0, "关注成功", - 1);
                   }else{
                       $info = type(1, "关注失败", - 1);
                   }
               }else{
                   $info = type(3, "您已关注该用户", - 1);
               }
           }else{
               $info = type(2, "您已经达到关注上限", - 1);
           }
        echo $info;
    }
   public function updateuinfo()
    {
		$userid = $_POST['userid'];
		unset($_POST['userid']);
        unset($_POST['session_id']);
        $type=$_POST['type'];
        $table=shenfen($type);
        $m = M($table);
        $user =$m->field("account,nickname,sex,birthday,img,signature,grade,jtype,address")
            ->where("userid='$userid'")
            ->cache(true,300)->find();
		 if (count($_FILES) == 1) {
				$path=$_POST['path'];
				$_POST['img'] = $userid.'.'.$path;
				dirup($userid,C('conf.big'),$path);
				$m->where("userid='$userid'")->setField("img",$_POST['img']);
				$info = update(0,"修改成功",$user);	
				echo $info;
				return;
		   }
        unset($_POST['type']);
        if(isset($_POST['phone'])){
            $_POST['account']=$_POST['phone'];
        }
        if ($m->where("userid='$userid'")->save($_POST)) {
            $field="account,nickname,sex,birthday,img,signature,grade,jtype,address,img";
            if($type=='jl' || $type=='zdy'){
                $field.=',teachedage,driverage';
            }
            if($type=='jl'){
                $field.=',masterid,trainid';
            }else if($type=='jx'){
                $field.=',fullname';
            }
            $user = M($table)->field($field)
                ->where("userid='$userid'")
                ->cache(true,300)->find();
            if(isset($_POST['trainid'])){
                M("coachtrain")->where("coachid='$userid'")->setField("trainid",$_POST['trainid']);
                $user['trname']=M("coachtrain t")->field("r.trname")->join("xueche1_train r on t.trainid=r.id and t.coachid='$userid'")->cache(true,300)->find()['trname'];
            }
            if(isset($_POST['masterid'])){
                $schoolid=$_POST['masterid'];
				 M("coachtrain")->where("coachid='$userid'")->setField("schoolid",$schoolid);
                $user['schoolname']=M("school")->field("nickname")->where("userid='$schoolid'")->cache(true,300)->find()['nickname'];
            }
            $info = update(0, "修改成功",$user);
        } else {
            $info = type(1, "修改失败请检查", - 1);
        }
        echo $info;
    }
    public function version($type)
    {
        try {
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            $version = phone($agent);
            echo goback(0, $version, $type);
        } catch (Exception $e) {
            die(type(1, "获取版本信息失败", - 1));
        }
    }
    public function anversion()
    {
        try {
            $mm = M('version')->field("maxversion")
                ->where("id=1 or id=3")
                ->cache(true,300)->select();
            $v = M('conf')->field("downloadurl")->cache(true,300)->select();
            $data['jl'] = (int) $mm[0]['maxversion'];
            $data['xy'] = (int) $mm[1]['maxversion'];
            $data['downloadURL']=$v[0]['downloadurl'];
            $info = result(0, $data);
        } catch (Exception $e) {
            $info = type(1, "返回错误", - 1);
        }
        echo $info;
    }
    public function qr($userid)
    {
        $userid .= mt_rand(- 9, 9) . mt_rand(range('a', 'z'));
        if (dirup($userid,C('conf.rq'), false)) {
            $info = type(1, "上传成功", - 1);
        } else {
            $info = type(1, "上传失败", - 1);
        }
    }
    public function logout($userid)
    {
        if (!empty($userid)) {
            $code = 0;
            $message = "您已安全退出";
        } else {
            $code = 1;
            $message = "退出失败，请检查";
        }
        $arr = array(
            'code' => $code,
            'message' => $message
        );
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
    public function sends($phone='13661977296')
	{$CorpID = C('conf.uid');
        $Pwd = C('conf.pwd');
        $Mobile = $phone;
        $cod = mt_rand(100000, 999999);
        $Content = mb_convert_encoding("【我要去学车】您的验证码为：{$cod},10分钟内有效", "GBK", "auto");
        $Cell = '';
        $SendTime = '';
        $url = C('conf.url');
        $post_data = array(
            "CorpID" => $CorpID,
            "PWD" => $Pwd,
            "Mobile" => $Mobile,
            "Content" => $Content,
            "Cell" => $Cell,
            "SendTime" => $SendTime
        );
	
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $code = curl_exec($ch);
        curl_close($ch);
        switch ($code) {
            case 0:
                $mess = "发送成功";
                break;
            case - 1:
                $mess = "账号未注册";
                break;
            case - 2:
                $mess = "其他错误";
                break;
            case - 3:
                $mess = "密码错误";
                break;
            case - 4:
                $mess = "手机格式不对";
                break;
            case - 5:
                $mess = "余额不足";
                break;
            case - 6:
                $mess = "定时发送时间不是有效的时间格式";
                break;
            case - 7:
                $mess = "禁止10小时以内向同一手机号发送相同短信";
                break;
            case - 100:
                $mess = "限制此IP访问";
                break;
            case - 101:
                $mess = "调用接口速度太快";
                break;
        }
	$post['auth']=$cod;	
	$post['time']=time();
	if(M('auth')->where("phone='$phone'")->cache(true,300)->find()){
		M('auth')->where("phone='$phone'")->save($post);
	}else{
		$post['phone']=$phone;
		M('auth')->add($post);
	}

        $info = array(
            "code" => $code,
            "message" => $mess,
			"auth"=>$cod,
			"time"=>time()
        );
        echo json_encode($info, JSON_UNESCAPED_UNICODE);
    }
    public function getinfo($userid,$deviceToken){
        try {
        } catch (Exception $e) {
        }
    }
    public function verification($phone,$auth)
    {
		 if(isset($_SESSION[$phone.'b'])){
            $t = time() - (int) $_SESSION[$phone.'b'];
			$tureauth=$_SESSION[$phone.'a'];
			 unset($_SESSION[$phone.'a']);
			unset($_SESSION[$phone.'b']);
        }else{
            $t = time() - (int)$_POST['time'];
			$tureauth= $_POST['trueauth'];
        }
        if ($t < 600) {
            if ($tureauth== $auth) {
                echo type(0, '验证码正确', - 1);
                return;
            } else {
                echo type(2, '验证码错误', - 1);
                return;
            }
        } else {
            echo type(1, '验证码超时,请重新获取', - 1);
            return;
        }
    }
    public function passreset($type,$account,$newpass)
    {
        $table=shenfen($type);
        $m=M($table);
        $date['pass'] = $newpass;
        if ($m->where("account='$account'")->save($date)) {
            $info = type(0, "密码重置成功",-1);
        } else {
            $info = type(1, "密码重置失败，请检查", - 1);
        }
        echo $info;
    }
    public function feedback(){
        unset($_POST['session_id']);
        $_POST['feedtime']=date('Y-m-d H:i:s');
        if(M('feedback')->add($_POST)){
            $info = type(0,"提交成功", - 1);
        }else{
            $info = type(1, "提交失败", - 1);
        }
        echo $info;
    }
     public function getUserName($userid="F5DCBE66_B741_D0EA_91ED_3A137EE99519"){
        try {
            $m=M("user");
            $name=$m->field("nickname")->where("userid='$userid'")->cache(true,300)->find();
			if($name){
			    echo result(0, $name);return;
			}else if(M("school")->field("nickname")->where("userid='$userid'")->cache(true,300)->find()){
				$name=M("school")->field("nickname")->where("userid='$userid'")->cache(true,300)->find();
				echo result(0, $name);return;
			}else if(M("coach")->field("nickname")->where("userid='$userid'")->cache(true,300)->find()){
				$name=M("coach")->field("nickname")->where("userid='$userid'")->cache(true,300)->find();
				echo result(0, $name);return;
			}else{
				$name=M("guider")->field("nickname")->where("userid='$userid'")->cache(true,300)->find();
				echo result(0, $name);return;
			}
        } catch (Exception $e) {
            $info = type(1, "返回出错");
        }

    }
	public function sinaAuth(){
	}
	public function cancelSinaAuth(){
	}
	public function userisset($userid){
		  try {
				 if(M("user")->field("id")->where("userid='$userid'")->find()){
                $table='user';
            }else if(M("school")->field("id")->where("userid='$userid'")->find()){
                $table='school';
            }else if(M("coach")->field("id")->where("userid='$userid'")->find()){
                $table='coach';
            }else{
                 $table='guider';
            }
            $data=M($table)->field("nickname,signature,img,birthday,sex,type")->where("userid='$userid'")->find();
				if($data){
					$info = result(0,$data);
				}else{
					$info = type(1,"不存在");
				}
       			 } catch (Exception $e) {
           			 $info = type(2, "返回出错");
        		}
       			 echo $info;
	}
	 public function result($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $data[$key][$k] = $v;
                }
            }
        }
        return stripslashes(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    public function bannerCount(){
        $count=count(scandir("/var/www/xueche/Upload/Banner/"))-3;
        echo result(0, $count);
    }
	/*****************************************************************************************************/
    public function coachUinfo1($userid='E7D96389-D40B-EA8B-46DF-99FF792C0D27',$type='jl')
    {
        try {
            $field="account,nickname,sex,birthday,img,signature,grade,jtype,address";
            if($type=='jl'){
                $field.=",masterid,teachedage,driverage,jltype";
                $user=M("coach")->field($field) ->where("userid='$userid'")
                ->cache(true,300)->find();
                $train=M('coachtrains')->field("trainid")->where("coachid='$userid'")->cache(true,300)->find()['trainid'];
                if($train){
                    $train=rtrim($train,',');
                    $user['train']=M('train')->field('id,trname')->where("id=$train")->cache(true,300)->find();
                }else{
                    $user['train']=[];
                }
                if($user['jltype']==3){
                    $coachlid=$user['masterid'];
                    $user['master']=M("coach")->field("userid,nickname")->where("userid='$coachlid'")->cache(true,300)->find();
                }else{
                    $schoolid=$user['masterid'];
                    $user['master']=M("school")->field("userid,nickname")->where("userid='$schoolid'")->cache(true,300)->find();
                }
            }else{
                if($type=='jx'){
                    $table="school";
                    $field.=',fullname';
                }else{
                    $table="guider";
                    $field.=",teachedage,driverage";
                }
                $user= M($table)->field($field)
                ->where("userid='$userid'")
                ->cache(true,300)->find();
            }
            $info = result(0, $user);
        } catch (Exception $e) {
            $info = type(1, "返回信息失败，请检查");
        }
        echo $info;
    }
}

