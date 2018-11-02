<?php
namespace app\admin\controller;
use think\Url;
use think\Request;
/* 后台首页控制器 */
class Index extends Common
{
    public function index()
    {  
        return $this->fetch();
    }

    public function menu()
    {
        $this->assign('menus',$this->admin['menus']);
        
        return $this->fetch();
    }

    public function main()
    {
        return $this->fetch();
    }

    public function top()
    {
        return $this->fetch();
    }
    public function add()
    {
        return $this->fetch();
    }

   


//-----------------------------测试类-----------------
    PUBLIC FUNCTION makeUrl()
        {
        echo Url::build('admin/Index/index','','mmp',true);
        }
    
    PUBLIC FUNCTION getquest(Request $r)
        {
            if ($r->isGet()) echo "当前为 GET 请求";
            if ($r->isPost()) echo "当前为 GET 请求";
            echo '文件名'.dump($r->module());
            echo '控制器'.dump($r->controller());
            echo '方法'.dump($r->action());
        }
    public function murl(){
    echo Url::build('fetchTemp').'<hr/>';
    echo url('type/index','id=10&type_id=1').'<hr/>';
    echo url('index/type/index').'<hr/>';
    }
    

}