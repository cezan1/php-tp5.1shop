<?php
namespace app\admin\model;
use think\Model;
use think\Db;
/*
权限模型

*/

class Rule extends Model{

    public function getRules(){
         $data =$this->all();
         $return=get_cate_tree($data);
         return $return; 

    }
}