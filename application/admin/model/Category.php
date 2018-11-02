<?php
namespace app\admin\model;
use think\Model;


class Category extends Model{

     //获取无限极分类方法 的所有分类信息
     public function getCateTree($id=0,$isClear=false)
     {
         //获取所有的分类信息
         $data = $this->all();
         //格式化数据
         return get_cate_tree($data,$id,1,$isClear);
     }

   public function del($cate_id){
       $hasSon = Category::get(['parent_id'=>$cate_id]);
       if($hasSon)
       {
$this->error = '有子分类不允许删除';
return FALSE;      
      }
      return Category::destroy($cate_id);

                      }
 //入库修改分类方法
public function editCategory(){
  //接收数据
    $data = input();
   
    //2 判断是否能够修改
    //修改的分类的父分类不能是当前被修改的分类下的子分类
    //$data['parent_id']为要设置的父分类
    //获取当前的分类下的子分类
    $son = $this->getCateTree($data['id']);
    foreach ($son as $key => $value){
        if($data['parent_id']==$value['id']){
            $this->error='分类设置有误';
            return FALSE;
        }
    }
    // 不能设置自己为父分类
   if($data['parent_id']==$data['id']){
       $this->error = '请勿设置子类为父分类';
       return FALSE;
   } 
   //4 数据入库修改
   //isUpdate为修改操作 allowField过滤非数据表中的子段 
$this->isUpdate(true)->allowField(true)->save($data,['id'=>$data['id']]);

}


}