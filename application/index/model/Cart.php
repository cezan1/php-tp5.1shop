<?php 
namespace app\index\model;
use think\Model;
use think\Db;
/**
* 
*/
class Cart extends Model
{
	// 返回用户的信息
	public function getUserInfo()
	{
		$user_info = session('user');
		if(!$user_info){
			return FALSE;
		}
		// 返回用户的id
		return $user_info['user_id'];
	}
	// 商品添加到购物车
	public function addCart($goods_id,$goods_count,$goods_attr_ids)
	{
		// 1、判断用户是否登录
		$user_id = $this->getUserInfo();
		// 2、登录 根据商品相关的信息操作数据库 根据商品id以及属性值id组合判断是否存在 存在更新数量 否则写入
		// 3、未登录 根据商品相关的信息操作cookie
		if($user_id === FALSE){
			// 未登录
			// 从cookie中获取购物车数据
			$cart = cookie('cart')?cookie('cart'):[];
			// 按照规则组装下标
			$key = $goods_id.'-'.$goods_attr_ids;
			if(array_key_exists($key,$cart)){
				// 商品id与属性值id组合相同的信息存在
				$cart[$key]+=$goods_count;
			}else{
				$cart[$key] = $goods_count;
			}
			// 最后写入数据到cookie中
			cookie('cart',$cart,30*24*3600);
		}else{
			// 登录
			$map = [
				'user_id'=>$user_id,
				'goods_id'=>$goods_id,
				'goods_attr_ids'=>$goods_attr_ids
			];
			if($this->where($map)->find()){
				// setInc为TP内置方法 可以将指定字段值增加 如果传递一个参数需要指定字段名称 增加1 如果传递两个参数 第一个参数为字段名称 第二个参数为增加的数量
				$this->where($map)->setInc('goods_count',$goods_count);
			}else{
				$map['goods_count']=$goods_count;
				$this->insert($map);
			}
		}
	}
	public function listData()
	{
		// 1、获取购物车中数据
		$user_id = $this->getUserInfo();
		$cart_info = [];
		// 2、根据商品id获取基本信息
		// 3、根据属性值id组合获取属性信息
		if($user_id === FALSE){
			// 没有登录
			$cart = cookie('cart')?cookie('cart'):[];
			// 由于是否登录购物车数据都需要 根据商品id获取基本信息属性值id组合获取属性信息。将没有登录情况下数据转换为与数据库格式一致
			foreach ($cart as $key => $value) {
				// 提取下标中的内容
				$tmp = explode('-', $key);
				$cart_info[]=[
					'goods_id'=>$tmp[0],
					'goods_attr_ids'=>$tmp[1],
					'goods_count'=>$value
				];
			}
		}else{
			$cart_info = Db::name('cart')->where(['user_id'=>$user_id])->select();
		}
		// 循环获取商品基本信息以及属性信息
		foreach ($cart_info as $key => $value) {
			// 获取商品基本信息
			$cart_info[$key]['goods_info'] = Db::name('goods')->where('id',$value['goods_id'])->find();
			// 获取属性
			// 链表查询的原生SQL语句SELECT a.attr_value,b.attr_name FROM shop_goods_attr a LEFT JOIN shop_attribute b on a.attr_id = b.id WHERE a.id IN(7,10)
			$cart_info[$key]['attr'] = Db::name('goods_attr')->alias('a')->field('a.attr_value,b.attr_name')->join('shop_attribute b','a.attr_id = b.id','left')->where(['a.id'=>['in',$value['goods_attr_ids']]])->select();
		}
		// dump($cart_info);
		return $cart_info;
	}
	// $data为listData方法返回的数据
	public function getTotal($data)
	{
		// 计算总金额与商品数量
		$money = $number = 0;
		foreach ($data as $key => $value) {
			$number += $value['goods_count'];
			$money += $value['goods_count']*$value['goods_info']['shop_price'];
		}
		return ['money'=>$money,'number'=>$number];
	}
	// 删除购物车中的商品
	public function del($goods_id,$goods_attr_ids)
	{
		$user_id = $this->getUserInfo();
		if($user_id === FALSE){
			// 从cookie中获取购物车数据
			$cart = cookie('cart')?cookie('cart'):[];
			// 组装key
			$key = $goods_id.'-'.$goods_attr_ids;
			unset($cart[$key]);
			// 最后写入数据到cookie中
			cookie('cart',$cart,30*24*3600);
		}else{
			// 登录
			$map = [
				'user_id'=>$user_id,
				'goods_id'=>$goods_id,
				'goods_attr_ids'=>$goods_attr_ids
			];
			$this->where($map)->delete();
		}
	}

	// 将cookie中的数据转移到数据库下
	public function cookie2db()
	{
		// 1、获取cookie中内容
		$cart = cookie('cart')?cookie('cart'):[];
		$user_id = $this->getUserInfo();
		if($user_id === FALSE){
			return FALSE;
		}
		foreach ($cart as $key => $value) {
			// 提取下标中的内容
			$tmp = explode('-', $key);
			$map=[
				'goods_id'=>$tmp[0],
				'goods_attr_ids'=>$tmp[1],
				'user_id'=>$user_id
			];
			if($this->where($map)->find()){
				// 以cookie中数据为准
				$this->where($map)->update(['goods_count'=>$value]);
			}else{
				$map['goods_count']=$value;
				$this->insert($map);
			}
		}
		// 清空cookie
		cookie('cart',null);
	}
	public function delCart($user_id){
		Db::name('cart')->where('user_id',$user_id)->delete();

	}
	
}

?>