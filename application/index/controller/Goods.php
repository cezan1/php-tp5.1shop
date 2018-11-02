<?php
namespace app\index\controller;
use think\Controller;

    class Goods extends Common
    {
     public function index(){
        $goods_id = input('goods_id/d',0);
       
        $goods_info = model('Goods')->getGoodsInfo($goods_id);
        
        if($goods_info === FALSE){
			$this->error('参数错误','index/index/index');
        }
       $login=session('user');
       if($login){
            $gn=($goods_info['info']['goods_name']);
            $gp=($goods_info['info']['shop_price']);
            $gt=($goods_info['info']['goods_thumb']);
            $list=[
                    'goods_id'=>$goods_id,
                    'goods_name'=>$gn,
                    'shop_price'=>$gp,
                    'goods_thumb'=>$gt,
                    ];
            
            session('history',$list);
       
      

      

     }
     
  
     $this->assign('goods_info',$goods_info);
     return $this->fetch();
   

    }

}