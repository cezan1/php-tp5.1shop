<?php
namespace app\index\model;
use think\Model;
/*
   用户模型
*/
class User extends Model{
    
    public function regist()
    {
       $data = input();
       //检查用户名是否重复
       if($this->get(['username'=>$data['username']])){
           $this->error = '用户名重复';
           return FALSE;   
       }
       
       //进行盐加密
       //生成盐
       $data['salt'] = rand(100000,999999);//向$data追加salt写入随机数
       $data['pwd'] = md6($data['password'],$data['salt']);
       $this->allowField(true)->save($data);
       
    }
    //登陆校验
    public function login(){
        $data=input();
        
        //获取用户信息
        $user_info = $this->get(['username'=>$data['username']]);
        if(!$user_info){
            $this->error = '用户名错误';
            return FALSE;
         } 
         //根据用户提交的密码安装注册的规则加密与数据库中的密码进行比对
         if($user_info->getAttr('pwd')!=md6($data['password'],$user_info->getAttr('salt'))){
             $this->error = '密码错误';
            
         }
     //保存用户状态到 session
     session('user',['user_id'=>$user_info->id,'username'=>$user_info->username]);
    
     	// 登录完成触发转移
		model('Cart')->cookie2db();
    }

   
}
