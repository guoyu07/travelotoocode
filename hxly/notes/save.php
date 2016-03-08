<?php
/**
 * 游记写入数据库
 */
require_once(dirname(__FILE__) . "/../include/common.inc.php");
require_once SLINEINC . "/view.class.php";
if (!$User->isLogin()) {
    exit('Access deny');
}
$typeid = 101;
$pv = new View($typeid);

if ($bannerpic == '/templets/smore/notes/images/fb_top_bg.jpg')
{
    $bannerpic = '';
}
if ($coverpic == '/templets/smore/notes/images/coverpic.jpg')
{
    $couverpic = '';
}

$modtime = time();
$content = strip_tags(str_replace(array('&lt;', '&gt;'), array('<', '>'), trim($content)), '<p><ul><li><b><i><img>');
$description = strip_tags($description);
$litpic = strip_tags($litpic);
$title = Helper_Archive::pregReplace($title, 5);
if(!$id)
{
    //添加内容
    $sql = "insert into `sline_notes` ";
    $sql.= "(memberid,title,bannerpic,litpic,description,content,modtime) ";
    $sql.= "values($User->uid,'$title','$bannerpic','$litpic','$description','$content','$modtime')";
    $result = $dsql->ExecuteNoneQuery($sql);
    if($result)
    {
        Helper_Archive::showMsg('提交成功', '/notes/notes.php?dopost=mynotes', 1, 2);
    }
    else
    {
        Helper_Archive::showMsg('提交失败', '/notes/notes.php?dopost=mynotes', 0, 2);
    }
}
else
{
    //修改内容
    $sql = "UPDATE `sline_notes` SET ";
    $sql.= "title='{$title}',";
    $sql.= "bannerpic='{$bannerpic}',";
    $sql.= "litpic='{$litpic}',";
    $sql.= "description='{$description}',";
    $sql.= "content='{$content}',";
    $sql.= "modtime='{$modtime}'";
    $sql.= "where id={$id}";
    $result = $dsql->ExecuteNoneQuery($sql);
    if($result)
    {
        Helper_Archive::showMsg('修改成功', '/notes/notes.php?dopost=mynotes', 1, 2);
    }
    else
    {
        Helper_Archive::showMsg('修改失败', '/notes/notes.php?dopost=mynotes', 0, 2);
    }
}