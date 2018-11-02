<?php
namespace app\admin\model;
use think\Model;


class Admin extends Model{

    //----------更新管理员方法
    public function updateAdmin(){
        //接收数据
       $data = input();
       $where = [
           'username'=>$data['username'],
            'id'=>['neq',$data['id']]

       ];
 
       if($user_info){
           $this->error = '用户名重复';
           return FALSE;
       }
       if($data['password']){
           //有提交密码 修改密码
           $data['password']=md5($data['password']);
       }else{
           //没有提交密码表示不修改
           unset($data['password']);
       }
       return $this->isUpdate(true)->allowField(true)->save($data);
    }
    public function addAdmin(){
        //1接收参数
        $data = input();
        $user_info = Admin::get(['username'=>$data['username']]);
        if($user_info){
            $this->error = '用户名重复';
            return FALSE;
        }
    }


//-------------------登陆验证------------------------
    public function login(){
        $data = input();
       $obj =new \think\captcha\Captcha();
       if(!$obj->check($data['captcha'])){
           $this->error = '验证码错误';
           return FALSE;
       }
      $where = [
          'username'=>$data['username'],
          'password'=>md5($data['password'])
      ];
      $user_info = Admin::get($where);
      if(!$user_info){
       $this->error = '用户名或密码错误';
       return FALSE;
   }
     //保存用户登陆状态
     $time = isset($data['remember'])?3600*24*7:0;
     //将用户信息写入cookie
     cookie('admin_info',$user_info->toArray(),$time);


    }
      
        


}
