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
 * 处理订单相关内容
 */
namespace Home\Controller;

class ApiquanController extends CommonController {
    protected function _initialize()
    {
    	parent::_initialize();
        $this->cur_page = 'apiquan';
    }
	/**
	public function get_user_info()
	{
		$token = I('get.token');
		$weprogram_token = M('weprogram_token')->field('member_id')->where( array('token' =>$token) )->find();
	    $member_id = $weprogram_token['member_id'];
		$member_info =  M('member')->field('name,avatar,score,member_id,comsiss_flag,level_id,account_money')->where( array('member_id' => $member_id) )->find();
		
		echo json_encode( array('code' => 0,'member_level_list' => $member_level_list,'level_name' => $level_name,'member_level_is_open' => $member_level_is_open_arr['value'],'is_yue_open' => $config_name['value'], 'opencommiss' => $opencommiss,'data' =>$member_info ,'is_open_integral' => $is_open_integral) );
	}
}