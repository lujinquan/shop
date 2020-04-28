<?php
/**
 * lionfish 商城系统
 *
 * ==========================================================================
 * @link      http://www.liofis.com/
 * @copyright Copyright (c) 2015 liofis.com. 
 * @license   http://www.liofis.com/license.html License
 * ==========================================================================
 *
 * @author    fish
 *
 */
namespace Seller\Model;
use Think\Model;
class GoodsCategoryModel extends Model{
	
	
	public function update($data,$cate_type='normal')
	{
		
		$ins_data = array();
		$ins_data['is_hot'] = $data['is_hot']; 
		$ins_data['is_show'] = intval($data['is_show']);
		
		if($data['is_show_topic']){
			$ins_data['is_show_topic'] = intval($data['is_show_topic']);
		}else{
			$ins_data['is_show_topic'] = 0;
		}
		
		$ins_data['is_type_show'] = intval($data['is_type_show']);
		
		$ins_data['name'] = $data['name'];
		$ins_data['logo'] = save_media($data['logo']);
		$ins_data['banner'] = save_media($data['banner']);
		//-------------- by lucas 【添加小程序图标，小程序展示分类需要图标】 Start ------------------------
		$ins_data['app_icon'] = save_media($data['app_icon']);
		//-------------- by lucas 【添加小程序图标，小程序展示分类需要图标】 End --------------------------
		$ins_data['sort_order'] = $data['sort_order'];
		$ins_data['cate_type'] = $cate_type;
		
		if(isset($data['id']) && !empty($data['id']))
		{
			//更新
			M('lionfish_comshop_goods_category')->where( array('id' => $data['id']) )->save($ins_data);
			
			$id = $data['id'];
		} else{
			$ins_data['pid'] = $data['pid'];
			//新增
			
			M('lionfish_comshop_goods_category')->add($ins_data);
			
			
		}
	
			
	}
	
	public function goodscategory_modify($datas)
	{
		
		$datas = json_decode(html_entity_decode($datas), true);

		if (!is_array($datas)) {
			show_json(0, '分类保存失败，请重试!');
		}

		$cateids = array();
		$displayorder = count($datas);

		foreach ($datas as $row) {
			$cateids[] = $row['id'];
			M('lionfish_comshop_goods_category')->where( array('id' => $row['id']) )->save(array('pid' => 0, 'sort_order' => $displayorder));
			
			if ($row['children'] && is_array($row['children'])) {
				$displayorder_child = count($row['children']);

				foreach ($row['children'] as $child) {
					$cateids[] = $child['id'];
					
					M('lionfish_comshop_goods_category')->where( array('id' => $child['id']) )->save( array('pid' => $row['id'], 'sort_order' => $displayorder_child) );
					
					--$displayorder_child;
					if ($child['children'] && is_array($child['children'])) {
						$displayorder_third = count($child['children']);

						foreach ($child['children'] as $third) {
							$cateids[] = $third['id'];
							
							M('lionfish_comshop_goods_category')->where( array('id' => $third['id']) )->save( array('pid' => $child['id'], 'sort_order' => $displayorder_third) );
							
							--$displayorder_third;
							if ($third['children'] && is_array($third['children'])) {
								$displayorder_fourth = count($third['children']);

								foreach ($child['children'] as $fourth) {
									$cateids[] = $fourth['id'];
									M('lionfish_comshop_goods_category')->where( array('id' => $fourth['id']) )->save( array('pid' => $third['id'], 'sort_order' => $displayorder_third) );
									
									--$displayorder_fourth;
								}
							}
						}
					}
				}
			}

			--$displayorder;
		}

		if (!empty($cateids)) {
			M('lionfish_comshop_goods_category')->where( 'id not in (' . implode(',', $cateids) . ')' )->delete();
		}

		
	}
	
	public function getFullCategory($fullname = false, $enabled = false,$cate_type = 'normal')
	{
		
		
		$allcategory = array();
		
		$category = M('lionfish_comshop_goods_category')->where(' cate_type="'.$cate_type.'" ')->order('pid ASC, sort_order DESC')->select();
		

		if (empty($category)) {
			return array();
		}

		foreach ($category as &$c) {
			if (empty($c['pid'])) {
				$allcategory[] = $c;

				foreach ($category as &$c1) {
					if ($c1['pid'] != $c['id']) {
						continue;
					}

					if ($fullname) {
						$c1['name'] = $c['name'] . '-' . $c1['name'];
					}

					$allcategory[] = $c1;

					foreach ($category as &$c2) {
						if ($c2['pid'] != $c1['id']) {
							continue;
						}

						if ($fullname) {
							$c2['name'] = $c1['name'] . '-' . $c2['name'];
						}

						$allcategory[] = $c2;

						foreach ($category as &$c3) {
							if ($c3['pid'] != $c2['id']) {
								continue;
							}

							if ($fullname) {
								$c3['name'] = $c2['name'] . '-' . $c3['name'];
							}

							$allcategory[] = $c3;
						}

						unset($c3);
					}

					unset($c2);
				}

				unset($c1);
			}

			unset($c);
		}

		return $allcategory;
	}
	
	
	public function get_parent_cateory($pid,$store_id)
	{
	   $bind_list = M('store_bind_class')->where(array('seller_id' => $store_id) )->select(); 
	   $list = array();
	   if(!empty($bind_list))
	   {
	       $cate_ids = array();
	       $cate_ids_str = '';
	       foreach($bind_list as $val)
	       {
	           if(!empty($val['class_1']))
	           {
	               $cate_ids[] = $val['class_1'];
	           }
	           if(!empty($val['class_2']))
	           {
	               $cate_ids[] = $val['class_2'];
	           }
	           if(!empty($val['class_3']))
	           {
	               $cate_ids[] = $val['class_3'];
	           }
	       }
	       $cate_ids_str = implode(',',$cate_ids);
	       
	       $list = M('goods_category')->field('id,pid,name')->where( array('pid'=>$pid,'id' => array('in',$cate_ids_str)) )->order('sort_order asc')->select();
	      
	       if($pid > 0)
	       {
	           $list = M('goods_category')->field('id,pid,name')->where( array('pid'=>$pid) )->order('sort_order asc')->select();
	       }
	   }
	   
	   return $list;
	}
	
	public function getInfoById($id,$field="*")
	{
	    return M('goods_category')->field($field)->where( array('id'=>$id) )->find();
	}
}
?>