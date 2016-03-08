<?php
require_once(dirname(dirname(__FILE__))."/member/config.php");

$nocontent_msg="<div style='height:100px;line-height:100px;text-align:center'><p>暂时没有相关信息!</p></div>";

/*获取我的游记*/
if($dopost == 'getnotes')
{
    Helper_Archive::loadModule('common');
    $_model = new CommonModule('#@__notes');
    //getAll($where=null,$order=null,$limit=null,$field=null)
    $offset=($curpage-1)*$pagesize;
    $arr = $_model->getAll("memberid='$uid'","modtime desc","$offset,$pagesize");
    foreach($arr as $row)
    {
        $updatetime = Mydate('Y-m-d H:i',$row['modtime']);//提问时间
        $edit="<a href=\"/notes/notes.php?dopost=delnotes&id={$row['id']}\" class=\"sc\">删除</a><a href=\"/notes/notes.php?dopost=editnotes&id={$row['id']}\" class=\"xg\">修改</a>";
        switch($row['status']){
            case -1:
                $status="<em class=\"no\">审核未通过：{$row['reason']} 请修改</em>";
                break;
            case 0:
                $status="<em class=\"ing\">正在审核中</em>";
                break;
            case 1:
                $status="<em class=\"yes\">审核通过</em>";
                $edit='<strong class="tg">获得积分<b>30</b>分</strong>';
                break;
        }
        $productname = getProductName($row['productid'],$row['typeid']);//产品名称
        $out.=<<<EOT
         <li>
                	<div class="tit">
                  	<a href="/notes/show_{$row['id']}.html" target="_blank">{$row['title']}</a>
                    <span>最后编辑于 {$updatetime}</span>
                  </div>
                  <div class="cz">
                  	{$status}
                    <span>
                    {$edit}
                    </span>
                  </div>
                </li>
EOT;
    }

    $out = !empty($out) ? $out : $nocontent_msg;
    $totalnum = $_model->getCount("memberid='$uid'");
    $totalpage=ceil($totalnum/$pagesize);;
    $pageinfo = setPageInfo($curpage,$totalpage);
    $data = array('list'=>$out,'pageinfo'=>$pageinfo);
    echo json_encode($data);
    exit();
}



if($dopost == 'daren_notes')
{


    $offset=($curpage-1)*$pagesize;
    $arr = $dsql->getAll("select * from #@__notes as n  left join (select mid,nickname,litpic as memberpic from #@__member) as m on m.mid=n.memberid having n.status=1 limit {$offset},{$pagesize}");



    foreach($arr as $row)
    {
        $updatetime = Mydate('Y-m-d',$row['modtime']);//提问时间
        $description=cutstr_html(strip_tags($row['description']),50);
        $url="/notes/show_{$row['id']}.html";
        $memberpic = empty($row['memberpic']) ? '/templets/smore/notes/images/member_nopic.png' : $row['memberpic'];
        $out.=<<<EOT
<li>
                <a class="fl" href="{$url}"><img class="fl" src="{$row['litpic']}" width="170"
                                            height="126" title="{$row['title']}"/></a>

                <div class="box_t">
                    <a class="tit" href="{$url}">{$row['title']}</a>

                    <p class="txt">{$description}</p>

                    <p class="msg">
                        <span class="name"><img class="fl" src="{$memberpic}" width="26" height="26"/>{$row['nickname']}</span>
                        <span class="time">{$updatetime}</span>
                    </p>
                </div>
                <div class="num">
                    <span>{$row['hits']}</span>人<br/>已阅读
                </div>
            </li>
EOT;
    }

    $out = !empty($out) ? $out : $nocontent_msg;
    $totalnum = $dsql->getOne("select count(*) as total from #@__notes");
    $totalnum=$totalnum['total'];
    $totalpage=ceil($totalnum/$pagesize);;
    $pageinfo = setPageInfo($curpage,$totalpage);
    $data = array('list'=>$out,'pageinfo'=>$pageinfo);
    echo json_encode($data);
}

//分页信息

function setPageInfo($currentpage,$totalpage)
{

	$out.=' <p class="page_right"> ';
	
	//上一页
	if($currentpage > 1)
	{
		$out.=' <a class="prev" title="上一页" href="javascript:$.AjaxSearch.changePage(\'pre\');">上一页</a>';
	}
	
    //第一页
	if($totalpage > 1)
	{
		if($currentpage == 1 )
		{
		   $out.='<span class="mod_pagenav_count">&nbsp;<a title="1" href="javascript:void(0);" class="current">1</a>&nbsp;';	
		}
		else
		{
		   	 $out.='<span class="mod_pagenav_count">&nbsp;<a title="1" href="javascript:$.AjaxSearch.page(1);" >1</a>&nbsp;';	
		}
		
	}
	$out.= $totalpage >=1 ? '' : '';
	
	//是否显示省略号
    $out.= $currentpage-2 > 2 ? '<span class="point">...</span>&nbsp;' : '';
	
	//中间数字部分

      
		$list_len = 2;
        $total_list = $list_len * 2 + 1;
        if($currentpage >= $total_list)
        {
            $j = $currentpage-$list_len;
            $total_list = $currentpage+$list_len;
            if($total_list>$totalpage)
            {
                $total_list=$totalpage-1;
            }
        }
        else
        {
            $j=2;
            if($total_list>$totalpage)
            {
                $total_list=$totalpage-1;
            }
        }
        for($j;$j<=$total_list;$j++)
        {
            if($j==$currentpage)
            {
                $out.= '<a onclick="return false;" href="javascript:void(0);" class="current">'.$currentpage.'</a>&nbsp;';
            }
            else
            {
               $out.='<a title="'.$j.'" href="javascript:$.AjaxSearch.page('.$j.');">'.$j.'</a>&nbsp;';
            }
        }
	//结尾省略号
    if($totalpage-$currentpage > 2)
	{
	     $out.='<span class="point">...</span>&nbsp;';	
	}
	//最后一页
	if($totalpage > 1)
	{
		if($currentpage == $totalpage)
		{
			$out.='<a title="'.$totalpage.'" href="javascript:void" class="current">'.$totalpage.'</a></span>&nbsp;';
			
		}
		else
		{
		  $out.='<a title="'.$totalpage.'" href="javascript:$.AjaxSearch.page('.$totalpage.');">'.$totalpage.'</a></span>&nbsp;';
		}
	}
	//下一页
	if($currentpage < $totalpage)
	{
		$out.='<a class="next" title="下一页" href="javascript:$.AjaxSearch.changePage(\'next\');">下一页</a>';
	}
	
   $out.='</p>';
   
   return $out;

}

//获取子级订单价格
function getChildOrderInfo($orderid)
{
    global $dsql;
    $sql = "select * from sline_member_order where pid='$orderid'";
    $arr = $dsql->getAll($sql);
    return $arr;
}



