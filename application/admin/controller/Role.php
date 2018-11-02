<?php
namespace app\admin\controller;
use think\Db;
/*

*/
class Role extends Common
{
    public function add(){
        if($this->request->isGet()){
            return $this->fetch();
        }
        Db::name('role')->insert(input());
        echo 'ok';exit;
    }
    public function index(){

        $data = Db::name('role')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function del(){
        $admin_id = input('id');
        if($role_id<=1){
            $this->error('参数错误');
        }
        Db::name('role')->where('id',$role_id)->delete();
        $this->success('ok','index');

    }
    public function edit(){
     $admin_in = input('id');
     //保留超级管理员 不容许修改
     if($admin_id<=1){
         $this->error('参数错误');
     }
     if($this->request->isGet()){
        return $this->fetch();
    }
      $model=model('Admin');
     $result = $model->updateAdmin();
     if($result ===FALSE){
         $this->error($model->getError());
     }
     $this->success('ok','index');
    }
    //-------------为角色分配权限--------------
    public function disfetch(){
        if($this->request->isGet()){
        $rules = model('Rule')->getRules();
        return $this->fetch('disfetch',['rules'=>$rules]);
    }
    $role_id = input('id');
   
     $rule_ids = input('rule/a');
      //将权限id转换为字符串格式
      $rule_ids = implode(',',$rule_ids);
      //入库修改
      Db::name('role')->where('id',$role_id)->update(['rule_ids'=>$rule_ids]);
}
}
