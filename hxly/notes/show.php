<?php
require_once(dirname(__FILE__) . "/../include/common.inc.php");
$typeid = 18; //游记栏目
require_once SLINEINC . "/view.class.php";
$pv = new View($typeid);
if(!isset($aid)) exit('Wrong Id');
$aid = RemoveXSS($aid); //防止跨站攻击
$row = getStandardInfo($aid); //基本信息
//如果不存在则跳转至404页面
if(empty($row['id']))
{
    head404();
}
if($row['status'] < 1)
{
    if(!$User->isLogin())
    {
        Helper_Archive::showMsg('游记正在审核中...', '/notes/index.php', 1, 2);
    }
}
foreach($row as $k => $v)
{
    if($k == 'bannerpic' && !$v)
    {
        $v = '/templets/smore/notes/images/fb_top_bg.jpg';
    }
    if($k == 'litpic' && !$v)
    {
        $v = '/templets/smore/notes/images/coverpic.jpg';
    }
    $pv->Fields[$k] = $v; //模板变量赋值
}
$templet = SLINETEMPLATE . "/smore/notes/show.htm";
$pv->SetTemplet($templet);
$pv->Display();
//点击次数+1
setHits($row['id']);
exit();
//查询id
function getStandardInfo($aid)
{
    global $dsql;
    $extsql = "select * from #@__notes as n  left join (select mid,nickname,litpic as memberpic from sline_member) as m on m.mid=n.memberid having n.id=$aid";
    $row = $dsql->GetOne($extsql);
    return $row;

}

//查询id
function setHits($id)
{
    global $dsql;
    $sql = "update sline_notes set shownum=shownum+1 where id={$id}";
    $dsql->ExecuteNoneQuery($sql);
}
