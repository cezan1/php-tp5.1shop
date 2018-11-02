<?php
namespace app\index\controller;
use think\Controller;
class User extends Common
{    //注册
    public function regist(){
        if($this->request->isGet()){
            return $this->fetch();
        }
        $model = model('User');
        $result=$model->regist();
        if($result === FALSE){
            $this->error($model->getError());
    }
        $this->success('注册成功','login');

    }
    //登陆
    public function login(){
        if($this->request->isGet()){
            return $this->fetch();
        }
        $model = model('User');
        $result = $model->login();
        if($result === FALSE){
            $this->error($model->getError());
            return FALSE;
        }
        $this->redirect('index/index/index');
    }
    public function outlogin(){
       
            session('user',null);
            $this->success('退出成功','index/index/index');


    }
}