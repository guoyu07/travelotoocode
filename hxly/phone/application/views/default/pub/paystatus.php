<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>{if $info['status']}恭喜，购买成功{else}对不起，支付失败！{/if}-{$GLOBALS['cfg_webname']}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
{php echo Common::css('amazeui.css,style.css,extend.css');}
    {php echo Common::js('jquery.min.js,amazeui.js');}

</head>

<body>

	<div class="mid_content">
  
    <div class="success_page">
    	<div class="suc_box">
         {if $info['status']}   
            <div class="success_pic"><img src="{$cmsurl}/public/images/success.png" /></div>
            <p>恭喜，购买成功</p>
            <a class="back" href="{$info['url']}">返回</a>
        {else}
            <div class="success_pic"><img src="{$cmsurl}/public/images/error.png" /></div>
            <p>对不起，支付失败！</p>
            <a class="back" href="{$info['url']}">返回</a>
        {/if}
      </div>
    </div>

	</div>
<script>
	$(function(){
			var $hbox = $('.suc_box').innerHeight()
			//alert($hbox)
	})
</script>
</body>
</html>

