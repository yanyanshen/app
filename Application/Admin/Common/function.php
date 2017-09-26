<?php
  use Think\Image;


function shenfen($type){
        switch($type){
            case 'jl':
                $table="coach";
                break;
            case 'zdy':
                $table="guider";
                break;
            case 'jx':
                $table="school";
                break;
            default:
                $table="user";
                break;
        }
        return $table;
    }
    function pd($account, $pass, $type, $agent)
{
	$where="account='$account'";
	$field="pass,nickname,userid,jtype,type,verify,subjects,verify,address,img,signature";
	if(M("user")->where($where)->cache(true,300)->find()){$shenfen='xy';$table='user';
	}else if(M("coach")->where($where)->cache(true,300)->find()){$shenfen='jl';$table='coach';$field.=",masterid,jltype";
	}else if(M("school")->where($where)->cache(true,300)->find()){$shenfen='jx';$table='school';
	}else if(M("guider")->where($where)->cache(true,300)->find()){$shenfen='zdy';$table='guider';
	}else{ $info = type(1, '账号不存在');  echo $info; return;	}
	if($shenfen==$type){
		$m=M($table)->field($field)->where($where)->cache(true,300)->find();
		if($pass==$m['pass']){
			unset($m['pass']);
			$u = M('conf');
//			$url = $u->field("downloadURL")->cache(true,300)->find()['downloadURL'];
            $url = $u->field("downloadURL")->cache(true,300)->find();
			$m['downloadURL']=$url;
			$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
			$info = type(0, "登录成功", $m, phone($agent), $type);
		}else{$info=type(2,'密码错误');}
	}else{$info=type(3,'身份错误');}
	echo $info;
}
    //处理高并发注册产生唯一不重复的字符串
    function guid(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "_"
            $uuid = // "
            substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            ;// "
            return $uuid;
        }
    }

    /**
     * @会话已过时
     */
      function over()
    {
        $arr = array(
            'code' => 10,
            'message' => "会话已超时请重新登录"
        );
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
    /**
     * @函数：获得手机类型
     */
      function phone($agent)
    {
		$iphone = (strpos($agent, 'cfnetwork')) ? true : false;
        $ipad = (strpos($agent, 'cfnetwork')) ? true : false;
        $android = (strpos($agent, 'android')) ? true : false;
        if ($iphone || $ipad) {
            $v = 'ios';
        }
        if ($android) {
            $v = 'an';
        }
        return $v;
    }
    // 为登录提供服务
      function type($code, $message, $data, $v = 'ios', $t)
    {
        // 版本问题
        $vv = ver($v, $t);
        // 处理中文乱码
        foreach ($data as $key => $value) {
            $date[$key] = urlencode($value);
        }
        $message = urlencode($message);
        $arr = array(
            'code' => $code,
            'latest' => $vv,
            'session_id' => session_id(),
            'result' => $date
        );
        $arr1 = array(
            'code' => $code,
            'message' => $message
        );
        $arr2 = array(
            'code' => $code,
            'message' => $message
        );
        $arr3 = array(
            'result' => $date
        );
        if ($code == - 2) {
            return urldecode(json_encode($arr3));
        }
        if (is_array($date)) {
            return stripslashes(urldecode(json_encode($arr)));
        } else 
            if ($date == - 1) {
                return urldecode(json_encode($arr2));
            } else {
                return urldecode(json_encode($arr1));
            }
    }
      function ver($v, $t)
    {
        $vv = M('version')->field("id,maxversion,minversion")->cache(true,300)->select();
        if ($v == 'ios') {
            if ($t == 'jl') {
                $max = $vv[1]['maxversion'];
            } else {
                $max = $vv[3]['maxversion'];
            }
        } else{
            if ($t == 'jl') {
                $max = $vv[0]['maxversion'];
            } else {
                $max = $vv[2]['maxversion'];
            }
       }
        return $max;
    }
    /**
     * @该函数作为公共函数，返回系统的最新版本
     * @当软件刚打开的时候就返回最新版本信息
     */
      function goback($code = 0, $v = 'ios', $t = 'jl')
    {
        // 版本问题
        $vv = M('version')->cache(true,300)->select();
        if ($v == 'ios') {
            if ($t == 'jl') {
                $vs = $vv[1]['minversion'];
                $max = $vv[1]['maxversion'];
            } else {
                $vs = $vv[3]['minversion'];
                $max = $vv[3]['maxversion'];
            }
        } else 
            if ($v == 'an') {
                if ($t == 'jl') {
                    $vs = $vv[0]['minversion'];
                    $max = $vv[0]['maxversion'];
                } else {
                    $vs = $vv[2]['minversion'];
                    $max = $vv[2]['maxversion'];
                }
            } else {//echo $v;
                $max = 120;
                $vs = 120;
            }
        $arr = array(
            'code' => $code,
            'latest' => $vs, // 返回该手机所使用的最新版本
            'max' => $max
        );
        return json_encode($arr);
    }
    // result()为用户登录后提供服务
      function result($code, $data)
    {
        // 处理中文乱码
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $data[$key][$k] = $v;
                }
            }
        }
        $arr = array(
            'code' => $code,
            'result' => $data
        );
        if ($code == 0) {
            return stripslashes(json_encode($arr, JSON_UNESCAPED_UNICODE));
        }
    }
    /*
     * 文件上传结果,只返回code和提示
     */
      function zimg($code, $message)
    {
        $arr = array(
            "code" => $code,
            "message" => $message
        );
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
    // result()为用户修改自己的信息提供服务
      function update($code, $message, $data)
    {
        $message = urlencode($message);
        // 处理中文乱码
        foreach ($data as $key => $value) {
            $date[$key] = urlencode($value);
        }
        $arr = array(
            'code' => $code,
            'message' => $message,
            'result' => $date
        );
        if ($code == 0) {
            return urldecode(json_encode($arr));
        }
    }
	/**
     * @我的教练里面点击详情-》培训价格
     */

      function trainprice($masterid,$jtype){
            $data=M("price")->field("id,weekdays,timebucket,price,jtype,subjects")->where("masterid='$masterid' and jtype='$jtype'")->cache(true,300)->select();
            return $data;
      }
    /**
     * 文件上传
     */
      function dirup($account, $dir, $push)
    {
        $j = 0;
		$arr=['jpg','png'];
        if ($_FILES['ImageField']['size'] < 1024 * 1024 * 2) {
            if (! file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            if (in_array($push,$arr)) {
                move_uploaded_file($_FILES['ImageField']['tmp_name'], $dir . '/' . $account . '.' . $push);
                 ++$j;
            }
            if ($j > 0) {
                   $image=new Image();
                $img =$dir . '/' . $account . '.' . $push;//获取文件上传目录
                $image = new \Think\Image();
                $image->open($img);    //打开上传图片
                $image->thumb(120,120,\Think\Image::IMAGE_THUMB_FIXED)->save('./Upload/small/' . $account . '.' . $push);//生成缩略图
                return "上传成功";
            }
        } else {
            return '所选文件过大，请更改设置或重新选择！';
        }
    }
    /**
     * 生成缩略图
     */
      function img_cl($userid, $dir, $w,$push)
    {
        $dirname = $dir . '/' . $userid . '.'.$push;
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

    /**
     * 删除session
     */
      function unsession($phone)
    {
        unset($_SESSION[$phone . 'a']);
        unset($_SESSION[$phone . 'b']);
    }
    /**
     * 获取列表及评论内容
     */
    /*
     * 订单中心
     */
      function getMyList($mode,$userid,$start){
        $m=M('list');
        /*
         * mode 订单类型 1--驾校  2--教练 3--指导员 4 预约
         * state 订单状态 0 --代支付 1 待确认 2 待评价 3 已完成   4 已取消
         */
        $modetype=$m->field("mode,objectid,listid")->where("masterid='$userid' and mode=$mode and flag=1")->cache(true,300)->select();
        if($mode==1){
            $fields="s.name,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state,l.classid,l.description,t.name as classname";
            $data1=M("school s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode")->join("xueche1_trainclass t on l.classid=t.tcid")->order("l.id desc")->limit("$start,10")->cache(true,300)->select();
            return $data1;
        }else if($mode==2 || $mode==3){
            foreach($modetype as $v){
                $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state";
                $data2=M("user s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode")->order("l.id desc")->limit("$start,10")->cache(true,300)->select();
            }
            return $data2;
        } else if($mode==4){
            foreach($modetype as $k=>$v){
                $lid=$v["listid"];
                $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state";
                $data3=M("user s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode")->order("l.id desc")->limit("$start,10")->cache(true,300)->select();     
                $d[$k]['duration']= M("reservation")->field("duration")->where("listid=$lid")->cache(true,300)->find()['duration'];
                $data4[]=array_merge($d[$k],$data3[$k]);
            }
            return $data4;
        }
    }
    /**
     * 订单分类
     * 
     */
      function getMyListClass($mode,$userid,$start,$class){
        $m=M('list');
        /*
         * mode 订单类型 1--驾校  2--教练 3--指导员 4 预约
         * state 订单状态 0 --代支付 1 待确认 2 待评价 3 已完成   4 已取消
         */
        $modetype=$m->field("mode,objectid,listid")->where("masterid='$userid' and mode=$mode and flag=1")->cache(true,300)->select();
        if($mode==1){
            $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.id,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state,l.classid,l.description,t.name as classname";
            $data1=M("school s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode and state=$class ")->join("xueche1_trainclass t on l.classid=t.tcid")->order("l.id desc")->limit("$start,10")->cache(true,300)->select();
            return $data1;
        }else if($mode==2 || $mode==3){
            foreach($modetype as $v){
                $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state";
                $data2=M("user s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode")->order("l.id desc")->limit("$start,10")->cache(true,300)->select();
            }
            return $data2;
        } else if($mode==4){
            foreach($modetype as $k=>$v){
                $lid=$v["listid"];
                $fields="s.nickname,s.img,l.objectid,l.phone,l.masterid,l.stucount,l.applymode,l.price,l.address,l.remark,l.mode,l.paidnum,l.masname,l.couponmode,l.listid,l.listtime,l.state";
                $data3=M("user s")->field($fields)->join("xueche1_list l on s.userid=l.objectid and l.masterid='$userid' and mode=$mode and state=$class ")->order("l.id desc")->limit("$start,10")->cache(true,300)->select();
                $d[$k]['duration']= M("reservation")->field("duration")->where("listid=$lid")->cache(true,300)->find()['duration'];
                $data4[]=array_merge($d[$k],$data3[$k]);
            }
            return $data4;
        }
    }
    /**
     * 
     */
      function jh($mm,$latitude,$longitude){
        $t='';
        for($i=0;$i<count($mm);$i++){
            for($j=0;$j<count($mm);$j++){
                if(!empty($mm[$j]['location']) && !empty($mm[$j+1]['location'])){
                    if(getDistance($mm[$j]['location']['latitude'],$mm[$j]['location']['longitude'],$latitude,$longitude)>getDistance($mm[$j+1]['latitude'],$mm[$j+1]['longitude'],$latitude,$longitude)){
                        $t=$mm[$j];
                        $mm[$j]=$mm[$j+1];
                        $mm[$j+1]=$t;
                    }
                }
            }
        }
        return $mm;
    }
    /**
     * 根据两个经纬度计算两点距离
     */
    function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }
	/**
	*/
	  function getAround($lat,$lon,$raidus){
	$PI = 3.14159265;
	$latitude = $lat;
	$longitude = $lon;
	$degree = (24901*1609)/360.0;
	$raidusMile = $raidus;
	$dpmLat = 1/$degree;
	$radiusLat = $dpmLat*$raidusMile;
	$minLat = $latitude -$radiusLat;
	$maxLat = $latitude + $radiusLat;
	$mpdLng = $degree*cos($latitude * ($PI/180));
	$dpmLng = 1 / $mpdLng;
	$radiusLng = $dpmLng*$raidusMile;
	$minLng = $longitude -$radiusLng;
	$maxLng = $longitude + $radiusLng;
	$a[]=$minLat;
	$a[]=$maxLat;
	$a[]=$minLng;
	$a[]=$maxLng;
	return $a;
}
    /**
     * 生成一个经纬度
     */
    function curlGetWeb($area,$address)
    {//96980ac7cf166499cbbcc946687fb414
        $Url="http://api.map.baidu.com/geocoder?address=".trim($area).trim($address)."&output=json&key=ASESdpTH1lcn6HbficDjIxGbpHyVAs3P";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出
        $result=curl_exec($ch);
        curl_close($ch);
        $infolist=json_decode($result);
        $array=array();
        if(isset($infolist->result->location) && !empty($infolist->result->location)){
            $array=array(
                'lng'=>$infolist->result->location->lng,
                'lat'=>$infolist->result->location->lat,
            );
            return $array;
        }
        else
        {
            return false;
        }
    }
	 //注册时一次统计多张表看是否存在
      function issetAccount($account){
		$where="account='$account'";
		if(M("user")->where($where)->cache(true,300)->find()){$count=1;
		}else if(M("coach")->where($where)->cache(true,300)->find()){$count=1;
		}else if(M("school")->where($where)->cache(true,300)->find()){$count=1;
		}else if(M("guider")->where($where)->cache(true,300)->find()){$count=1;
		}else{$count=0;}
        return $count;
    }
	   function getSchoolList($start,$cityid,$latitude,$longitude)
    {
        $field = "id,userid,nickname,pickrange,score,allcount,price,img";
        $erro = "驾校";
        $m = M("school");
        if ($m->count()> 0) {
            $mm = $m->field($field)
            ->where("cityid=$cityid and verify=3")
            ->limit("$start,5")
            ->cache(true,300)->select();
	   for($k=0;$k<count($mm);$k++){
                $mas=$mm[$k]['userid'];
                $mm[$k]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$mas'")->cache(true,300)->find()['minprice'];
                $stu=M('sign')->field("userid,stime")->where("masterid='$mas'")->order("id desc")->cache(true,300)->find();
                $stuid=$stu['userid'];
                $mm[$k]['student']=M('user')->field("nickname")->where("userid='$stuid'")->cache(true,300)->find()['nickname'];
                $mm[$k]['signtime']=$stu['stime'];
				$mm[$k]['landmark']=M("schoolland s")->field("l.id,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.schoolid='$mas'")->order("rand()")->limit(20)->cache(true,300)->select();
		   }
            //$mm=jh($mm,$latitude,$longitude);
            $info=result(0, $mm);
        } else {
            $info = type(1, "没有任何驾校" . $erro, - 1);
        }
        echo $info;
    }
    
      function getCoachList($start,$cityid,$latitude,$longitude)
    {
        $field = "id,userid,nickname,img,sex,birthday,masterid,teachedage,score,passedcount,allcount,evalutioncount,praisecount";
        $m = M("coach");
        if ($m->count() > 0) {
               $mm = $m->field($field)->where("cityid=$cityid and verify=3")->limit("$start,5")->cache(true,300)->select();
            foreach($mm as $k=>$v){
                $id=$v['userid'];
                $mas=$mm[$k]['masterid'];
                $mm[$k]['location']=M('address')->field("latitude,longitude")->where("masterid='$id'")->cache(true,300)->find();
                $mm[$k]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$mas'")->cache(true,300)->find()['minprice'];
				$mm[$k]['landmark']=M("coachland s")->field("l.id,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.coachid='$id'")->order("rand()")->limit(20)->cache(true,300)->select();
            }
            $mm=jh($mm,$latitude,$longitude);
            $info=result(0, $mm);
        } else {
            $info = type(1, "没有任何教练", - 1);
        }
        echo $info;
    }
      function getGuiderList($start,$cityid,$latitude,$longitude)
    {
        $field = "id,userid,nickname,img,sex,birthday,teachedage,score,passedcount,allcount,evalutioncount,praisecount";
        $erro = "指导员";
        $m = M("guider");
        if ($m->count() > 0) {
            $mm = $m->field($field)->where("cityid=$cityid and verify=3")->limit("$start,5")->cache(true,300)->select();
            foreach($mm as $k=>$v){
                $mas=$mm[$k]['userid'];
                $mm[$k]['location']=M('address')->field("latitude,longitude")->where("masterid='$mas'")->cache(true,300)->find();
                $mm[$k]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$mas'")->cache(true,300)->find()['minprice'];
				$mm[$k]['landmark']=M("guiderland s")->field("l.id,l.longitude,l.latitude")->join("xueche1_landmark l on l.id=s.landmarkid and s.guiderid='$mas'")->order("rand()")->limit(20)->cache(true,300)->select();
            }
            $mm=jh($mm,$latitude,$longitude);
            $info=result(0, $mm);
        } else {
            $info = type(1, "没有任何指导员", - 1);
        }
        echo $info;
    }
    //语言过滤
      function Filter_word($str)
    {
        $words=file("./bd.txt");
        $string = strtolower($str);
        foreach($words as $k=>$v){
            $word = preg_replace("/\r\n|\r\n/i", '', $v);
            if(false!= strtr($string,$word)){
                return 1;
            }
        }
        return $string;
    }
	function returnfield($type,$userid){
        $field="account,nickname,sex,birthday,img,signature,grade,jtype,address,img";
        switch($type){
            case 'jl':
                $field.=",teachedage,driverage,masterid,jltype";
                $user=M("coach")->field($field)->where("userid='$userid'")->cache(true,300)->find();
                $masterid=$user['masterid'];
                if($user['jltype']==3){
                    $user['masterid']=M("coach")->field('nickname')->where("userid='$masterid'")->cache(true,300)->find()['nickname'];
                }else{
                    $user['masterid']=M("school")->field('nickname')->where("userid='$masterid'")->cache(true,300)->find()['nickname'];
                }
            break;
            case 'jx':
                $field.=",fullname";
                $user=M("school")->field($field)->where("userid='$userid'")->cache(true,300)->find();
            break;
            case 'zdy':
                $field.=",teachedage,driverage";
                $user=M("guider")->field($field)->where("userid='$userid'")->cache(true,300)->find();
            break;
            default:
                $user=M("user")->field($field)->where("userid='$userid'")->cache(true,300)->find();
            break;
        }
        return $user;
    }
    function returnCoach($userid,$tp,$start,$jltype){
        $coachs=M("coach")->field("userid,nickname,img")->where("$tp='$userid' and jltype=$jltype")->limit("$start,10")->cache(true,300)->select();
        //加上小打工的教练的学员人数和订单数
        foreach ($coachs as $k=>$v){
            $uid=$v['userid'];
            $coachs[$k]['stucount']=M("student")->where("masterid='$uid' and subjects !=5")->count();
            $coachs[$k]['listcount']=M("list")->where("objectid='$uid'")->count();
            $coachs[$k]['train']=M("coachtrain c")->field("t.trname")->join("xueche1_train t on c.trainid=t.id and c.coachid='$uid'")->cache(true,300)->find()['trname'];
        }
        return $coachs;
    }
	function returnCoach1($userid,$tp,$start,$jltype){
        $coachs=M("coach")->field("userid,nickname,img")->where("$tp='$userid' and jltype=$jltype")->limit("$start,10")->cache(true,300)->select();
        if($coachs){
            //加上小打工的教练的学员人数和订单数
            foreach ($coachs as $k=>$v){
                $uid=$v['userid'];
                $coachs[$k]['stucount']=M("student")->where("masterid='$uid' and Cl_type='y' and subjects !=5")->count();
                $coachs[$k]['listcount']=M("list")->where("objectid='$uid' and Cl_type='y'")->count();
                $trainid=rtrim(M("coachtrains")->field("trainid")->where("coachid='$uid'")->cache(true,300)->find()['trainid'],',');
            	 if($trainid){
                    $coachs[$k]['train']=M('train')->field('trname')->where("id=$trainid")->cache(true,300)->find()['trname'];
                }else{
                    $coachs[$k]['train']=null;
                }
	    }
        }
        return $coachs;
    }
	//----------------------------------9.7
	//学员端返回教练详情()
    function returnCoachDetail($userid,$objectid,$field){
        $m = M("coach u");
        $mm = $m->field($field)->where("userid='$objectid'")->cache(true,300)->find();  
        $mm['evalutioncount']=M('evaluating')->where("objecthingid='$objectid'")->count();//--评论数
        $mm['consultcount']=M('consult')->where("objectid='$objectid'")->count();//--提问数
        $img = M('img')->where("userid='$objectid'")->count();//--图片数
        $evalmasterid=$objectid;
        $consultid=$objectid;
        $imgid=$objectid;
        $classmasterid=$objectid;
        if($mm['jltype']==0){$classmasterid=$mm['masterid'];}
        else if($mm['jltype']==3){$classmasterid=$mm['boss'];}
        $mm['classcount']= M('trainclass')->where("masterid='$classmasterid'")->count();//--课程数
        if($mm['evalutioncount']==0){$evalmasterid=$mm['masterid'];
		$mm['evalutioncount']=M('evaluating')->where("objecthingid='$evalmasterid'")->count();
        }
        if($mm['consultcount']==0){$consultid=$mm['masterid'];
		  $mm['consultcount']=M('consult')->where("objectid='$consultid'")->count();}
        if($img==0){$imgid=$mm['masterid'];}
        //评论
        $p1 = M("user u")->field("u.nickname,u.img,e.id,e.time,e.masterid,e.objectid,e.content,e.score,e.num")
        ->join("xueche1_evaluating e on e.masterid=u.userid and e.objecthingid='$evalmasterid'")->order('e.id desc')->limit(2)->cache(true,300)->select();
        $p2 =M("evaluating")->field("id,masterid,time,masterid,content,score")->where("masterid='1' and objecthingid='$evalmasterid'")->cache(true,300)->select();
        $mm['message'] = array_slice(array_merge($p1,$p2),0,2);
        //提问
        $pp = $m->field("c.id,c.time,c.masterid,c.objectid,c.content,c.replycount")
        ->join("xueche1_consult c on u.userid=c.objectid and u.userid='$consultid'")
        ->order('id desc')->limit(2)->cache(true,300)->select();
        foreach ($pp as $k => $v){
            $uid = $v['masterid'];
            $pp[$k]['nickname'] =M('user')->field("nickname")->where("userid='$uid'")->cache(true,300)->find()['nickname'];
            $pp[$k]['img'] =M('user')->field("img")->where("userid='$uid'")->cache(true,300)->find()['img'];
        }
        $mm['consult'] = $pp;
        //图片
        $imgname1 = M('img')->field("imgname ")
        ->where("userid='$imgid'")//先写死
        ->order('id desc')
        ->cache(true,300)->select();
        foreach($imgname1 as $k=>$v){
            $mm['imgurl'][$k]= C("conf.ip").$imgname1[$k]['imgname'];
        }
        //课程
        $trainclass=M("trainclass")->field("tcid,name,include,masterid,time,carname,mode,officialprice,whole517price,prepay517price,prepay517deposit,classtime")->where("masterid='$classmasterid'")->cache(true,300)->select();
        $mm['class'] = $trainclass;
        // 查看用户是否关注了该对象
        if(M('attention')->where("userid='$userid' and objectid='$objectid'")->count()==1){
            $mm['conflag']=1; //代表已经关注
        }else{
            $mm['conflag']=0;//代表还未关注
        }
        //dump($mm);
        return result(0, $mm);
    }
	function userLogs($userid,$doing,$action_name){
			$post['userid']=$userid;
			$post['doing']=$doing;
			$post['dotime']=date("Y-m-d H:i:s");
			$post['url']=$action_name;
			$post['ip']=$_SERVER["REMOTE_ADDR"];
			$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
			$post['phone']=phone($agent);
			$m=M("userlog");
			$m->add($post);
	}
/***************************************************************************************************/
	function getSchoolList1($start,$cityid,$latitude,$longitude)
	{
	    $field = "id,userid,nickname,pickrange,score,allcount,price,img";
	    $erro = "驾校";
	    $m = M("school");
	    if ($m->count()> 0) {
	        $mm = $m->field($field)
	        ->where("cityid=$cityid and verify=3")
	        ->limit("$start,5")
	        ->cache(true,300)->select();
	        for($k=0;$k<count($mm);$k++){
	            $mas=$mm[$k]['userid'];
	            $mm[$k]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$mas'")->cache(true,300)->find()['minprice'];
	            $stu=M('sign')->field("userid,stime")->where("masterid='$mas'")->order("id desc")->cache(true,300)->find();
	            $stuid=$stu['userid'];
	            $mm[$k]['student']=M('user')->field("nickname")->where("userid='$stuid'")->cache(true,300)->find()['nickname'];
	            $mm[$k]['signtime']=$stu['stime'];
	            $land[$k]=M("schoollands")->field("landmarkid")->where("schoolid='$mas'")->cache(true,300)->find()['landmarkid'];
		    if(substr_count($land[$k],',')>6){
	                $n = 0;
    	                for($i = 1;$i <= 5;$i++) {
                    	  $n = strpos($land[$k], ',', $n);
                    	  $i != 5 && $n++;
                        }
                        $land[$k]=substr($land[$k],0,$n);
	            }else{
	                $land[$k]=rtrim($land[$k],',');
	            }
	            $where = array();$where['id'] = array('in',"$land[$k]");
	            $mm[$k]['landmark']=M("landmark")->field("id,longitude,latitude")->where($where)->cache(true,300)->select();
	        }
	        $info=result(0, $mm);
	    } else {
	        $info = type(1, "没有任何驾校" . $erro, - 1);
	    }
	    echo $info;
	}
	
	
	
	function getCoachList1($start,$cityid,$latitude,$longitude)
	{
	    $field = "id,userid,nickname,img,sex,birthday,masterid,teachedage,score,passedcount,allcount,evalutioncount,praisecount";
	    $m = M("coach");
	    if ($m->count() > 0) {
	        $mm = $m->field($field)->where("cityid=$cityid and verify=3")->limit("$start,5")->cache(true,300)->select();
	        foreach($mm as $k=>$v){
	            $id=$v['userid'];
	            $mas=$mm[$k]['masterid'];
	            $mm[$k]['location']=M('address')->field("latitude,longitude")->where("masterid='$id'")->cache(true,300)->find();
	            $mm[$k]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$mas'")->cache(true,300)->find()['minprice'];
	            $land[$k]=M("coachlands")->field("landmarkid")->where("coachid='$id'")->cache(true,300)->find()['landmarkid'];
		   if(substr_count($land[$k],',')>6){
	                $n = 0;
    	            for($i = 1;$i <= 5;$i++) {
                    	$n = strpos($land[$k], ',', $n);
                    	$i != 5 && $n++;
                    }
                    $land[$k]=substr($land[$k],0,$n);
	            }else{
	                $land[$k]=rtrim($land[$k],',');
	            }
	            $where = array();$where['id'] = array('in',"$land[$k]");
	            $mm[$k]['landmark']=M("landmark")->field("id,longitude,latitude")->where($where)->cache(true,300)->select();
	        }
	        $mm=jh($mm,$latitude,$longitude);
	        $info=result(0, $mm);
	    } else {
	        $info = type(1, "没有任何教练", - 1);
	    }
	    echo $info;
	}
	
	
	
	function getGuiderList1($start,$cityid,$latitude,$longitude)
	{
	    $field = "id,userid,nickname,img,sex,birthday,teachedage,score,passedcount,allcount,evalutioncount,praisecount";
	    $erro = "指导员";
	    $m = M("guider");
	    if ($m->count() > 0) {
	        $mm = $m->field($field)->where("cityid=$cityid and verify=3")->limit("$start,5")->cache(true,300)->select();
	        foreach($mm as $k=>$v){
	            $mas=$mm[$k]['userid'];
	            $mm[$k]['location']=M('address')->field("latitude,longitude")->where("masterid='$mas'")->cache(true,300)->find();
	            $mm[$k]['minprice']=M('trainclass')->field("min(whole517price)as minprice")->where("masterid='$mas'")->cache(true,300)->find()['minprice'];
	            $land[$k]=M("guiderlands")->field("landmarkid")->where("guiderid='$mas'")->cache(true,300)->find()['landmarkid'];
		    if(substr_count($land[$k],',')>6){
	                $n = 0;
    	            for($i = 1;$i <= 5;$i++) {
                    	$n = strpos($land[$k], ',', $n);
                    	$i != 5 && $n++;
                    }
                    $land[$k]=substr($land[$k],0,$n);
	            }else{
	                $land[$k]=rtrim($land[$k],',');
	            }
	            $where = array();$where['id'] = array('in',"$land[$k]");
	            $mm[$k]['landmark']=M("landmark")->field("id,longitude,latitude")->where($where)->cache(true,300)->select();
	        }
	        $mm=jh($mm,$latitude,$longitude);
	        $info=result(0, $mm);
	    } else {
	        $info = type(1, "没有任何指导员", - 1);
	    }
	    echo $info;
	}
 function returnSquarePoint($lng=121.536,$lat=31.2996,$distance=0.5){
     $dlng=2*asin(sin($distance / (2 * 6371))/cos(deg2rad($lat)));
     $dlng=rad2deg($dlng);
     $dlat=$distance / 6371;
     $dlat=rad2deg($dlat);
     return array(
         'left-top'=>array('lat'=>$lat+$dlat,'lng'=>$lng-$dlng),
         'right-top'=>array('lat'=>$lat+$dlat,'lng'=>$lng+$dlng),
         'left-bottom'=>array('lat'=>$lat-$dlat,'lng'=>$lng-$dlng),
         'right-bottom'=>array('lat'=>$lat-$dlat,'lng'=>$lng+$dlng),
     );
 }
	//学员端返回教练详情()
 function coachDetail($userid,$objectid,$field){
     $m = M("coach u");
     $mm = $m->field($field)->where("userid='$objectid'")->cache(true,300)->find();
     $mm['evalutioncount']=M('evaluating')->where("objecthingid='$objectid'")->count();//--评论数
     $mm['consultcount']=M('consult')->where("objectid='$objectid'")->count();//--提问数
     $img = M('img')->where("userid='$objectid'")->count();//--图片数
     $evalmasterid=$objectid;
     $consultid=$objectid;
     $imgid=$objectid;
     $classmasterid=$objectid;
     if($mm['jltype']==0){$classmasterid=$mm['masterid'];}
     else if($mm['jltype']==3){$classmasterid=$mm['boss'];}
     $mm['classcount']= M('trainclass')->where("masterid='$classmasterid'")->count();//--课程数
     if($mm['evalutioncount']==0){$evalmasterid=$mm['masterid'];
     $mm['evalutioncount']=M('evaluating')->where("objecthingid='$evalmasterid'")->count();
     }
     if($mm['consultcount']==0){$consultid=$mm['masterid'];
     $mm['consultcount']=M('consult')->where("objectid='$consultid'")->count();}
     if($img==0){$imgid=$mm['masterid'];}
     //评论
     $mm['message'] = M("user u")->field("u.nickname,u.img,e.id,e.time,e.masterid,e.objectid,e.content,e.score,e.num,e.replycount,e.evaluate")
     ->join("xueche1_evaluating e on e.masterid=u.userid and e.objecthingid='$evalmasterid'")->order('e.id desc')->cache(true,300)->find();
     if(!$mm['message']){
         $mm['message'] = M("evaluating")->field("id,time,masterid,objectid,content,score,num,evaluate")
         ->where("objecthingid='$evalmasterid'")->order('id desc')->cache(true,300)->find();
     }
     //提问
     $mm['consult'] = M("user u")->field("c.id,c.time,c.masterid,c.objectid,c.content,c.replycount,u.nickname,u.img")
     ->join("xueche1_consult c on u.userid=c.masterid and c.objectid='$consultid'")
     ->order('id desc')->cache(true,300)->find();
     //图片
     $imgname1 = M('img')->field("imgname ")
     ->where("userid='$imgid'")
     ->order('id desc')
     ->cache(true,300)->select();
     foreach($imgname1 as $k=>$v){
         $mm['imgurl'][$k]= C("conf.ip").$imgname1[$k]['imgname'];
     }
     //课程
     $trainclass=M("trainclass")->field("tcid,name,include,masterid,time,carname,mode,officialprice,whole517price,prepay517price,prepay517deposit,classtime")->where("masterid='$classmasterid'")->cache(true,300)->select();
     $mm['class'] = $trainclass;
     // 查看用户是否关注了该对象
     if(M('attention')->where("userid='$userid' and objectid='$objectid'")->count()==1){
         $mm['conflag']=1; //代表已经关注
     }else{
         $mm['conflag']=0;//代表还未关注
     }
     return result(0, $mm);
 }
/*-------------------------------------------------------*/

