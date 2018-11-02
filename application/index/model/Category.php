<?php
namespace app\index\model;
use think\Model;

/*
 ------===========     前台分类模型   ===========----------
*/

class Category extends Model
{
    //获取无限极分类方法 的所有分类信息
    public function getCateTree($id=0,$isClear=false)
    {
        //获取所有的分类信息
        $data = $this->all();
        //格式化数据
        return get_cate_tree($data,$id,1,$isClear);
    }
}