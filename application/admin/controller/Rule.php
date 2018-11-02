<?php
namespace app\admin\controller;
use think\Db;
/*
   权限管理
*/ 
class Rule extends Common{

    public function add(){
        $model=model('Rule');
        if($this->request->isGet()){
            //获取已有的权限信息
            $rules = $model->getRules();
            
            return $this->fetch('add',['rules'=>$rules]); 
        }
        $model->insert(input());
        $this->success('ok');
    }
    public function index(){
        //调用模型下的自定义方法获取数据
        $model = model('Rule');
        $data = $model->getRules();
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function del(){
        //子权限考虑是否可以删除
        $rule_id=input('id/d',0);
        //修改is_del状态
        Db::name('Rule')->where('id',$rule_id)->delete();
        $this->success('ok','index'); 
    }
    
   
}