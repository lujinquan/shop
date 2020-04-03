<?php
/**
 * lionfish 小程序直播插件
 *
 * ==========================================================================
 * @link      http://www.liofis.com/
 * @copyright Copyright (c) 2015 liofis.com.
 * @license   http://www.liofis.com/license.html License
 * ==========================================================================
 *
 * @author    J_da
 *
 */
namespace Seller\Controller;

class WxliveController extends CommonController
{

    protected function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $gpc = I('request.');
        $pindex = max(1, intval($gpc['page']));
        $psize = 10;
        $condition = '1';
        $offset = ($pindex - 1) * $psize;

        $keywords = trim($gpc['keywords']);
        if ($keywords !== '') {
            $condition .= " and name like '%". $keywords ."%' or anchor_name like '%". $keywords ."%'";
        }

        $list = M("lionfish_comshop_wxlive")
            ->where($condition)
            ->order('is_top desc,start_time asc')
            ->limit($offset, $psize)
            ->select();

        $total = M('lionfish_comshop_wxlive')->where($condition)->count();
        $pager = pagination2($total, $pindex, $psize);

        $this->list  = $list;
        $this->pager = $pager;
        $this->keywords = $keywords;
        $this->display();
    }

    public function setting()
    {
        if (IS_POST) {
            $data = I('request.parameter');

            $data['live_share_image'] = save_media($data['live_share_image']);
            $data['live_nav_name'] = trim($data['live_nav_name']);
            $data['live_share_title'] = trim($data['live_share_title']);
            
            D('Seller/Config')->update($data);
            show_json(1, array('url' => $_SERVER['HTTP_REFERER']));
        }
        $data = D('Seller/Config')->get_all_config();
        $this->data = $data;
        $this->display();
    }

    /**
     * 同步直播间
     */
    public function sync()
    {
        $ret = D('Home/Livevideo')->syncRoomList();
        if (isset($ret['errcode'])) {
            show_json(0, $ret['msg']);
        }

        show_json(1);
    }

}
