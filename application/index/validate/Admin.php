<?php
namespace app\index\validate;
use think\Validate;
/*-------------------------------------------------------
Admin 验证器类
---------------------------------------------------------*/
class Admin extends Validate
{
protected $rule = [
    //例子
    'username'=>'require|length:6,24',
    //可自定义规则checkPwd:xxx  在下面定义一个方法
    'pwd|密码'=>'require|checkPwd:yz|',
    'role_id'=>'require|gt:3'
];
//场景
protected $scene = [
'edit'=>['username','pwd','role_id'],
'add'=>['pwd','role_id'],
];
//设置错误提示
protected $message=[
    'username.require'=>'用户名必须存在',
    'username.lenght'=>'用户名长度不满足',
    'role_id.require'=>'role_id错误',
    'pwd.require'=>'密码必须存在',
    'pwd.checkPwd'=>'密码错误了',


];
//自定义的验证方法
//value  对应验证的数据
//r  额外的规则 xxx
//d 用户所提交的完整得数据
public function checkPwd($value,$r,$d){
    //初步简单打印处理体现 
if($value !=='123456'){
  
    return FALSE;
        exit;
}
return true;

}


}