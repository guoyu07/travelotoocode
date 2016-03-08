<div class="other_login">
    <h3><span>其他登录方式</span></h3>
    <p>
        {if $sinalogin}
        <a class="ico_wb" href="{$cmsurl}member/login/sina"><em></em><span>微博</span></a>
        {/if}
        {if $qqlogin}
        <a class="ico_qq" href="{$cmsurl}member/login/qq"><em></em><span>QQ</span></a>
        {/if}
        {if $weixinlogin}
        <a class="ico_wx" href="{$cmsurl}member/login/weixin"><em></em><span>微信</span></a>
        {/if}
    </p>
</div>