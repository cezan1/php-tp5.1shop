<?php
namespace app\index\controller;
use think\Controller;
use think\Db;


    class Cart extends Common
    {
        public function addCart(){
            if($this->request->isPost()){
                //接收数据
                $goods_id=input('goods_id');
                $goods_count = input('goods_count');
                $goods_attr = input('goods_attr/a');
  
                //将数组转为字符串格式
                $goods_attr_ids = $goods_attr?implode(',',$goods_attr):'';
                model('Cart')->addCart($goods_id,$goods_count,$goods_attr_ids);
            
                    $this->success('ok','index');
              
                
            }
             
        }
        //购物车列表
        public function index(){
            $model = model('Cart');
            $data = $model->listData();
            // dump($data);exit;
            $this->assign('data',$data);
           
            $total = $model->getTotal($data);        
            $this->assign('total',$total);
            return $this->fetch();
        }
       
        public function test(){
            model('Cart')->getUserInfo();
        }
        //--------------提交订单后删除购物车表数据----------
        public function del(){
            $goods_id = input('goods_id');
            $goods_attr_ids = input('goods_attr_ids');
            model('Cart')->del($goods_id,$goods_attr_ids);
            $this->success('ok','index');
        }


        public function upcount(){
            $goods_id = input('goods_id/d');
            Db::name("cart")->where("goods_id",$goods_id)->setDec("goods_count");
            
        }
        public function addcount(){
            $goods_id = input("goods_id/d");
            Db::name("cart")->where("goods_id",$goods_id)->setInc("goods_count");
            
        }
    }