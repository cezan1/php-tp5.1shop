<?php 
namespace app\admin\controller;
use think\Db;
/**
* 商品控制器
*/
class Goods extends Common
{
	// ajax请求获取属性
	public function showAttr()
	{
		$type_id = input('type_id');

		// 调用自定义方法根据type_id的值获取属性
		$data = model('Attribute')->getAttrByTypeId($type_id);
		// dump($data);
		if(!$data){
			return '没有数据';
		}
		$this->assign('data',$data);
		return $this->fetch();
	}
	public function testMove() 
	{
		// 需要转移图片的地址
		$img_dir = '1.gif';
		require_once "../extend/ftp.php";
		$obj = new \ftp('192.168.255.133','21','ftpuse','123456');
		$obj->up_file($img_dir,$img_dir);
	}
	public function add()
	{
		if($this->request->isGet()){
			// 获取所有的类型
			$type = model('Type')->getAllInfo();
			$this->assign('type',$type);
			// 获取所有的分类
			$category = model('Category')->getCateTree();
			$this->assign('category',$category);
			return $this->fetch();
		}
		$model = model('Goods');
		$result = $model->addGoods();
		if($result === FALSE){
			$this->error($model->getError());
		}
		$this->success('添加成功','index');
	}
	// 显示商品列表
	public function index()
	{
		// 调用模型下的自定义方法获取数据
		$model = model('Goods');
		$data = $model->listData();
		$this->assign('data',$data);
		// 获取所有的分类
		$category = model('Category')->getCateTree(0,true);
		$this->assign('category',$category);
		return $this->fetch();
	}

	// 显示商品回收站
	public function recycle()
	{
		// 调用模型下的自定义方法获取数据
		$model = model('Goods');
		$data = $model->listData(1);//查询已经被删除的商品
		$this->assign('data',$data);
		// 获取所有的分类
		$category = model('Category')->getCateTree(0,true);
		$this->assign('category',$category);
		return $this->fetch();
	}
	// ajax切换状态
	public function changeStatus()
	{
		$model= model('Goods');
		// 返回FALSE表示修改失败 返回0或者1表示正常
		$result = $model->changeStatus();
		// ajax请求通过status判断操作是否正常 0 表示异常 如果为1表示正常 可以通过goods_status判断最终的商品对应的状态
		if($result === FALSE){
			return json(['status'=>0,'msg'=>$model->getError()]);
		}
		return json(['status'=>1,'goods_status'=>$result]);
	}
	public function del()
	{
		$goods_id = input('id/d',0);
		// 修改is_del 状态
		Db::name('goods')->where('id',$goods_id)->setField('is_del',1);
		$this->success('ok','index');
	}
	// 商品还原
	public function restore()
	{
		$goods_id = input('id/d',0);
		// 修改is_del 状态
		Db::name('goods')->where('id',$goods_id)->setField('is_del',0);
		$this->success('ok','index');
	}
	// 彻底删除
	public function remove()
	{
		$goods_id = input('id/d',0);
		// 修改is_del 状态
		Db::name('goods')->where('id',$goods_id)->delete();
		$this->success('ok','index');
	}
	public function delPic()
	{
		$img_id = input('img_id/d');
		Db::name('goods_img')->where('id',$img_id)->delete();
		return json(['status'=>1,'msg'=>'ok']);
	}
	public function edit()
	{
		$goods_id = input('id/d',0);
		$model = model('Goods');
		if($this->request->isGet()){
			$goods_info = $model->get($goods_id);
			$this->assign('goods_info',$goods_info);
			// 获取所有分类
			$category = model('Category')->getCateTree();
			$this->assign('category',$category);
			//获取类型属性
			$type = model('Type')->getAllInfo();
			$this->assign('type',$type);
			// 获取已有的相册
			$pics = Db::name('goods_img')->where('goods_id',$goods_id)->select();
			$this->assign('pics',$pics);
			return $this->fetch();
		}
		// 修改商品
		$result = $model->editGoods();

		if($result === FALSE){
			$this->error($model->getError());
		}
		//修改属性
		$editAttr = model('GoodsAttr')->insertData($goods_id,input('attr_id/a'),input('attr/a'));
		
		if($editAttr === FALSE){
			$this->error($model->getError());
		}

	return	$this->success('ok','index');
	}
}