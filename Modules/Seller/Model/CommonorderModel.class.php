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

class CommonorderModel{
	
	//TODO....设置售后期的情况下进行确认收货，跟系统自动结算两个方法
	
	/**
		获取一个订单中，商品的数量，
	**/
	public function get_order_goods_quantity( $order_id,$order_goods_id=0)
	{
		
		$where = "";
		
		if( !empty($order_goods_id) && $order_goods_id >0 )
		{
			$where .= " and order_goods_id={$order_goods_id} ";
		}
		
		//原来有的数量
		
		$total_quantity = M('lionfish_comshop_order_goods')->where( "order_id ={$order_id} {$where}" )->sum('quantity');
		
		$total_quantity = empty($total_quantity) ? 0 : $total_quantity;
		
		$refund_quantity = $this->refund_order_goods_quantity( $order_id,$order_goods_id,$uniacid);
		 
		 $surplus_quantity = $total_quantity - $refund_quantity;
		 
		 return $surplus_quantity;
	}
	
	/**
		已经退掉的订单商品数量
	**/
	public function refund_order_goods_quantity( $order_id,$order_goods_id=0 )
	{
		$where = "";
		
		if( !empty($order_goods_id) && $order_goods_id >0 )
		{
			$where .= " and order_goods_id={$order_goods_id} ";
		}
		
		$refund_quantity = M('lionfish_comshop_order_goods_refund')->where("order_id ={$order_id} {$where}")->sum('real_refund_quantity');
		
		$refund_quantity = empty($refund_quantity) ? 0 : $refund_quantity;
		
		return $refund_quantity;
	}
	
	/**
		该笔子订单已经退款了多少钱
	**/
	public function get_order_goods_refund_money( $order_id,$order_goods_id )
	{
		
		$where = "";
		
		if( !empty($order_goods_id) && $order_goods_id >0 )
		{
			$where .= " and order_goods_id={$order_goods_id} ";
		}
		
		$refund_money = M('lionfish_comshop_order_goods_refund')->where( "order_id ={$order_id} {$where}" )->sum('money');
		
		$refund_money = empty($refund_money) ? 0 : $refund_money;
		
		return $refund_money;
		
	}
	
	/**
		获取订单商品支付的金额公共方法
	**/
	public function get_order_goods_paymoney( $order_goods_id )
	{
		$order_goods_info  =  M('lionfish_comshop_order_goods')->where( array('order_goods_id' => $order_goods_id) )->find();
		
		if( empty($order_goods_info) )
		{
			return 0;
		}else{
			$pay_free = $order_goods_info['total'] + $order_goods_info['shipping_fare']-$order_goods_info['voucher_credit']-$order_goods_info['fullreduction_money']-$order_goods_info['score_for_money'];
			
			$pay_free = round($pay_free, 2);
			
			return $pay_free;
		}
	}
	
	/**
		获取订单金额--公共方法
		@auth fish 
	**/
	public function get_order_paymoney( $order_id )
	{
		$order_info = M('lionfish_comshop_order')->where( array('order_id' => $order_id ) )->find();
		
		if( empty($order_info) )
		{
			return 0;
		}else{
			
			$pay_free = $order_info['total'] + $order_info['shipping_fare']-$order_info['voucher_credit']-$order_info['fullreduction_money']-$order_info['score_for_money'];
			
			$pay_free = round($pay_free, 2);
			
			return $pay_free;
		}
		
	}
	
	
	/**
		退款整笔订单
		@auth lionfish 
		mail 353399459@qq.com
		time:2020-03-07
	**/
	public function refund_one_order( $order_id )
	{
		
		$order_goods_list = M('lionfish_comshop_order_goods')->field('order_goods_id,quantity')->where( array('order_id' => $order_id , 'is_refund_state' => 0) )->select();
		if( !empty($order_goods_list) )
		{
			foreach( $order_goods_list as $val)
			{
				$order_goods_id = $val['order_goods_id'];
				
				$pay_total_money = $this->get_order_goods_paymoney( $order_goods_id );
				$real_refund_quantity = $val['quantity'];
				$refund_quantity = $real_refund_quantity;
				$refund_money = $pay_total_money;
				$is_back_sellcount = 1;
				
				$this->ins_order_goods_refund($order_id, $order_goods_id,$pay_total_money,$real_refund_quantity, $refund_quantity,$refund_money, $is_back_sellcount);
			}
		}
		
	}
	
	
	/**
		插入子订单退款
	**/
	public function ins_order_goods_refund($order_id, $order_goods_id,$pay_total_money,$real_refund_quantity, $refund_quantity,$refund_money, $is_back_sellcount)
	{
		//计算需要抵扣多少佣金 ims_ lionfish_comshop_order
		
		$commiss_info = M('lionfish_community_head_commiss_order')->where( " order_id={$order_id} and order_goods_id={$order_goods_id} and type='orderbuy' " )->find();
		
		// lionfish_comshop_order_goods
		$order_goods_info = M('lionfish_comshop_order_goods')->where( " order_goods_id={$order_goods_id} " )->find();	
		
		//order_status_id
		$order_info = M('lionfish_comshop_order')->where( array('order_id' => $order_id ) )->find();
		
		$refund_data = array();
		$refund_data['order_goods_id'] = $order_goods_id;
		$refund_data['order_id'] = $order_id;
		$refund_data['uniacid'] = 0;
		$refund_data['real_refund_quantity'] = $real_refund_quantity;
		$refund_data['quantity'] = $refund_quantity;
		$refund_data['money'] = $refund_money;
		$refund_data['order_status_id'] = $order_info['order_status_id'];
		$refund_data['is_back_quantity'] = $is_back_sellcount;
		
		//---  以下需要计算了 refundorder
		$refund_data['back_score_for_money'] = 0;//退还积分兑换商品的积分 orderbuy
		$refund_data['back_send_score'] = 0; //退还赠送积分 goodsbuy
		$refund_data['back_head_orderbuycommiss'] = 0; //退还团长佣金
		$refund_data['back_head_supplycommiss'] = 0; //退还供应商佣金
		$refund_data['back_head_commiss_1'] = 0; //退1级团长佣金
		$refund_data['back_head_commiss_2'] = 0; //退2级团长佣金
		$refund_data['back_head_commiss_3'] = 0; //退3级团长佣金
		$refund_data['back_member_commiss_1'] = 0; //退会员1级佣金
		$refund_data['back_member_commiss_2'] = 0; //退会员2级佣金
		$refund_data['back_member_commiss_3'] = 0; //退会员3级佣金
		$refund_data['addtime'] = time(); //添加时间
		
		
		if( !empty($commiss_info) && $commiss_info['state'] == 1 )
		{
			//已经结算了
			
		}else{
			//未结算的
			$score_for_money_info = M('lionfish_comshop_member_integral_flow')->where( " order_id={$order_id} and order_goods_id={$order_goods_id} and type='orderbuy' " )->find();
			
			if( !empty($score_for_money_info) )
			{
				$refund_data['back_score_for_money'] =  ($refund_money / $pay_total_money ) * $score_for_money_info['score'] ;
				
				//$refund_money
				
				//退回去给用户
				D('Admin/Member')->sendMemberPointChange($order_info['member_id'],$refund_data['back_score_for_money'], 0 ,'退款'.$real_refund_quantity.'个商品，增加积分','refundorder', $order_info['order_id'] ,$order_goods_id );
				
				$send_score_info = M('lionfish_comshop_member_integral_flow')->where(" order_id={$order_id} and order_goods_id={$order_goods_id} and type='goodsbuy' ")->find();
			
			}
			if( !empty($send_score_info) )
			{
				$refund_data['back_send_score'] =  intval( ($refund_money / $pay_total_money ) * $send_score_info['score'] );
				
				$refund_data['back_send_score'] = $refund_data['back_send_score'] <= 0 ? 0 : $refund_data['back_send_score'];
				//减去相应的分数，然后插入
				
				
				M('lionfish_comshop_member_integral_flow')->where( " order_id={$order_id} and type='goodsbuy' and order_goods_id={$order_goods_id} " )->setInc('score', -$refund_data['back_send_score']);
				
			}
			
			//$refund_data['back_head_orderbuycommiss'] = 0; //退还团长佣金
			
			$head_commisslist = M('lionfish_community_head_commiss_order')->where( " type in ('orderbuy','commiss') and order_id={$order_id} and order_goods_id={$order_goods_id} and state=0 " )->select();
			
			if( !empty($head_commisslist) )
			{
				foreach( $head_commisslist as $val )
				{
					$val['money'] = $val['money'] - $val['add_shipping_fare'];
					
					if( $val['type'] == 'orderbuy' )
					{
						
						$head_orderbuycommiss = round( ($refund_money / $pay_total_money ) * $val['money'] , 2);
						
						$head_orderbuycommiss = $head_orderbuycommiss <= 0 ? 0 : $head_orderbuycommiss;
						
						M('lionfish_community_head_commiss_order')->where( array('id' => $val['id'] ) )->setInc('money', -$head_orderbuycommiss);
						
						
						$refund_data['back_head_orderbuycommiss'] = $head_orderbuycommiss;
					}
					if( $val['type'] == 'commiss' )
					{
						if( $val['level'] == 1 )
						{
							$head_levelcommiss = round( ($refund_money / $pay_total_money ) * $val['money'] , 2);
						
							$head_levelcommiss = $head_levelcommiss <= 0 ? 0 : $head_levelcommiss;
							
							M('lionfish_community_head_commiss_order')->where( "id=".$val['id'] )->setInc('money', -$head_levelcommiss);
							
							$refund_data['back_head_commiss_1'] = $head_levelcommiss;
						}
						if( $val['level'] == 2 )
						{
							$head_levelcommiss = round( ($refund_money / $pay_total_money ) * $val['money'] , 2);
						
							$head_levelcommiss = $head_levelcommiss <= 0 ? 0 : $head_levelcommiss;
							
							M('lionfish_community_head_commiss_order')->where( array('id' => $val['id'] ) )->setInc('money', -$head_levelcommiss);
							
							$refund_data['back_head_commiss_2'] = $head_levelcommiss;
						}
						if( $val['level'] == 3 )
						{
							$head_levelcommiss = round( ($refund_money / $pay_total_money ) * $val['money'] , 2);
						
							$head_levelcommiss = $head_levelcommiss <= 0 ? 0 : $head_levelcommiss;
							
							M('lionfish_community_head_commiss_order')->where( array('id' => $val['id'] ) )->setInc('money', -$head_levelcommiss);
							
							$refund_data['back_head_commiss_3'] = $head_levelcommiss;
						}
					}
				}
			}
			
			//back_head_supplycommiss    ims_lionfish_supply_commiss_order ims_ 
			
			$supply_commisslist = M('lionfish_supply_commiss_order')->where( " order_id={$order_id} and order_goods_id={$order_goods_id} and state=0 " )->find();
			
			if( !empty($supply_commisslist) )
			{
				$supply_orderbuycommiss = round( ($refund_money / $pay_total_money ) * $supply_commisslist['total_money'] , 2);
				$supply_orderbuycommiss_money = round( ($refund_money / $pay_total_money ) * $supply_commisslist['money'] , 2);
						
				$supply_orderbuycommiss = $supply_orderbuycommiss <= 0 ? 0 : $supply_orderbuycommiss;
				$supply_orderbuycommiss_money = $supply_orderbuycommiss_money <= 0 ? 0 : $supply_orderbuycommiss_money;
				
				M('lionfish_supply_commiss_order')->where( array('id' =>$supply_commisslist['id'] ) )->setInc('money', -$supply_orderbuycommiss_money );
				M('lionfish_supply_commiss_order')->where( array('id' =>$supply_commisslist['id'] ) )->setInc('total_money', -$supply_orderbuycommiss );
				
				$refund_data['back_head_supplycommiss'] = $supply_orderbuycommiss;
			}
			
			// 
			
			//$refund_data['back_member_commiss_1'] = 0; //退会员1级佣金
			//$refund_data['back_member_commiss_2'] = 0; //退会员2级佣金
			//$refund_data['back_member_commiss_3'] = 0; //退会员3级佣金
			
			$member_commisslist = M('lionfish_comshop_member_commiss_order')->where( " order_id={$order_id} and order_goods_id={$order_goods_id} and state=0 " )->select();
			
			if( !empty($member_commisslist) )
			{
				foreach( $member_commisslist as $val )
				{
						if( $val['level'] == 1 )
						{
							$member_levelcommiss = round( ($refund_money / $pay_total_money ) * $val['money'] , 2);
						
							$member_levelcommiss = $member_levelcommiss <= 0 ? 0 : $member_levelcommiss;
							
							M('lionfish_comshop_member_commiss_order')->where( array('id' => $val['id'] ) )->setInc('money', -$member_levelcommiss );
							
							
							$refund_data['back_member_commiss_1'] = $member_levelcommiss;
						}
						if( $val['level'] == 2 )
						{
							$member_levelcommiss = round( ($refund_money / $pay_total_money ) * $val['money'] , 2);
						
							$member_levelcommiss = $member_levelcommiss <= 0 ? 0 : $member_levelcommiss;
							
							M('lionfish_comshop_member_commiss_order')->where( array('id' => $val['id'] ) )->setInc('money', -$member_levelcommiss);
							
							$refund_data['back_member_commiss_2'] = $member_levelcommiss;
						}
						if( $val['level'] == 3 )
						{
							$member_levelcommiss = round( ($refund_money / $pay_total_money ) * $val['money'] , 2);
						
							$member_levelcommiss = $member_levelcommiss <= 0 ? 0 : $member_levelcommiss;
							
							M('lionfish_comshop_member_commiss_order')->where( array('id' => $val['id'] ) )->setInc('money', -$member_levelcommiss );
							
							$refund_data['back_member_commiss_3'] = $member_levelcommiss;
						}
				}
			}
			
			//INSERT
			$id = M('lionfish_comshop_order_goods_refund')->add( $refund_data );
			
			
			M('lionfish_comshop_order_goods')->where( array('order_goods_id' => $order_goods_id) )->setInc('has_refund_money', $refund_money );
			M('lionfish_comshop_order_goods')->where( array('order_goods_id' => $order_goods_id) )->setInc('has_refund_quantity', $real_refund_quantity );
							
			//has_refund_money
			
			return $id;
		}
		
	}
	
	/**
		后台订单详情 部分商品退款操作，检测是否整单退款
		TODO....
	**/
	public function check_refund_order_goods_status($order_id, $order_goods_id, $refund_money,$is_back_sellcount,$real_refund_quantity, $refund_quantity,$is_refund_shippingfare, $ref_comment = '后台操作立即退款,')
	{
		$refund_total_quantity = M('lionfish_comshop_order_goods_refund')->where( "order_id={$order_id} and order_goods_id={$order_goods_id} " )->sum('quantity');
		
		$order_goods_info = M('lionfish_comshop_order_goods')->where( "order_goods_id={$order_goods_id} " )->find();

		$order_info = M('lionfish_comshop_order')->where( array('order_id' => $order_id ) )->find();
		
		if( $refund_total_quantity >= $order_goods_info['quantity'] || $order_goods_info['has_refund_money'] >= $order_goods_info['total'])
		{
			M('lionfish_comshop_order_goods')->where( array('order_goods_id' => $order_goods_id ) )->save( array('is_refund_state' => 1 ) );
			
			$order_goods_list = M('lionfish_comshop_order_goods')->where( array('order_id' => $order_id ) )->select();
			
			$is_all_refund = true;
			
			foreach($order_goods_list as $val )
			{
				if($val['is_refund_state'] != 1)
				{
					$is_all_refund = false;
				}
			}
			
			if($is_all_refund)
			{
				$comment = $ref_comment.'退款金额:'.$refund_money.'元';
			
				if( $order_info['type'] == 'integral' )
				{
					if( $order_info['shipping_fare'] > 0 )
					{
						$comment = $ref_comment.'退款金额:'.$order_info['shipping_fare'].'元，积分:'.$order_info['total'];
					}else{
						$comment = $ref_comment.'退还积分:'.$order_info['total'];
					}
				}
				
				if($is_refund_shippingfare == 1)
				{
					$comment .= '. 退配送费：'.$order_goods_info['shipping_fare'].'元';
				}
				
				if($is_back_sellcount == 1)
				{
					$comment .= '. 退款商品数量：'.$real_refund_quantity.'. 退库存/扣销量：'.$refund_quantity;
				}else{
					$comment .= '. 退款商品数量：'.$real_refund_quantity.'. 不退库存/不扣销量';
				}
				
				
				$order_history = array();
				$order_history['uniacid'] = $_W['uniacid'];
				$order_history['order_id'] = $order_id;
				$order_history['order_status_id'] = 7;
				$order_history['notify'] = 0;
				$order_history['comment'] =  $comment;
				$order_history['date_added'] = time();
			
				M('lionfish_comshop_order_history')->add( $order_history );
				
				M('lionfish_comshop_order')->where( array('order_id' => $order_id) )->save( array('order_status_id' => 7) );
				
				
				$is_print_admin_cancleorder = D('Home/Front')->get_config_by_name('is_print_admin_cancleorder');
				
				if( isset($is_print_admin_cancleorder) && $is_print_admin_cancleorder == 1 )
				{
					D('Seller/Printaction')->check_print_order($order_id,'后台操作取消订单');
				}
				
			}
			
		}else{
			
				
		}
		
		
		
	}
	
	/**
		整单退款，切割退款金额到子订单
	**/
	public  function def_order_refund_togoods( $order_id, $refund_money,$free_tongji,$is_refund_shippingfare )
	{
	
		
		
	}
	
	
	
}
?>