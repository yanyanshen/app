<?php
namespace Manage\Model;
use Think\Model;
class CoachModel extends Model {
    //自动验证定义
    protected $patchValidate=true;
    protected $_validate =array(
        //array(字段，验证规则，错误提示，验证条件，附加规则，验证时间);
        //1用户名不为空，并且唯一
        array('account','require','账号不能为空'),//不能为空
        array('account','','该帐号已存在',0,'unique'),//用户名唯一
        array('nickname','require','姓名不能为空'),//不能为空
        array('score','require','评分不能为空'),//不能为空
        array('evalutioncount','require','评论数不能为空'),//不能为空
        array('praisecount','require','好评数不能为空'),//不能为空
        array('allcount','require','总学员数不能为空'),//不能为空
        array('passedcount','require','已通过人数不能为空'),//不能为空
        
        array('numberId','require','身份证不能为空'),//不能为空
        array('driverId','require','驾驶证不能为空'),//不能为空
        array('birthday','require','出生日期不能为空'),//不能为空
        array('serialId','require','教练证不能为空'),//不能为空
        array('driverage','require','驾龄不能为空'),//不能为空
        array('teachedage','require','教龄不能为空'),//不能为空
        
        
        
        //4qq号码验证
        array('score','number','评分必须是数字'),//2---表示只有在输入值得情况下才去验证
        array('evalutioncount','number','评论数必须是数字'),//2---表示只有在输入值得情况下才去验证
        array('praisecount','number','好评数必须是数字'),//2---表示只有在输入值得情况下才去验证
        array('allcount','number','总学员必须是数字'),//2---表示只有在输入值得情况下才去验证
        array('passedcount','number','已通过人数必须是数字'),//2---表示只有在输入值得情况下才去验证
        
       
    );
}