<?php
/**
 * 发布游记 必须先登陆
 */
require_once(dirname(__FILE__) . "/../include/common.inc.php");
require_once SLINEINC . "/view.class.php";
//登陆跳转
if (!$User->isLogin()) {
    header("Location: " . $cfg_cmsurl . "/member/login.php");
    exit;
}
$typeid = 101; //游记
require_once SLINEINC . "/view.class.php";
$pv = new View($typeid);
$pv->GetChannelKeywords($typeid); //根据栏目类型获取关键词.介绍,栏目名称
$pv->Fields['bannerpic']='/templets/smore/notes/images/fb_top_bg.jpg';
$pv->Fields['litpic']='/templets/smore/notes/images/coverpic.jpg';
$pv->Fields['write']='添加游记';
$templet = SLINETEMPLATE . "/" . $cfg_df_style . "/notes/write.htm";
$pv->SetTemplet($templet);
$pv->Display();
exit();

?>