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

class RechargecardController extends CommonController{
	
	public function index()
	{
		$_GPC = I('request.');
		  

        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;

		$condition = " 1 ";
        $condition .= " and deletetime = 0 ";
		
        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword'] = trim($_GPC['keyword']);
            $condition .= ' and cardname like "%'.$_GPC['keyword'].'%"';
        }

		$list = M('lionfish_comshop_member_recharge_card')->where($condition)->order('id desc')->limit( (($pindex - 1) * $psize) , $psize )->select();

        foreach ($list as &$info){
            $records = M('lionfish_comshop_member_recharge_card_record')->where( ' sign_time > 0 and member_id > 0 and recharge_card_id = '.$info['id'])->field('count(id) as ids')->find();
            if(empty($records['ids'])){
                $info['records'] = 0;
            }else{
                $info['records'] = $records['ids'];
            }
        }
		
		$total = M('lionfish_comshop_member_recharge_card')->where( $condition )->count();

        $pager = pagination2($total, $pindex, $psize);

		
		$this->list = $list;
		$this->pager = $pager;
		
		$this->_GPC = $_GPC;
		
		$this->display();
	}

	public function receive()
	{
        $_GPC = I('request.');


        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;

        $condition = " 1 ";
        $condition .= " and deletetime = 0 ";

        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword'] = trim($_GPC['keyword']);
            $condition .= ' and cardname like "%'.$_GPC['keyword'].'%"';
        }
        $sql = "select cr.*,c.cardname,c.password_type,c.valuemoney,c.cardmark,c.addtime,c.expire_time,m.username,m.telephone from ".C('DB_PREFIX'). "lionfish_comshop_member_recharge_card_record as cr left join "
            .C('DB_PREFIX')."lionfish_comshop_member_recharge_card as c on cr.recharge_card_id = c.id left join "
            .C('DB_PREFIX')."lionfish_comshop_member as m on cr.member_id = m.member_id where cr.member_id > 0 order by sign_time desc limit " . (($pindex - 1) * $psize) . "," . $psize;
        $list = M()->query($sql);
        foreach ($list as $k => &$v) {
            $len = strlen($v['password']);
            $xing = '';
            for($i = 0; $i < $len - 5; $i++ ){
                $xing .= '*';
            }
            $v['password'] = substr($v['password'],0,1) . $xing . substr($v['password'],-4);
        }

        $count_sql = "select count(cr.id) ids from ".C('DB_PREFIX'). "lionfish_comshop_member_recharge_card_record as cr left join "
        .C('DB_PREFIX')."lionfish_comshop_member_recharge_card as c on cr.recharge_card_id = c.id left join "
        .C('DB_PREFIX')."lionfish_comshop_member as m on cr.member_id = m.member_id where cr.member_id > 0 order by sign_time desc ";
        $totals = M()->query($count_sql);

        $total = $totals['ids'];
//        dump($total);exit;
        $pager = pagination2($total, $pindex, $psize);


        $this->list = $list;
        $this->pager = $pager;

        $this->_GPC = $_GPC;

        $this->display();
	}

    public function detail()
    {
        $_GPC = I('request.');


        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;

        $condition = " 1 ";
        $condition .= " and c.id = ".$_GPC['id']." ";

        if (!empty($_GPC['telephone'])) {
            $_GPC['telephone'] = trim($_GPC['telephone']);
            $condition .= ' and m.telephone like "%'.$_GPC['telephone'].'%"';
        }
        if (!empty($_GPC['is_use'])) {
            if($_GPC['is_use'] == 1){
                $condition .= ' and cr.member_id > 0 ';
            }elseif($_GPC['is_use'] == 2){
                $condition .= ' and cr.member_id = 0 ';
            }
//            $_GPC['telephone'] = trim($_GPC['telephone']);

        }
        $sql = "select cr.*,c.cardname,c.password_type,c.valuemoney,c.cardmark,c.addtime,c.expire_time,m.username,m.telephone from ".C('DB_PREFIX'). "lionfish_comshop_member_recharge_card_record as cr left join "
            .C('DB_PREFIX')."lionfish_comshop_member_recharge_card as c on cr.recharge_card_id = c.id left join "
            .C('DB_PREFIX')."lionfish_comshop_member as m on cr.member_id = m.member_id where ".$condition." order by sign_time desc limit " . (($pindex - 1) * $psize) . "," . $psize;
//        var_dump($sql);exit;
        $list = M()->query($sql);
        foreach ($list as $k => &$v) {
            $len = strlen($v['password']);
            $xing = '';
            for($i = 0; $i < $len - 5; $i++ ){
                $xing .= '*';
            }
            $v['password'] = substr($v['password'],0,1) . $xing . substr($v['password'],-4);
        }
        $count_sql = "select count(cr.id) ids from ".C('DB_PREFIX'). "lionfish_comshop_member_recharge_card_record as cr left join "
        .C('DB_PREFIX')."lionfish_comshop_member_recharge_card as c on cr.recharge_card_id = c.id left join "
        .C('DB_PREFIX')."lionfish_comshop_member as m on cr.member_id = m.member_id where ".$condition." order by sign_time desc ";
        $totals = M()->query($count_sql);

        $total = $totals['ids'];
        // dump($list);exit;
        $pager = pagination2($total, $pindex, $psize);


        $this->list = $list;
        $this->pager = $pager;

        $this->_GPC = $_GPC;

        $this->display();
    }

	public function add()
	{
        $_GPC = I('request.') ;

        $id = intval($_GPC['id']);

        if (!empty($id)) {

            $item = M('lionfish_comshop_member_recharge_card')->where( array('id' => $id ) )->find();

            $record = M('lionfish_comshop_member_recharge_card_record')->where( array('recharge_card_id' => $id, 'sign_time' => array('GT',0), 'member_id' => array('GT',0) ) )->find();

            $this->record = $record;

            $this->item = $item;
        }

        if (IS_POST) {

            $data = $_GPC['data'];

            $id = $data['id'];

            $ins_data = array();
            // 储值卡名称
            $ins_data['cardname'] = $data['cardname'];
            // 储值卡密码类型：1:8位纯数字,2:10位纯数字,3:12位纯数字
            if( !empty($id) && $id > 0 ){

                // 储值卡详情描述
                $ins_data['card_describe'] = $data['card_describe'];
                // 储值卡有效期（过期时间）
                $ins_data['expire_time'] = strtotime($data['expire_time']);

                if($ins_data['expire_time'] < time()){
                    show_json(0,  array('message' => '储值卡有效期不能小于当前时间'));die();
                }
            }else{
                if($data['password_type'] != 1 && $data['password_type'] != 2 && $data['password_type'] != 3){
                    show_json(0,  array('message' => '储值卡密码类型错误'));die();
                }
                $ins_data['password_type'] = $data['password_type'];
                // 储值卡标识（自动生成）
                $last_row = M('lionfish_comshop_member_recharge_card')->field('cardmark')->order('id desc')->find();
                if(empty($last_row['cardmark'])){
                    $ins_data['cardmark'] = 'FP001';
                }else{
                    $numc = intval(substr($last_row['cardmark'],2));
                    if($numc > 999){
                        show_json(0,  array('message' => '储值卡标识超出限制'));die();
                    }
                    $change_numc = $numc + 1001;
                    $new_numc = substr($change_numc,1);
                    $ins_data['cardmark'] = 'FP'.$new_numc;
                }
                // 储值卡详情描述
                $ins_data['card_describe'] = $data['card_describe'];
                // 储值卡有效期（过期时间）
                $ins_data['expire_time'] = strtotime($data['expire_time']);
                if($ins_data['expire_time'] < time()){
                    show_json(0,  array('message' => '储值卡有效期不能小于当前时间'));die();
                }
                // 储值卡面额
                $ins_data['valuemoney'] = $data['valuemoney'];
                // 储值卡的生成数量
                if($data['cardcount'] < 1 || $data['cardcount'] > 1000){
                    show_json(0,  array('message' => '储值卡生成数量必须在1~1000之间'));die();
                }
                $ins_data['cardcount'] = $data['cardcount'];
                // 储值卡添加时间
                $ins_data['addtime'] = time();
            }




            if( !empty($id) && $id > 0 )
            {
                M('lionfish_comshop_member_recharge_card')->where( array('id' => $id) )->save( $ins_data );
                $id = $data['id'];
            }else{
                $id = M('lionfish_comshop_member_recharge_card')->add( $ins_data );

                // 生成一个个储值卡

                // 储值卡密码（自动生成）
                if($ins_data['password_type'] == 1){
                    $password_arr = batch_random(10,1,$ins_data['cardcount']);
                }elseif($ins_data['password_type'] == 2){
                    $password_arr = batch_random(12,1,$ins_data['cardcount']);
                }elseif($ins_data['password_type'] == 3){
                    $password_arr = batch_random(14,1,$ins_data['cardcount']);
                }

                for($i = 0; $i < $data['cardcount']; $i++){
                    $record_data = array();
                    // 储值卡总id
                    $record_data['recharge_card_id'] = $id;
                    // 储值卡密码（自动生成）
                    $record_data['password'] = $password_arr[$i];
                    M('lionfish_comshop_member_recharge_card_record')->add( $record_data );
                }

            }

            show_json(1,  array('url' => $_SERVER['HTTP_REFERER']));
        }
		$this->display();
	}

	public function test()
    {

//        $re = batch_random(1,1,9);
//        echo '<pre>';
//        var_dump($re);exit;
        $ins_data = array();
        // 储值卡名称
        $ins_data['cardname'] = '储值卡名称';
        // 储值卡密码类型：1:8位纯数字,2:10位纯数字,3:12位纯数字
        $ins_data['password_type'] = 1;
        // 储值卡详情描述
        $ins_data['card_describe'] = '储值卡详情描述';
        // 储值卡有效期（过期时间）
        $ins_data['expire_time'] = strtotime('2020-12-12');
        // 储值卡面额
        $ins_data['valuemoney'] = '50';
        // 储值卡的生成数量
        $ins_data['cardcount'] = 5;
        // 储值卡标识（自动生成）
        $ins_data['cardmark'] = 'FP001';
        // 储值卡添加时间
        $ins_data['addtime'] = time();

        $id = M('lionfish_comshop_member_recharge_card')->add( $ins_data );

        $password_arr = array();

        // 储值卡密码（自动生成）
        if($ins_data['password_type'] == 1){
            $password_arr = batch_random(8,1,$ins_data['cardcount']);
        }elseif($ins_data['password_type'] == 2){
            $password_arr = batch_random(10,1,$ins_data['cardcount']);
        }elseif($ins_data['password_type'] == 3){
            $password_arr = batch_random(12,1,$ins_data['cardcount']);
        }

        array_unique($password_arr);
        // 生成一个个储值卡
        for($i = 0; $i < $ins_data['cardcount']; $i++){
            $record_data = array();
            // 储值卡总id
            $record_data['recharge_card_id'] = $id;
            $record_data['password'] = $password_arr[$i];
            M('lionfish_comshop_member_recharge_card_record')->add( $record_data );
        }
    }

	public function delete()
	{
        $_GPC = I('request.') ;

        $id = intval($_GPC['id']);

        $info = M('lionfish_comshop_member_recharge_card')->where( array('id'=>$id) )->find();

//        foreach ($list as &$info){
        $records = M('lionfish_comshop_member_recharge_card_record')->where( 'recharge_card_id = '.$id.' and sign_time > 0 and member_id > 0 ')->field('count(id) as ids')->find();
//        dump($records);exit;
        if(empty($records['ids'])){
            // 删除
            M('lionfish_comshop_member_recharge_card')->where( array('id'=>$id) )->save(['deletetime'=>time()]);
            show_json(1,  array('url' => $_SERVER['HTTP_REFERER']));
        }else{
            show_json(0,  array('message' => '当前批次的储值卡已有会员使用，无法删除'));
        }


	}
}