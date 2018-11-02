<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

/* 
   抢购控制器
*/

 class Rob extends Common{
     //商品抢购
     protected $obj ;
     public function Robredis($count){
        $this->obj = new \RedisCache('192.168.255.133');
        for ($i=0; $i < $count; $i++){ 
            $this->obj->lpush('goods_list',$i);
        }
     }
 //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    //抢购入口方法   
    //需要做好抢购商品显示
    //定时开放抢购商品入口
    //再进入此方法实现用户商品抢购
     public function Robgoods(){
        
        $model=model('Rob');
        //获得库存商品数量
        $goods_id = input('goods_id/d');
        $count = $model->getcount($goods_id);
        $this->Robredis($count);
        //通过查看 队列的长度来判断，货物是否被抢购完
        if($this->obj->lLen('goods_list')==0){
            return '已经卖完';
        }

        //获取用户 id
        $user_id= session("user")['user_id'];

        //通过 集合的唯一性，判读此用户是否已经购买(返回1) 否则返回0(没有购买)
        if($this->obj->sIsMember('exist_list',$user_id)){
            $this->error('已经买了');
            return FALSE;

        }
        //从商品队列删除一个商品，并获取值为商品 id
        $this->obj->rpop('goods_list');

        //订单信息
        $goods_info = [
            'user_id'  =>$user_id,
            'goods_id' =>$goods_id,
         ];
        //将已买用户写入列队
        $this->obj->lpush('exist_list',$user_id);
        //订单信息存入数据库 
        $result = $model->setInfo($goods_info); 
        if($result === FALSE){
           return $this->error('数据错误');
        }
        //抢购成功
        $this->success('抢购成功');

    }
}