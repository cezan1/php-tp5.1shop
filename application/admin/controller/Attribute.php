<?php
namespace app\admin\controller;
use think\Db;
/* 属性控制器 */
class Attribute extends Common
{
    public function add(){
       if($this->request->isGet()){
           //获取已有数据
           $type = Db::name('type')->select();
           $this->assign('type',$type);
           return $this->fetch();
       } 
       $data = input();
       if($data['attr_input_type']==1){
           //input 文本框输入
           unset($data['attr_values']);
       }else{
               if(!$data['attr_values']){
                   $this->error('select选择默认值必须填写');
               }
           }
           Db::name('attribute')->insert($data);
           $this->success('ok','index');
       }

    public function index(){
        $data = model('attribute')->listData();
        $this->assign('data',$data);
        return $this->fetch();
    } 

    public function edit(){

        $model = model('Attribute');
 if($this->request->isGet()){
     $info=$model->get(input('id'));
     $this->assign('info',$info);
     //获取所有类型
     $type = model('Type')->getAllInfo();
     $this->assign('type',$type);
     return $this->fetch();
 } 
 $result = $model->edit();
 if(!$result){
     $this->error($model->getError());
 }
 $this->success('ok','index'); 
    }

    //---------------------移除属性----------------
    public function del(){
        $model=model('Attribute');
        $result = $model->del();
        if(!$result){
            $this->error($model->getError());
        }
        $this->success('ok','index');
    }
}
