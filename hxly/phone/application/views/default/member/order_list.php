<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>订单中心-{$GLOBALS['cfg_webname']}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    {php echo Common::css('amazeui.css,style.css,extend.css');}
    {php echo Common::js('jquery.min.js,amazeui.js,template.js,layer/layer.m.js');}
</head>

<body>
{request "pub/header/typeid/$typeid/isorder/1"}
<section>
    <div class="mid_content">
        <div class="user_order_list" id="list-content">
            <ul id="result_list">
            </ul>
            <div class="list_more"><a href="javascript:;" id="btn_getmore">加载更多</a></div>
        </div>
        <div class="no-content" style="display: none">
            <img src="{$GLOBALS['cfg_public_url']}images/nocon.png"/>
            <p>空空如也，什么都没有<br/>赶紧去预定，让生活充实起来吧！</p>
        </div>
    </div>
    <input type="hidden" id="page" value="{$page}"/>
</section>
{request 'pub/footer'}

<script type="text/html" id="tpl_list_item">
    {{each list as value i}}
    <li>
        <div class="date_time">{{value.addtime}}</div>
        <div class="pic_txt">
            <a href="{{value.url}}">
                <dl>
                    <dt><img src="{{value.litpic}}"/></dt>
                    <dd>
                        <span>{{value.productname}}</span>
                        <p>{{value.subname}}</p>
                    </dd>
                </dl>
                </dl>
            </a>
        </div>
        <div class="price">
            <span>总额：<em>&yen;{{value.price}}</em></span>
            <span class="fr"><i data="type">{{value.paytype}}</i>：<em>&yen;{{value.price}}</em></span>
        </div>
        <div class="cool">
            <span class="zt">订单状态</span>
                {{if value.status == 5}}
                <span class="cz"><em>交易成功</em>{{if value.ispinlun == 0}}<a class="pl" href="{{value.commenturl}}">去评论</a>{{/if}}</span>
                {{else if value.status == 4}}
                <span class="cz"><em>已退款</em></span>
                {{else if value.status == 3}}
                <span class="cz"><em>取消订单</em></span>
                {{else if value.status == 2}}
                <span class="cz"><em>付款成功</em></span>
                {{else if value.status == 1}}
                <span class="cz"><em>处理中</em></span>
                    {{if value.ispay == 0 && value.status == 1}}
                    <span class="cz"><em>等待付款</em><a class="fk_btn" href="{{value.payurl}}">去付款</a></span>
                    {{/if}}
                {{else}}
                <span class="cz"><em>未处理</em></span>
                {{/if}}
        </div>
    </li>
    {{/each}}
</script>
<script>
    //初始页码
    var initpage = '{$page}';
    $(function () {
        if(initpage !=  $("#page").val()){
            $("#page").val(initpage);
        }
        get_data();
        //获取更新数据
        $('#btn_getmore').click(function () {
            get_data();
        })

        //ajax获取数据
        var contentNum = 0;
        function get_data() {
            var url = SITEURL + 'member/order/ajax_order_more';
            layer.open({
                type: 2,
                content: '',
                time: 20

            });
            $.getJSON(url, {page: $("#page").val()}, function (data) {
                if (data.list.length > 0) {
                    var itemHtml = template('tpl_list_item', data);
                    $("#result_list").append(itemHtml);
                    contentNum++;
                }
                //设置分页
                if (data.page != -1) {
                    $("#page").val(data.page);
                } else {
                    $("#btn_getmore").hide();
                }
                //设置内内容显示
                if (contentNum == 0) {
                    $('#list-content').hide();
                    $("#no-content").show();
                }
                layer.closeAll();
            });
        }
    });
</script>
</body>
</html>
