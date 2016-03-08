<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
$typeid=101; //游记
require_once SLINEINC."/view.class.php";

$html = dirname(__FILE__).'/index.html';
if(isset($pageno)) $pageno = intval(preg_replace("/[^\d]/", '', $pageno));//当前页
if(file_exists($html) && $genpage != 1 )
{
    include($html);
    exit;
}
else
{
    $pv = new ListView($typeid);
    //总游记数 通过审核
    $sql='select count(*) as total from #@__notes where status=1';
    $count=$dsql->getOne($sql);
    $pv->Fields['total']=$count['total'];
    //最新攻略
    $commonSql='select * from #@__notes as n  left join (select mid,nickname,litpic,remarks from #@__member) as m on m.mid=n.memberid having n.status=1';
    //最新
    $GLOBALS['new']=$dsql->getAll($commonSql." order by modtime desc limit 5");
    //热门
    $GLOBALS['hot']=$dsql->getAll($commonSql." order by shownum desc limit 5");
    //达人
    $GLOBALS['super']=$dsql->getAll("select a.nickname,a.litpic,a.remarks,b.* ,count(b.memberid) as c from sline_member a left join sline_notes b on(a.mid = b.memberid and b.status=1)  group by b.memberid order by c desc limit 5");
    //当季
    $season=$dsql->getAll("select a.nickname,a.litpic,a.remarks,a.mid,b.*  from sline_member a left join sline_notes b on(a.mid = b.memberid and b.status=1 ) having quarter(from_unixtime(modtime,'%y%m%d'))=quarter(curdate()) order by shownum desc limit 9");
    $seasonRow3=array_slice($season,0,3);
    $seasonRow6=array_slice($season,2,6);
    $pv->GetChannelKeywords($typeid);//根据栏目类型获取关键词.介绍,栏目名称
    $templet = SLINETEMPLATE ."/smore/notes/index.htm";
    $pv->SetTemplet($templet);
    $pv->Display();
    exit();
}
