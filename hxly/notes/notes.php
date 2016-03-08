<?php
require_once(dirname(dirname(__FILE__))."/member/config.php");
if(!$User->isLogin())
{
    header("Location: " . $cfg_cmsurl . "/member/login.php");
    exit;
}

$uid=empty($uid)? "" : RemoveXSS($uid);

$pv = new View(0);

if($dopost=='mynotes')
{
     $pagename = $dopost;//当前页面,用于左侧导航选中

     $pv->Fields['pagename'] = $pagename;

     $pv->SetTemplet(SLINETEMPLATE."/smore/notes/mynotes.htm");

     $pv->Display();

     exit();
}

//编辑游记
if($dopost=='editnotes')
{
    $id=(int)$_GET['id'];
    $typeid = 101; //游记
    $pv = new View($typeid);
    $pv->GetChannelKeywords($typeid); //根据栏目类型获取关键词.介绍,栏目名称
    $sql = "SELECT * FROM `sline_notes` where id='$id'";
    $row=$dsql->GetOne($sql);
    foreach($row as $k=>$v)
    {
        if($k=='bannerpic'&& !$v){
            $v='/templets/smore/images/fb_top_bg.jpg';
        }
        if($k=='litpic'&& !$v){
            $v='/templets/smore/notes/images/coverpic.jpg';
        }
        $pv->Fields[$k] = $v;//模板变量赋值
    }
    $templet = SLINETEMPLATE . "/" . $cfg_df_style . "/notes/write.htm";
    $pv->SetTemplet($templet);
    $pv->Display();
    exit();
}
//删除游记
if($dopost=='delnotes')
{
  $id=(int)$_GET['id'];
  $rowNum=$dsql->ExecuteNoneQuery("delete from #@__notes where id={$id}");
  $message=$rowNum?'删除成功':'删除失败';
  Helper_Archive::showMsg($message,'/notes/notes.php?dopost=mynotes',1,2);
}