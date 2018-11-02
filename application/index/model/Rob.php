<?php
namespace app\index\model;
use think\Model;
use think\Db;

/*
     
*/
  class Rob extends Model
  {
      //从数据库获取库存量
   public function getcount($goods_id){
     
        return $this->field('goods_count')->where('goods_id',$goods_id)->find();

   }
  
     //将成功抢到商品的用户写入数据库
   public function setInfo($goods_info){
      
      if($goods_info){
        Db::name('rob_user')->insertAll($goods_info);
        }
   
   }
  

  }