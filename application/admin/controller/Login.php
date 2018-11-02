<?php
namespace app\admin\controller;
use think\Db;
/*登陆控制器*/
class Login extends Common{
    public $is_check_login = FALSE;
    //登陆
    public function index(){
        if($this->request->isGet()){
            return $this->fetch();
        }
        $model = model('Admin');
        $result = $model->login();
        if($result === FALSE){
            $this->error($model->getError());
        }
        $this->success('登陆成功','admin/index/index');
    }

    //生成验证码
    public function captcha(){
        $obj = new \think\captcha\Captcha();
        return $obj->entry();
    }

    //退出
    public function logout()
        {
            //清除cookie退出到登陆页面
          cookie('admin_info',null);
          $this->success('退出成功','index');
        }
    
}
