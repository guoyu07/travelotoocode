<header>
    <div class="header_top sys_header">
        {if $showlogo==1}
        <div class="st_logo"><a href="{$GLOBALS['cfg_m_main_url']}"><img src="{$GLOBALS['cfg_m_logo']}" alt="logo" height="30" /></a></div>
        {else}
         <div class="st_back"><a href="javascript:;" onclick="javascript:history.go(-1);"></a></div>
        {/if}

        <h1 class="tit">
            {$channelname}
        </h1>
        <div class="st_top_menu">
            <span data-am-offcanvas="{target: '#my-st-slide'}"></span>
            <div id="my-st-slide" class="am-offcanvas">
                <!--登录后-->
                <div class="am-offcanvas-bar" id="login-html">
                    <div class="st_user_header_pic">
                        <img src="{$member['litpic']}" />
                        {if !$member['mid']}
                        <p>
                            <a href="{$cmsurl}member/login">登录</a>|
                            <a href="{$cmsurl}member/register">注册</a>
                        </p>
                        {else}
                        <p><a>{$member['nickname']}</a></p>
                        {/if}
                    </div>
                    {if !empty($member['mid'])}
                    <div class="st_user_cz">
                        <a href="{$cmsurl}"><i class="ico_01"></i>首页</a>
                        <a href="{$cmsurl}member/order/list"><i class="ico_02"></i>我的订单<em>{$orderNum}</em></a>
                        <a href="{$cmsurl}member/linkman"><i class="ico_03"></i>常用联系人</a>
                        <a class="cursor" id="logout"><i class="ico_04"></i>退出</a>
                    </div>
                    {/if}
                </div>

            </div>
        </div>
    </div>
</header>
<script>
    var SITEURL = "{URL::site()}";
    $(function(){
        $('#my-st-slide').offCanvas('close');
        $('.st-slider').flexslider({pauseOnAction: false});
        $('#logout').click(function(){
           $.get(SITEURL+'member/login/ajax_out','',function(){
              window.location.href='http://'+window.location.host+SITEURL;
            });
        });
        
    })
</script>