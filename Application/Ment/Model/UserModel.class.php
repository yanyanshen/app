<?php
namespace Ment\Model;
use Think\Model;
class UserModel extends Model {
    //自动验证定义
    protected $patchValidate=true;
    protected $_validate =array(
        //array(字段，验证规则，错误提示，验证条件，附加规则，验证时间);
        //1用户名不为空，并且唯一
        array('account','require','账号不能为空'),//不能为空
        array('account','','用户名已存在',0,'unique'),//用户名唯一
        array('nickname','require','姓名不能为空'),//不能为空
    );
    function  a(){
        dump($this->select());
    }
}