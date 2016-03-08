<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>产品支付</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    {Common::css('amazeui.css,style.css,extend.css')}
    {Common::js('jquery.min.js,amazeui.js,template.js,layer/layer.m.js')}
</head>

<body>

  {request "pub/header/typeid/0/ispaypage/1"}
  <section>
    
  	<div class="mid_content">
     
	   <div class="confirm_order_msg">
      	<dl>
        	<dt><img src="{$info['litpic']}" /></dt>
          <dd>
          	<span>{$info['productname']}</span>
            <strong>&yen;<b>{$info['payprice']}</b></strong>
          </dd>
        </dl>
        <ul>

          {if $info['typeid']==2}
            <li><span>入住日期：</span>{$info['usedate']}</li>
            <li><span>离店日期：</span>{$info['departdate']}</li>
            <li><span>预定间数：</span>{$info['dingnum']}</li>
          {elseif $info['typeid']==3}
            <li><span>开始日期：</span>{$info['usedate']}</li>
            <li><span>结束日期：</span>{$info['departdate']}</li>
            <li><span>租车数量：</span>{$info['dingnum']}</li>
          {elseif $info['typeid']==8}
            <li><span>预定数量：</span>{$info['dingnum']}</li>
          {else}
            <li><span>使用日期：</span>{$info['usedate']}</li>
            <li><span>预定人数：</span>{$info['dingnum']}</li>
          {/if}

        </ul>
        <ul>
          <li><span>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</span>{$info['linkman']}</li>
          <li><span>联系电话：</span>{$info['linktel']}</li>
          <li><span>身份证号：</span>{$info['linkidcard']}</li>
        </ul>
      </div>
       {if $jifen['isopen']}
        <div class="integral">
            <p class="tl">积分抵现<span>您有{$jifen['userjifen']}积分可用</span></p>
            <p class="use">使用<strong>{$jifen['needjifen']}</strong>积分，抵现<strong>¥{$jifen['jifentprice']}</strong><i class="usejifen" data-price="{$jifen['jifentprice']}"></i></p>
        </div>
       {/if}
      
      <div class="payway">
      	<ul>
        	<li class="l1"><strong>支付方式</strong><span>{if !empty($info['dingjin'])}定金支付{else}全款支付{/if}</span></li>
          <li class="l2">
          	<p><strong>总金额</strong><span class="totalprice">&yen;{$info['totalprice']}</span></p>
            <p><strong>实际支付</strong><span class="payprice">&yen;{$info['payprice']}</span></p>
          </li>
          <li class="l3 zhifu-box">

              <a class="wx" data-value="3" href="javascript:;" id="weixin_btn"><i></i><span>微信支付</span></a>
              {if in_array(1,$paytypes)}
               <a class="zfb" data-value="1" href="javascript:;"><i></i><span>支付宝</span></a>
              {/if}
              {if in_array(2,$paytypes)}
                <a class="kq" data-value="2" href="javascript:;"><i></i><span>快钱支付</span></a>
              {/if}

              {if $isXianxia}
              <a data-value="0" href="javascript:;" class="xianxia">线下</a>
              {/if}

          </li>
        </ul>
      </div>

    </div>
      <form method="post" action="{$cmsurl}pub/dopay" id="payfrm">
          <input type="hidden" name="orderid" value="{$info['id']}"/>
          <input type="hidden" name="paytype" id="paytype" value="3">
          <input type="hidden" name="usejifen" id="usejifen" value="0"/>
          <input type="hidden" id="totalprice" value="{$info['totalprice']}"/>
          <input type="hidden" id="payprice" value="{$info['payprice']}"/>
		  
      </form>
	  <div id="xianxia" style="display:none">{$GLOBALS['cfg_pay_xianxia']}/div>
    
  </section>


  {request "pub/footer"}
  
  <div class="bom_link_box">
  	<div class="bom_fixed">
      <a class="confirm_pay_btn" href="javascript:;">确认支付</a>
    </div>
  </div>
  <script>
      $(function(){
          $('.zhifu-box').find('a').click(function(){
              $(this).addClass('on').siblings().removeClass('on');
              $("#paytype").val($(this).attr('data-value'));
          })


          $('.confirm_pay_btn').click(function(){
              if($("#paytype").val()==0){
				  var xianxia = $("#xianxia").html();
                  layer.open({
                      type: 1,
                      content: xianxia,
                      anim: 0,
                      style: 'position:fixed; bottom:0; left:0; width:100%; height:150px; padding:10px 0; border:none;'
                  });
              }else{
                  $("#payfrm").submit();
              }

          })
          //判断是否是微信端

          if(!isWeiXin())
          {

              $("#weixin_btn").hide();
              $("#weixin_btn").siblings('a:first').addClass('on');
              $("#paytype").val($("#weixin_btn").siblings('a:first').attr("data-value"));
          }

          //是否使用积分
          $('.usejifen').click(function(){
              var jifenprice = $(this).attr('data-price');
              var totalprice = $("#totalprice").val();
              var payprice = $("#payprice").val();
              if($(this).hasClass('on')){
                    $("#usejifen").val(0);
                    $(this).removeClass('on');
                    var t_price = Number(totalprice)+Number(jifenprice);
                    var p_price = Number(payprice)+Number(jifenprice);
                    $("#totalprice").val(t_price);
                    $("#payprice").val(p_price);
                    $('.totalprice').html('&yen;'+t_price);
                    $('.payprice').html('&yen;'+p_price);
              }else{
                  $("#usejifen").val(1);
                  $(this).addClass('on');
                  var t_price = Number(totalprice)-Number(jifenprice);
                  var p_price = Number(payprice)-Number(jifenprice);
                  $("#totalprice").val(t_price);
                  $("#payprice").val(p_price);
                  $('.totalprice').html('&yen;'+t_price);
                  $('.payprice').html('&yen;'+p_price);
              }
          })

      })




      function isWeiXin(){
          var ua = window.navigator.userAgent.toLowerCase();
          if(ua.match(/MicroMessenger/i) == 'micromessenger'){
              return true;
          }else{
              return false;
          }
      }



  </script>


</body>
</html>
