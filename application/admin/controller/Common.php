<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

/* 公共控制器 --------------后台------------------*/
class Common extends Controller
{
    
public $request;//保存request类对象
public $is_check_login = TRUE;//属性保存是否需要校验登陆
public $admin=[];//保存用户的信息
public $is_check_rule=TRUE;//标识是否需要校验权限不需要则 设置FALSE
public function __construct(){
    parent::__construct();//执行父类的构造方法
    $this->request = request(); //将request对象保存到属性中
    //判断用户是否登陆  防翻墙
    if($this->is_check_login){
      $admin_info=cookie('admin_info');
    if(!$admin_info){
        $this->error('请先登陆','admin/Login/index');
       }
 
      //将用户的信息保存到属性中
      $this->admin=$admin_info;
      //根据用户角色判断是否需要检查权限  介个role_id=0时 是超级管理员
      if($this->admin['role_id']==0){
        $this->is_check_rule = FALSE; //拥有所有权限
        $rules = Db::name('rule')->select();
        }else{
        // 普通角色
				// 根据角色ID获取到权限id
        $role = Db::name('role')->where('id',$this->admin['role_id'])->find(); 
        if(!$role){$this->error('未分配角色');
        }
        //根据角色下的权限id获取详细权限
        $rules = Db::name('rule')->where(['id'=>['in',$role['rule_ids']]])->select(); 
      }
   
      //可以获取权限信息
      foreach($rules as $key => $value){
        // 将用户具备的权限加入到一个元素下  
        // 将数据库中的控制器名称与方法名称组装为一个字符串
        //strtolower('')  把所有字符转换为小写
        $this->admin['rule'][]=strtolower($value['controller_name'].'/'.$value['action_name']);
        // 将用户具备的导航菜单信息加入到属性中
        if($value['is_show']==1){
          $this->admin['menus'][]=$value;
          }	
       }
      //非超级管理员角色下的用户进行权限检查  为false则不需检查权限
      if($this->is_check_rule){
            //设置后台首页的名称
            if($this->is_check_rule){
              $this->admin['rule'][]='index/index';
              $this->admin['rule'][]='index/menu';
              $this->admin['rule'][]='index/top';
              $this->admin['rule'][]='index/main';
              }
        //获取用户访问控制器的方法名称
      $action =strtolower($this->request->controller().'/'.$this->request->action());
      if(!in_array($action,$$this->admin['rule'])){
        $this->error('没有权限');
        }
    }
   


  }
   
 }



}