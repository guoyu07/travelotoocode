<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Member_Order
 * 订单管理
 */
class Controller_Member_Order extends Stourweb_Controller
{
    /**
     * 订单管理前置操作
     */
    public function before()
    {
        parent::before();
        $this->member = Common::session('member');
        if (empty($this->member))
        {
            Common::message(array('message' => __('unlogin'), 'jumpUrl' => $this->cmsurl . 'member/login'));
        }

    }

    /**
     * 订单列表
     */
    public function action_list()
    {
        //没有登录
        if (empty($this->member['mid']))
        {
            exit;
        }
        $row = $this->get_list();
        if(!empty($row))
        {
            $this->assign('data', $row);
            $this->display('member/order_list');
        }
        else
        {
            $this->display('member/empty_order');
        }
    }

    /**
     * 获取订单列表
     * @return mixed
     */
    private function get_list()
    {
        $row = Model_Member_Order::order_list($this->member['mid']);
        $row= self::get_data_initialization($row);
        return $row;
    }
    /**
     * 订单列表 查看更多
     */
    public function action_ajax_order_more()
    {
        $page = Common::remove_xss(intval($_GET['page']));
        $page = $page < 1 ? 1 : $page;
        $row = Model_Member_Order::order_list($this->member['mid'], $page);
        $row= self::get_data_initialization($row);
        echo (Product::list_search_format($row, $page));
    }

    /**
     * 订单列表数据处理
     * @param $data
     * @return mixed
     */
    private function get_data_initialization($data)
    {
        foreach ($data as &$v)
        {
            //订单详情
            $v['url'] = Common::get_web_url($v['webid']) . "/member/order/show?id={$v['id']}";
            //支付url
            $v['payurl'] = Common::get_web_url($v['webid']) . "/pub/pay/orderid/{$v['id']}";
            //评论url
            $v['commenturl'] = Common::get_web_url($v['webid']) . "/member/comment/index?id={$v['id']}";
            //产品缩略图
            $v['litpic'] = Common::img($v['litpic'], 150, 90);
            //下单时间
            $v['addtime'] = date('Y-m-d H:i', $v['addtime']);
            //分割订单产品名称
            $tempArr = array_filter(preg_split('`[\(\)]`', $v['productname']));
            $v['subname'] = count($tempArr) > 1 ? $tempArr[count($tempArr) - 1] : '';
            $v['productname'] = str_replace("({$v['subname']})", '', $v['productname']);
            //全额支付
            $v['price'] = $v['price'] * $v['dingnum'] + $v['childprice'] * $v['childnum'] + $v['oldprice'] * $v['oldnum'];
            //支付方式
            switch ($v['paytype'])
            {
                case '1':
                    $v['paytype'] = '全款支付';
                    break;
                case '2':
                    $v['paytype'] = '定金支付';
                    $v['price'] = ($v['dingnum'] + $v['childnum'] + $v['oldnum']) * $v['dingjin'];
                    break;
                default:
                    $v['paytype'] = '线下支付';
                    break;
            }
            //1元积分兑换
            $v['exchange'] = $GLOBALS['cfg_exchange_jifen'];
        }
        return $data;
    }
    /**
     * 订单详情
     */
    public function action_show()
    {
        $id = Common::remove_xss($_GET['id']);
        $row = Model_Member_Order::get_order_detail($id);
        //分割订单产品名称
        $tempArr = array_filter(preg_split('`[\(\)]`', $row['productname']));
        $row['subname'] = count($tempArr) > 1 ? $tempArr[count($tempArr) - 1] : '';
        $row['productname'] = str_replace("({$row['subname']})", '', $row['productname']);
        //支付金额
        $row['total'] = $row['paytype'] == 2 ? ($row['dingnum'] + $row['childnum'] + $row['oldnum']) * $row['dingjin'] : $row['price'] * $row['dingnum'] + $row['childprice'] * $row['childnum'] + $row['oldprice'] * $row['oldnum'];
        //预订人数
        $num = array();
        if ($row['dingnum'] > 0)
        {
            array_push($num, "成人{$row['dingnum']}个");
        }
        if ($row['childnum'] > 0)
        {
            array_push($num, "小孩{$row['childnum']}个");
        }
        if ($row['oldnum'] > 0)
        {
            array_push($num, "老人{$row['oldnum']}个");
        }
        $row['num'] = implode('，', $num);
        //评论
        $comment = Model_Comment::get_comment($row['ordersn']);
        if (!empty($comment))
        {
            $comment['score'] = ($comment['score1'] * 20) . '%';
        }
        $this->assign('info', $row);
        $this->assign('comment', $comment);
        $this->assign('member', $this->member);
        $this->display('member/order_show');
    }
}