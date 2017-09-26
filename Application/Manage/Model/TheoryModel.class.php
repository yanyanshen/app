<?php
namespace Manage\Model;

use Think\Model;
/**
 * 
 * @author 陈龙龙
 *  2016.7.25始 对学员的操作
 */
class TheoryModel extends Model {
    //自动验证定义
    protected $patchValidate    =   true;
    protected $_validate =array(
        //array(字段，验证规则，错误提示，验证条件，附加规则，验证时间);
        //1用户名不为空，并且唯一
        array('question','require','题目不能为空'),//不能为空
        array('subjects','require','科目不能为空'),//不能为空
        array('ChapterId','require','章节不能为空'),//不能为空
        array('ClassId','require','类型不能为空'),//不能为空
        array('answer','require','答案不能为空'),//不能为空
        array('analysis','require','解析不能为空'),//不能为空
    );
}