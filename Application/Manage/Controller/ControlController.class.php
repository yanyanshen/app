<?php
namespace Manage\Controller;

use Think\Controller;

class ControlController extends CommonController
{
    // 删除操作
    public function del()
    {
        $type = I('get.type');
        $typeid = I("get.id");
        switch ($type) {
            case "feed":
                if (M('feedback')->delete($typeid)) {
                    $warning="确认要删除该记录吗";
                    $message = "删除成功";
                    $url = U('user/getfeedback');
                }
                break;
            case "admin":
                if (M('admin')->delete($typeid)) {
                    $warning="确认要删除该管理员吗";
                    $message = "删除成功";
                    $url = U('admin/adminList');
                }
                break;
            case "user":
                $userid=I("get.userid");
                $t=I("get.t");
                if($t=='xy'){
                    if (M('user u')->join("")->where("userid='$userid'")->delete()) {
                        $warning="确认要删除该用户吗";
                        $message = "删除成功";
                        $url = U('user/userList?type='.$t);
                    }
                }else{
                    switch($t){
                        case 'jl': $table="coach";break; case 'zdy': $table="guider";break; case 'jx': $table="school";break;
                    }
                    if (M('user')->where("userid='$userid'")->delete() && M($table)->where("userid='$userid'")->delete()) {
                        $warning="确认要删除该用户吗";
                        $message = "删除成功";
                        $url = U('user/userList?type='.$t);
                    }
                }
                break;
        }
        echo "<script>confirm('$warning');</script>";
        echo "<script>confirm('$message');</script>";
        echo "<script>location.href='$url'</script>";
    }
    public function add()
    {
        $type = I('get.type');
        switch ($type) {
            case "adminadd":
                unset($_POST['repass']);
                if (M('admin')->add($_POST)) {
                    $message = "添加成功";
                    $url = U('Pay/admin_add');
                }
                break;
        }
        echo "<script>alert('$message');location.href='$url'</script>";
    }
}