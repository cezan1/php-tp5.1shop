<?php 
namespace app\admin\model;
use think\Model;
use think\Db;
/**
* 类型模型
*/
class Type extends Model
{
	// 获取所有的类型信息
	public function getAllInfo()
	{
		
		// 实例化封装的redis的操作类
		$obj = new \RedisCache('192.168.255.133');
		$type_info = $obj->get('type_info');
		if(!$type_info){
			$data = Db::name('type')->select();
			// 格式化数据 转换为使用主键作为下标的数组
			foreach ($data as $key => $value) {
				$type_info[$value['id']]=$value;
			}
			$obj->set('type_info',$type_info,3600*24);
		}
		
		return $type_info;
	}

	public function updateCahe()
	{
		$obj = new \RedisCache('192.168.255.133');
		return  $obj->delete('type_info');
	}
}

?>