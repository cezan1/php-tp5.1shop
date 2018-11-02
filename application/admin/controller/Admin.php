<?php
namespace app\admin\controller;
use think\Db;
/*
管理员用户管理
*/
class Admin extends Common 
{
    public function add(){
        if($this->request->isGet()){
      //获取已有的角色信息
      $role=Db::name('role')->select();
      $this->assign('role',$role);
      return $this->fetch();
        }
        $model=model('Admin');
        $result = $model->addAdmin();
        if($result ===FALSE){
            $this->error($model->getError());
        }
    $this->success(' ok','index');
    }

    public function index(){
        $data = Db::name('admin')->alias('a')->join('shop_role b','a.role_id=b.id','left')->field('a.*,b.role_name')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function del(){
        $admin_id=input('id');
        //保留超级管理员不被修改
        if($admin_id<=1){
            $this-> error('参数错误');

        }
        Db::naem('admin')->where('id',$admin_id)->delete();
        $this->success('ok','index');
    }

    public function edit(){
        $admin_id = input('id');
        if($admin_id<=1){
            $this->error('参数错误');

        }
        if($this->request->isGet()){
            $role = Db::name('role')->select();
            $this->assign('role',$role);
            $info = Db::name('admin')->where('id',$admin_id)->find();
            $this->assign('info',$info);
            return $this->fetch();
        }
        $result = model('Admin')->updateAdmin();
        if($result === FALSE){
            $this->error(model('Admin')-getError());
        }
        $this->success('ok','index');
    }
    
}
