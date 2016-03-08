<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Member_Linkman
 * 常用联系人
 */
class Controller_Member_Linkman extends Stourweb_Controller
{
    public function before()
    {
        parent::before();
        $this->member = Common::session('member');
        if (empty($this->member))
        {
            $this->request->redirect('member/login');
        }
    }

    /**
     * 首页
     */
    public function action_index()
    {
        $row = Model_Member_Linkman::get_list($this->member['mid']);
        foreach ($row as &$v)
        {
            $v['url'] = $this->cmsurl . "member/linkman/update?action=edit&id={$v['id']}";
        }
        $this->assign('data', $row);
        $this->display('member/linkman_index');
    }

    /**
     * 添加、修改联系人
     */
    public function action_update()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $listUrl = $this->cmsurl . 'member/linkman/';
        switch ($action)
        {
            //编辑
            case 'edit':
                $info = Model_Member_Linkman::detail(Common::remove_xss($_GET['id']));
                $info['action'] = '修改';
                $this->assign('info', $info);
                $this->display('member/linkman_edit');
                break;
            //添加
            case 'add':
                $info['action'] = '添加';
                $info['isadd'] = true;
                $this->assign('info', $info);
                $this->display('member/linkman_edit');
                break;
            //删除
            case 'delete':
                $id = Common::remove_xss($_GET['id']);
                $rows = DB::delete('member_linkman')->where("id={$id}")->execute();
                $message = $rows > 0 ? __('success_delete') : __('error_delete');
                Common::message(array('message' => $message, 'jumpUrl' => $listUrl));
                break;
            //新增或更新数据
            case 'save':
                $_POST = Common::remove_xss($_POST);
                if (empty($_POST['id']))
                {
                    $_POST['memberid'] = $this->member['mid'];
                    list(, $rows) = DB::insert('member_linkman', array_keys($_POST))->values(array_values($_POST))->execute();
                    $message = $rows > 0 ? __('success_add') : __('error_add');
                }
                else
                {
                    $id = $_POST['id'];
                    unset($_POST['id']);
                    $rows = DB::update('member_linkman')->set($_POST)->where("id={$id}")->execute();
                    $message = $rows > 0 ? __('success_edit') : __('error_edit');
                }
                Common::message(array('message' => $message, 'jumpUrl' => $listUrl));
                break;
        }
    }

    /**
     * 检测身份证、电话号码是否存在
     */
    public function action_ajax_check()
    {
        $_POST = Common::remove_xss($_POST);
        if (count($_POST) != 1)
        {
            exit('false');
        }
        $key=array_keys($_POST);
        $key=$key[0];
        $val=$key=='idcard'?"'{$_POST[$key]}'":$_POST[$key];
        $result = DB::select()->from('member_linkman')->where("{$key}=$val and memberid=".$this->member['mid'])->execute()->as_array();
        empty($result) ? exit('true') : exit('false');
    }
}