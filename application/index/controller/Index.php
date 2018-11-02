<?php
namespace app\index\controller;
use think\Db;
use think\Controller;

    class Index extends Common
    {
    public function index(){
        
        if($this->request->isGet()){
          $goodsModel = model('Goods');
          $data=[]; //保存推荐状态的商品
          //获取热卖商品
          $data['hot'] = $goodsModel->getRecGoods('is_hot');
          //获取推荐商品
          $data['rec'] = $goodsModel->getRecGoods('is_rec');
          //获取新品商品
          $data['new'] = $goodsModel->getRecGoods('is_new');
         
          //从session读取用户浏览记录写入数据库
          $history=(session('history'));
          // dump($history);
          if($history){
          db('history')->insert($history);
          session('history',null);
            }

            //查数据量显示浏览记录
            $his = Db::name('history')->where('id>0')->order('id desc')->limit(5)->select();
           
            $rdata =$goodsModel->getRobShop();
            // dump($rdata);exit;
           
           
            
          $this->assign('rdata',$rdata);
          $this->assign('data',$data);
          $this->assign('homepage',1);
          $this->assign('his',$his);
          return $this->fetch();
         
        }
       

    }
     //疯狂抢购显示商品
    public function purchase(){
        if($this->request->isGet()){
            
           
          }
    }
}
    
































    // //------------------------------------------------------
    // //自定义的验证方法(测试验证器)
    // public function checkPwd2(){
    //     //假设表单传递来的数据
    //     $data=[
    //         'username'=>'xzx',
    //         'pwd'=>'123456',
    //         'role_id'=>''
    //     ];
    //     //使用助手函数实例化验证器类
             
    //          $re =$this->validate($data,'Admin.edit');
    //           if($re!==TRUE){
    //             echo $re;
    //               exit;
    //           }
    //           echo "ok";
    // }
    // //-----------------------------------------------------------
    // public function Uploadone(){
    //     if(request()->isGet()){
    //         return view();}
    //         //获取到file对象  file参数需要为上传文本框name对应的值
    //         $file = request()->file('image');
    //           //调用move方法上传  多个文件则遍历调用move方法
    //         $info = $file->move('uploads');
    //         dump($info);
    //     }

    //     public function makeImg()
    //     {
    //         if(request()->isGet()){
    //             return view('Uploadone');
    //              }
    //              $file = request()->file('image');
    //              $info = $file->move('Uploas');
    //              //计算图片地址
    //              $img_url = $info->getPathName();
    //              //打开图片
    //              $obj = \think\Image::open($img_url);
    //              //调用方法处理图片
    //              $request=$obj->thumb(100,100)->save('1.jpg');
    //              dump($img_url);

    // }

