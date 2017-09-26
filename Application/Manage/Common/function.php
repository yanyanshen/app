<?php
function addlog($info){
    $data['account']=session('account');
    $data['ntime']=time();
    $data['nip']=sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
    $data['info']=$info;
    M('adminlog')->add($data);
}
//驾校的最后更新人
function lastupdate($user,$userid){
	   $username=session("username");
    switch($user){
        case 'jx':$table='school';break;
        case 'jl':$table='coach';break;
        case 'zdy':$table='guider';break;
        case 'xy':$table='user';break;
        case 'eval':$table='evaluating';
			M($table)->where("id=$userid")->setField("lastupdate",$username);
            return;
		break;
    }
 
    M($table)->where("userid='$userid'")->setField("lastupdate",$username);
}

//订单的最后更新人
function listlastupdate($listid){
    $username=session("username");
    M("list")->where("listid='$listid'")->setField("lastupdate",$username);
}
function mym($str,$name){
	    $p1 = md5($str.$name);
		$p2 = sha1($str.$name);	
		$pass = substr($p1,0,6);
		$pass.=substr($p2,0,6);
		$pass.=substr($p1,20,6);
		$pass.=substr($p2,20,6);
		$pass.=substr($p1,30,2);
		$pass.=substr($p2,30,6);
		return strtolower($pass);
	}

