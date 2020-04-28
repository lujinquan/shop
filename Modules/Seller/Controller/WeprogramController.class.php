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
namespace Seller\Controller;

class WeprogramController extends CommonController{
	
	protected function _initialize(){
		parent::_initialize();
		
		//'pinjie' => '拼团介绍',
	}
	
	public function index()
	{
		
		if (IS_POST) {
			
			$data = I('request.parameter'); 
			$data['wepro_share_title'] = trim($data['wepro_share_title']);
			
			
			$data['wepro_appid'] = trim($data['wepro_appid']);
			$data['wepro_appsecret'] = trim($data['wepro_appsecret']);
			$data['wepro_partnerid'] = trim($data['wepro_partnerid']);
			$data['wepro_key'] = trim($data['wepro_key']);
			
			
			D('Seller/Config')->update($data);
			
			show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
		}
		$data = D('Seller/Config')->get_all_config();
		
		$this->data = $data;
		
		$this->display();
	}
	
	
	public function autosubscribetemplateconfig()
	{
		
		D('Seller/User')->mange_subscribetemplate_auto();
		
		show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
	}
	
	
	public function autotemplateconfig()
	{
		global $_W;
		global $_GPC;
		
		D('Seller/User')->mange_template_auto();
		
		show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
	}
	
	public function templateconfig()
	{
		$_GPC = I('request.');
		
		if( !isset($_GPC['type']) || empty($_GPC['type']) )
		{
			$_GPC['type'] = 1;
		}
		
		if($_GPC['type']=='2'){
			
			//获取选中的会员id
			$data = array();
			$data['userids'] = $_GPC['limit_user_list'];
			
			//提交更新
			if(IS_POST)
			{
		
				$platform = array();
				$platform['platform_send_info_member']= $data['userids'];
			   
				D('Seller/Config')->update($platform);
			
				show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
			}
			
			//查询下会员id		
			$send = M('lionfish_comshop_config')->where( array('name' => 'platform_send_info_member') )->find();
			
			if(!empty($send['value'])){
				
				//in语句查询会员对应信息
				$list = array();
				if( !empty($send['value']) )
				{
					$list = M('lionfish_comshop_member')->field('member_id, username as nickname,avatar')->where('member_id in('.$send['value'].')')->select();	
				}
				
				//组合
				foreach($list as $key => $vv){
					$userall =array(
						'member_id' => $vv[member_id],
						'nickname' => $vv[nickname],
						'avatar' => tomedia($vv[avatar]),	
					);
					$user_list[$key]=$userall;
				}	
			}else{
				
				$user_list = array();
			}	
			
			
			
		}
		else if( $_GPC['type']=='3' )
		{				
			$member_group_list = M('lionfish_comshop_member_group')->order('id asc')->select();
			
			$this->member_group_list = $member_group_list;
		}
		else{
			if ( IS_POST ) {
			
				$data = ((is_array($_GPC['parameter']) ? $_GPC['parameter'] : array()));
				$data['wepro_share_title'] = trim($data['wepro_share_title']);
				
				D('Seller/Config')->update($data);
				
				show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
			}
			
			$data = D('Seller/Config')->get_all_config();
		}
		
		$type = I('get.type',1);
		
		$this->type = $type;
		$this->user_list = $user_list;
		$this->_GPC = $_GPC;
		
		$this->data = $data;
		$this->display();
	}
	
	
	public function templateconfig_set()
	{
		$_GPC = I('request.');
		
		if (IS_POST) {
				
			$data = ((is_array($_GPC['parameter']) ? $_GPC['parameter'] : array()));
		
			D('Seller/Config')->update($data);
			
			show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
		}
		$data = D('Seller/Config')->get_all_config();
		$this->data = $data;
		
		include $this->display();
	}
	
	
	
	public function templateconfig_fenxi()
	{
		$_GPC = I('request.');
		
		$subtitle = $_GPC['subtitle'];
		
		$datas = $_GPC['datas'];
		$all_msg_send_type = $_GPC['all_msg_send_type'];
		$member_id = $_GPC['member_id'];
		$member_group_id = $_GPC['member_group_id'];
		$all_send_template_id = $_GPC['all_send_template_id'];
		$link = $_GPC['link'];
		
		$limit_user_list = $_GPC['limit_user_list'];
		
		if( $all_msg_send_type == 1)
		{
			//单人
			if( empty($limit_user_list)  )
			{
				show_json(0, '请选择会员');
				die();
			}
			
		}else if( $all_msg_send_type == 2 )
		{
			//用户组
			if( empty($member_group_id) || $member_group_id <= 0 )
			{
				show_json(0, '请选择会员组');
				die();
			}
		}else if( $all_msg_send_type == 3 )
		{
			//群发所有人
		}
		
		if( empty($datas) )
		{
			show_json(0, '请填写模板消息内容');
			die();
		}
		
		$template_data = array();
		
		foreach( $datas as $key => $val )
		{
			$str = $key;
			
			$str = str_replace('{{','', $str );
			$str = str_replace('.DATA','', $str );
			$tp_key = str_replace('}}','', $str );
			
			$template_data[$tp_key] = array('value' => ($val), 'color' => '#173177');
		}
		
		if( empty($all_send_template_id) )
		{
			 show_json(0, '请填写模板ID');
			 die();
		}
		if( empty($link) )
		{
			show_json(0, '请填写点击链接');
			die();
		}
		
		@set_time_limit(0);
		
		//todo///
		if( $all_msg_send_type == 3 )
		{
			$membercount = M('lionfish_comshop_member')->count();
			
			if( empty($membercount) || $membercount == 0 )
			{
				show_json(0, '暂无会员可发送消息');
				die();
			}
		
			$msg_order = array();
			$msg_order['uniacid'] = 0;
			$msg_order['template_data'] = serialize($template_data);
			$msg_order['url'] = $link;
			$msg_order['open_id'] = '';
			$msg_order['template_id'] = $all_send_template_id;
			$msg_order['type'] = 1;
			$msg_order['state'] = 0;
			$msg_order['total_count'] = $membercount;
			$msg_order['send_total_count'] = 0;
			$msg_order['addtime'] = time();
			
			M('lionfish_comshop_templatemsg')->add( $msg_order );
			
			show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
		}else{
			
			if( $all_msg_send_type == 1 )
			{
				$mb_info_all = M('lionfish_comshop_member')->field('we_openid')->where( 'member_id in ('.$limit_user_list.')' )->select();
				
				foreach($mb_info_all as $mb_info )
				{
					$msg_order = array();
					$msg_order['uniacid'] = 0;
					$msg_order['template_data'] = serialize($template_data);
					$msg_order['url'] = $link;
					$msg_order['open_id'] = $mb_info['we_openid'];
					$msg_order['template_id'] = $all_send_template_id;
					$msg_order['type'] = 0;
					$msg_order['state'] = 0;
					$msg_order['addtime'] = time();
					
					M('lionfish_comshop_templatemsg')->add( $msg_order );
				}
				
				show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
			}else if( $all_msg_send_type == 2 )
			{
				$mb_info_list = M('lionfish_comshop_member')->field('we_openid')->where( array('groupid' => $member_group_id ) )->select();
				
				foreach( $mb_info_list as $mb_info )
				{
					$msg_order = array();
					$msg_order['uniacid'] = 0;
					$msg_order['template_data'] = serialize($template_data);
					$msg_order['url'] = $link;
					$msg_order['open_id'] = $mb_info['we_openid'];
					$msg_order['template_id'] = $all_send_template_id;
					$msg_order['type'] = 0;
					$msg_order['state'] = 0;
					$msg_order['addtime'] = time();
					
					M('lionfish_comshop_templatemsg')->add($msg_order);
				}
				show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
				
			}
			
			
		}
		
	}
	
	public function subscribetemplateconfig()
	{
		$_GPC = I('request.');
		
		if ( IS_POST ) {
				
			$data = ((is_array($_GPC['parameter']) ? $_GPC['parameter'] : array()));
			$data['wepro_share_title'] = trim($data['wepro_share_title']);
			
			D('Seller/Config')->update($data);
			
			show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
		}
		$data = D('Seller/Config')->get_all_config();
		
		$this->data = $data;
		
		$this->display();
	}
	
	/**
	 * tabbar设置
	 */
	public function tabbar()
	{
		
		if ( IS_POST) {
			
			$gpc = I('request.');
			
			$data = ((is_array($gpc['parameter']) ? $gpc['parameter'] : array()));
			$param = array();
			$param['wepro_tabbar_list'] = array();
			$param['wepro_tabbar_list']['t1'] = trim($data['wepro_tabbar_text1']);
			$param['wepro_tabbar_list']['t2'] = trim($data['wepro_tabbar_text2']);
			$param['wepro_tabbar_list']['t3'] = trim($data['wepro_tabbar_text3']);
			$param['wepro_tabbar_list']['t4'] = trim($data['wepro_tabbar_text4']);
			$param['wepro_tabbar_list']['t5'] = trim($data['wepro_tabbar_text5']);
			$param['wepro_tabbar_list']['s1'] = save_media($data['wepro_tabbar_selectedIconPath1']);
			$param['wepro_tabbar_list']['s2'] = save_media($data['wepro_tabbar_selectedIconPath2']);
			$param['wepro_tabbar_list']['s3'] = save_media($data['wepro_tabbar_selectedIconPath3']);
			$param['wepro_tabbar_list']['s4'] = save_media($data['wepro_tabbar_selectedIconPath4']);
			$param['wepro_tabbar_list']['s5'] = save_media($data['wepro_tabbar_selectedIconPath5']);
			$param['wepro_tabbar_list']['i1'] = save_media($data['wepro_tabbar_iconPath1']);
			$param['wepro_tabbar_list']['i2'] = save_media($data['wepro_tabbar_iconPath2']);
			$param['wepro_tabbar_list']['i3'] = save_media($data['wepro_tabbar_iconPath3']);
			$param['wepro_tabbar_list']['i4'] = save_media($data['wepro_tabbar_iconPath4']);
			$param['wepro_tabbar_list']['i5'] = save_media($data['wepro_tabbar_iconPath5']);
			$param['wepro_tabbar_list'] = serialize($param['wepro_tabbar_list']);
			$param['open_tabbar_type'] = $data['open_tabbar_type'];
			$param['open_tabbar_out_weapp'] = $data['open_tabbar_out_weapp'];
			$param['tabbar_out_appid'] = $data['tabbar_out_appid'];
			$param['tabbar_out_link'] = $data['tabbar_out_link'];
			$param['tabbar_out_type'] = $data['tabbar_out_type'];
			$param['wepro_tabbar_selectedColor'] = $data['wepro_tabbar_selectedColor'];
			
			D('Seller/Config')->update($param);

			//-------------- by lucas 【自定义设置图片同步到服务器供小程序初始化调用】 Start ------------------------
			//首页选中图标
			@unlink(ROOT_PATH.'static/images/icon-tab-index-active.png');
			copy(ROOT_PATH.'Uploads/image/'.trim($data['wepro_tabbar_selectedIconPath1']), ROOT_PATH.'static/images/icon-tab-index-active.png');
			//首页未选中图标
			@unlink(ROOT_PATH.'static/images/icon-tab-index.png');
			copy(ROOT_PATH.'Uploads/image/'.trim($data['wepro_tabbar_iconPath1']), ROOT_PATH.'static/images/icon-tab-index.png');
			//分类未选中图标
			@unlink(ROOT_PATH.'static/images/icon-tab-type.png');
			copy(ROOT_PATH.'Uploads/image/'.trim($data['wepro_tabbar_iconPath4']), ROOT_PATH.'static/images/icon-tab-type.png');
			//分类选中图标
			@unlink(ROOT_PATH.'static/images/icon-tab-type-active.png');
			copy(ROOT_PATH.'Uploads/image/'.trim($data['wepro_tabbar_selectedIconPath4']), ROOT_PATH.'static/images/icon-tab-type-active.png');
			//购物车未选中图标
			@unlink(ROOT_PATH.'static/images/icon-tab-shop.png');
			copy(ROOT_PATH.'Uploads/image/'.trim($data['wepro_tabbar_iconPath2']), ROOT_PATH.'static/images/icon-tab-shop.png');
			//购物车选中图标
			@unlink(ROOT_PATH.'static/images/icon-tab-shop-active.png');
			copy(ROOT_PATH.'Uploads/image/'.trim($data['wepro_tabbar_selectedIconPath2']), ROOT_PATH.'static/images/icon-tab-shop-active.png');
			//个人中心未选中图标
			@unlink(ROOT_PATH.'static/images/icon-tab-me.png');
			copy(ROOT_PATH.'Uploads/image/'.trim($data['wepro_tabbar_iconPath3']), ROOT_PATH.'static/images/icon-tab-me.png');
			//个人中心选中图标
			@unlink(ROOT_PATH.'static/images/icon-tab-me-active.png');
			copy(ROOT_PATH.'Uploads/image/'.trim($data['wepro_tabbar_selectedIconPath2']), ROOT_PATH.'static/images/icon-tab-me-active.png');

			//-------------- by lucas 【自定义设置图片同步到服务器供小程序初始化调用】 End --------------------------
			
			show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
		}
		$data = D('Seller/Config')->get_all_config();
		   
		if(!is_array($data['wepro_tabbar_list']))
			$data['wepro_tabbar_list'] = unserialize($data['wepro_tabbar_list'] );
	
		$this->data = $data;
		
		$this->display();
	}
	
	
	/**
	 * 小程序一键上传
	 */
	public function wxapp()
	{
		$url = D('Home/Front')->get_config_by_name('shop_domain');
		$this->url = $url;
		$this->display();
	}
	
	public function appqrcode()
	{
        $config = D('Seller/Config')->get_all_config();
		
		$jssdk = new \Lib\Weixin\Jssdk($config['wepro_appid'], $config['wepro_appsecret']);
        $re_access_token = $jssdk->getweAccessToken();
		$data1 = json_encode([
            'path' => 'pages/index/index',
            'width' => 1280,
        ], JSON_UNESCAPED_UNICODE);
		$api = "https://api.weixin.qq.com/wxa/getwxacode?access_token={$re_access_token}";
		$result = sendhttps_post($api, $data1);
		$result2 = json_decode($result, true);
		if (empty(trim($result2['errcode']) == false)) {
        $data = [
        'code' => 1,
        'msg' => $result2['errmsg'],
        ];
        return $this->ajaxReturn($data);
		}else{
        $data = [
        'code' => 0,
        'data' => [
        'qrcode' => 'data:image/jpeg;base64,'.base64_encode($result),
        ],
        ];
        return $this->ajaxReturn($data);
		}

	}
	
	public function login()
	{
		
		$config = D('Seller/Config')->get_all_config();
		$url = D('Home/Front')->get_config_by_name('shop_domain');
		$version_info = M('lionfish_comshop_config')->where( array('name' => 'site_version') )->find();
		$version = $version_info['value'];
		$data = [
            'domain'   => trim(preg_replace('/http(s)?:\\/\\//', '', rtrim($url, '/'))),
			'ip'   => $_SERVER['SERVER_ADDR'],
			'appid'   => $config['wepro_appid'],
			'modules'   => 'lionfish_comshop',
			'version'   => str_replace('V','',$version),
			'desc'   => I('get.desc'),
        ];
		$auth_url = "http://as.shiziyu8888.com/wxapp/index/login";
		$resp = http_request($auth_url,$data);
		$response = @json_decode($resp, true);
		
        return $this->ajaxReturn($response);

	}
	
	public function preview()
	{
		
		$config = D('Seller/Config')->get_all_config();
		$url = D('Home/Front')->get_config_by_name('shop_domain');
		$data = [
            'domain'   => trim(preg_replace('/http(s)?:\\/\\//', '', rtrim($url, '/'))),
			'ip'   => $_SERVER['SERVER_ADDR'],
			'appid'   => $config['wepro_appid'],
			'modules'   => 'lionfish_comshop',
        ];
		$auth_url = "http://as.shiziyu8888.com/wxapp/index/preview";
		$resp = http_request($auth_url,$data);
		$response = @json_decode($resp, true);
		
        return $this->ajaxReturn($response);

	}
	
	public function upload()
	{
		
		$config = D('Seller/Config')->get_all_config();
		$url = D('Home/Front')->get_config_by_name('shop_domain');
		$data = [
            'domain'   => trim(preg_replace('/http(s)?:\\/\\//', '', rtrim($url, '/'))),
			'ip'   => $_SERVER['SERVER_ADDR'],
			'appid'   => $config['wepro_appid'],
			'modules'   => 'lionfish_comshop',
        ];
		$auth_url = "http://as.shiziyu8888.com/wxapp/index/upload";
		$resp = http_request($auth_url,$data);
		$response = @json_decode($resp, true);
		
        return $this->ajaxReturn($response);

	}
	

	
	
	
}
?>