<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

/*
公共控制器   -----------前台-------------
*/


class Common extends Controller
{
   public $request;//保存request类对象
   public function __construct()
   {    
      
       //继承父类构造方法
       parent::__construct();
       $this->request = request();
       //获取所有分类的信息
       $category = model('Category')->getCateTree();
       $this->assign('category',$category);
       
       
   }
}
