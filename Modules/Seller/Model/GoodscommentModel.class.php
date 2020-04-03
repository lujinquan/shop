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

class GoodscommentModel{
	
	public function show_comment_page($search = array()){
		
	    $sql='SELECT * FROM '.C('DB_PREFIX').'order_comment where 1= 1 ';
	
	    
	    if(isset($search['goods_id']))
	    {
	        $sql.=" and goods_id=".$search['goods_id'];
	    }
		/**
			$search['goods_name'] = $name;
		}
		if( !empty($order_num_alias) )
		{
			$search['order_num_alias'] = $order_num_alias;
		**/
		if( isset($search['goods_name']) && !empty($search['goods_name']) )
		{
			$sql.=" and goods_name like '%".$search['goods_name']."%'";
		}
		if( isset($search['order_num_alias']) && !empty($search['order_num_alias']) )
		{
			$sql.=" and order_num_alias like '%".$search['order_num_alias']."%'";
		}
	
	    $count=count(M()->query($sql));
	
	    $Page = new \Think\Page($count,C('BACK_PAGE_NUM'));
	
	    $show  = $Page->show();// 分页显示输出
	
	    $sql.=' order by state asc,add_time desc LIMIT '.$Page->firstRow.','.$Page->listRows;
	
	    $list=M()->query($sql);
	    			
	    foreach($list as $key => $val)
	    {
			//	goods_id name image		
			if(empty($val['goods_name']))
			{
				$goods_info = M('goods')->field('goods_id,name,image')->where( array('goods_id' => $val['goods_id']) )->find();
				$val['goods_name'] = $goods_info['name'];
				$val['goods_image'] = $goods_info['image'];
				M('order_comment')->where( array('comment_id' => $val['comment_id']) )
				->save( array('goods_name' =>$val['goods_name'],'goods_image' =>$val['goods_image']  ) );
			}
			
			//member_id order_comment order_id
			if(empty($val['order_num_alias']))
			{
				$order_info = M('order')->field('order_num_alias')->where( array('order_id' => $val['order_id']) )->find();
				$val['order_num_alias'] = $order_info['order_num_alias'];
				M('order_comment')->where( array('comment_id' => $val['comment_id']) )
				->save( array('order_num_alias' =>$val['order_num_alias']  ) );
			}
			if(empty($val['user_name']))
			{
				$member_info = M('member')->field('name,avatar')->where( array('member_id' => intval($val['member_id'])) )->find();
				$val['user_name']  = $member_info['name'];
				$val['avatar']     = $member_info['avatar'];
				
				M('order_comment')->where( array('comment_id' => $val['comment_id']) )
				->save( array('user_name' =>$val['user_name'], 'avatar' =>$val['avatar'] ) );
			}
	        
	           
	        $list[$key] = $val;
	    }
	    return array(
	        'empty'=>'<tr><td colspan="20">~~暂无数据</td></tr>',
	        'list'=>$list,
	        'page'=>$show
	    );
	
	
		/**
	    $where = array();
	    
	    if(!empty($search) && isset($search['store_id'])) {
	        $where['store_id'] = $search['store_id'];
	    }
	    
		$count=M('pick_up')->where($where)->count();
		$Page = new \Think\Page($count,C('BACK_PAGE_NUM'));
		$show  = $Page->show();// 分页显示输出	
		
		$list = M('pick_up')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		return array(
			'empty'=>'<tr><td colspan="20">~~暂无数据</td></tr>',
			'list'=>$list,
			'page'=>$show
		);	
		**/
	}
	
	
	
	public function show_pickup_member_page( $search = array() )
	{
		$where = array();
	    
	    if(!empty($search) && isset($search['store_id'])) {
	        $where['store_id'] = $search['store_id'];
	    }
		
		if(!empty($search) && isset($search['pick_up_id'])) {
	        $where['pick_up_id'] = $search['pick_up_id'];
	    }
		//
		
		
		$count=M('pick_member')->where($where)->count();
		$Page = new \Think\Page($count,C('BACK_PAGE_NUM'));
		$show  = $Page->show();// 分页显示输出	
		
		$list = M('pick_member')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		foreach( $list as $key => $val )
		{
			if( $val['pick_up_id'] == 0)
			{
				$val['pick_name'] = '<span class="red">所有店铺</span>';
			}else{
				$pick_up_info =  M('pick_up')->field('pick_name')->where( array('id' => $val['pick_up_id']) )->find();
				$val['pick_name'] = $pick_up_info['pick_name'];
			}
			$pick_order_count =  M('pick_order')->where( array('pick_member_id' => $val['member_id']) )->count();
			//name
			$val['pick_order_count'] = $pick_order_count;
			
			$val['member_info'] = M('member')->field('name,avatar')->where( array('member_id' => $val['member_id']) )->find();
			$list[$key] = $val;
		}
		
		return array(
			'empty'=>'<tr><td colspan="20">~~暂无数据</td></tr>',
			'list'=>$list,
			'page'=>$show
		);	
		
	}
	
}
?>