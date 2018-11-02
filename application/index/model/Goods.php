<?php
namespace app\index\model;
use think\Model;
use think\Db;

/*
     商品模型
*/
  class Goods extends Model
  {
       //获取 推荐状态 的商品
    public function getRecGoods($field)
    {
        return $this->where([$field=>1])->limit(5)->order('id')->select();
        
    }

    //根据 商品id 获取 商品所有信息
    public function getGoodsInfo($goods_id)
    {
        $data=[];
        $info = $this->where('id',$goods_id)->find();
        if(!$info || ($info['is_del']==1)){
          return FALSE;
        }

        $data['info'] = $info->toArray();//保存商品的基本属性
        //获取商品相册
        $data['img'] = Db::name('goods_img')->where('goods_id',$goods_id)->select();
        $sql = "select a.*,b.attr_name,b.attr_type from shop_goods_attr a left join shop_attribute b on a.attr_id=b.id where a.goods_id=$goods_id";
        $attr = $this->query($sql);
        
        foreach ($attr as $key => $value) {
            //拆分唯一属性追加到$data下的unique数组下

            if($value['attr_type']==1){
            $data['unique'][]=$value;
          }else{
            //当属性类型为单选属性时  attr_type==2
            //组装数据  将数据写入radio下的属性id下的数组
            $data['radio'][$value['attr_id']][]=$value;
            
           
          }
          
        }
        // dump($data);exit;
        return $data;
  
    }
    //显示抢购的商品信息
    public function getRobShop(){
      $data=[];
      $goods_ids= Db::name('rob')->field('goods_id')->where('goods_count','>',0)->select();
      
      foreach ($goods_ids as $key => $value) {
          foreach ($value as $key => $v) {
            $goods_idss[] = $v;
            
          }
      }
      // dump($goods_idss);
      
      $data = Db::name('goods')->alias('a')->field('a.goods_name,a.shop_price,a.goods_thumb')->join('shop_rob b','a.id = b.goods_id','left')->where(['a.id'=>['in',$goods_idss]])->select(); 
      // dump($data);exit;
      if($data === FALSE){
        return false;

      }
      return $data;
     



    }
  }