<?php
namespace Home\Model;
use Think\Model;
/**
 * 商品分类模型
 * @author fish
 *
 */
class GoodsCategoryModel {
	
	
	/**
		获取首页的商品分类
	**/
	public function get_index_goods_category($pid = 0 ,$cate_type = 'normal', $is_show=1, $is_type_show=0)
	{
		// and pid = {$pid}
		if( empty($pid) )
		{
			$pid = 0;
		}
		
		$where = '';
	    if($is_type_show==1) {
	    	$cate_list = M('lionfish_comshop_goods_category')->where( array('is_type_show' => 1,'pid' =>$pid,'cate_type' => $cate_type ) )
			->order('sort_order desc, id desc')->select();
	    } else {
	    	$cate_list = M('lionfish_comshop_goods_category')->where( array('is_show' => 1,'pid' =>$pid,'cate_type' => $cate_type ) )
			->order('sort_order desc, id desc')->select();
	    }
			
		$need_data = array();	
			
		foreach($cate_list as $key => $cate)		
		{			
			$need_data[$key]['id'] = $cate['id'];			
			$need_data[$key]['name'] = $cate['name'];
			$need_data[$key]['banner'] = $cate['banner'] && !empty($cate['banner']) ? tomedia($cate['banner']) : '';
			//-------------- by lucas 【添加小程序图标，小程序展示分类需要图标】 Start ------------------------
			$need_data[$key]['app_icon'] = $cate['app_icon'] && !empty($cate['app_icon']) ? tomedia($cate['app_icon']) : '';
			//-------------- by lucas 【添加小程序图标，小程序展示分类需要图标】 End --------------------------
			$need_data[$key]['logo'] = $cate['logo'] && !empty($cate['logo']) ? tomedia($cate['logo']) : '';
			$need_data[$key]['sort_order'] = $cate['sort_order'];
			
			$params = array();
			$params['pid'] = $cate['id'];

			if($is_type_show==1) {
				$params['is_type_show'] = 1;
			} else {
				$params['is_show'] = 1;
			}

			$sub_cate = M('lionfish_comshop_goods_category')->field('id,name,sort_order')
						->where($params)->order('sort_order desc, id desc')->select();
			
			$need_data[$key]['sub'] = $sub_cate;
		}				
		
		
		return $need_data;
	}

	/**
	 * 获取所有分类包括子分类
	 * @param  string  $cate_type    [description]
	 * @param  integer $is_show      [description]
	 * @param  integer $is_type_show [description]
	 * @return [type]                [description]
	 */
	public function get_all_goods_category($cate_type = 'normal', $is_show=1, $is_type_show=0)
	{
		// and pid = {$pid}
		if( empty($pid) )
		{
			$pid = 0;
		}
		
		$where = '';
	    if($is_type_show==1) {
	    	$cate_list = M('lionfish_comshop_goods_category')->where( array('is_type_show' => 1, 'cate_type' => $cate_type ) )
			->order('sort_order desc, id desc')->select();
	    } else {
	    	$cate_list = M('lionfish_comshop_goods_category')->where( array('is_show' => 1, 'cate_type' => $cate_type ) )
			->order('sort_order desc, id desc')->select();
	    }
			
		$need_data = array();	
		foreach($cate_list as $key => $cate)		
		{			
			$need_data[$key]['id'] = $cate['id'];			
			$need_data[$key]['name'] = $cate['name'];
			$need_data[$key]['banner'] = $cate['banner'] && !empty($cate['banner']) ? tomedia($cate['banner']) : '';
			$need_data[$key]['logo'] = $cate['logo'] && !empty($cate['logo']) ? tomedia($cate['logo']) : '';
			$need_data[$key]['sort_order'] = $cate['sort_order'];
		}			
		
		return $need_data;
	}
	
}