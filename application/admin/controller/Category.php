<?php
namespace app\admin\controller;
use think\Db;
use think\view;

/* 分类控制器 */
class Category extends Common
{
public function add()
 {
        //获取Query对象
    $queryObj = Db::name('category');
    if($this->request->isGet()){
   
        $category = $queryObj->select();
        $category = get_cate_tree($category);
        $this->assign('category',$category);
        return $this->fetch();
    }

    //调用方法写入 返回受影响行数
    $result = $queryObj->insert(input());
    if(!$result){
        $this->error('错误');
    }
    $this->success('ojbk');

}

//使用模型获取数据
/* public function add()
{
    $model = model('Category');
    if($this->request->isGet()){
        //调用模型下的自定义方法获取数据
        $category = $model->getCateTree();
        $this->assign('category',$category);
        return $this->fetch();
    }

} */
//实现显示分类列表  统一以 index 创建显示
public function index(){
    $category = model('Category')->getCateTree();
    $this->assign('category',$category);
    return $this->fetch();
}

public function del()
{   //从模板获取到id 表示要删除的分类
    $id = input('id',0,'intval');
    $model = model('Category');
    //调用模型下的自定义方法进行删除
    $re = $model->del($id);
    if($re === FALSE)
    {    //调用getError方法获取到错误信息
        $this->error($model->getError());
    }
$this->success('ok');

}

    public function edit()
 {
    //获取要修改的分类标识
    $cate_id = input('id',0,'intval');
    //获取模型对象
    $model = model('Category');
    if($this->request->isGet()){
        //获取当前要修改的分类的信息
      $info = $model->get($cate_id);
      //赋值模板显示
      $this -> assign('info',$info);
   
      //获取所有分类信息
      $category = $model->getCateTree();
      //渲染模板输出
      $this->assign('category',$category);
       return $this->fetch();  
    }
    //  先假设在模型中存在 入库修改 的方法
    $result = $model->editCategory();
    if ($result === FALSE )
    {
        $this->error($model->getError());
    }
    $this->success('ok','index');
 }

public function md5(){
    return md5('xz123');
}

}
