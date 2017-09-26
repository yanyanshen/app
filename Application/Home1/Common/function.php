<?php
function addlog($info){
    $data['account']=session('account');
    $data['ntime']=time();
    $data['nip']=sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
    $data['info']=$info;
    M('adminlog')->add($data);
    file_put_contents("aa.txt", $data);
}

