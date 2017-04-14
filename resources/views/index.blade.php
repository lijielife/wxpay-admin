<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>XX支付管理平台</title>
    <link rel="stylesheet" type="text/css" href="/assets/js/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/default.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/index.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/zl_pay.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/layer/skin/layer.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/artdialog/skins/idialog.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/validform.min.css">

<!--     <script type="text/javascript" src="/assets/js/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/assets/js/easyui/locale/easyui-lang-zh_CN.js"></script> -->
</head>
<body>
    <div class="easyui-layout" fit="true">
        <div data-options="region:'north'" style="height:55px">
            <div class="container">
                <div class="header">
                    <div class="logo"><img id="jq_logo_img" src="/assets/images/logo1.jpg"></div>
                    <div class="txt">
                        <b>您好：<span>{{ Auth::user()->name }}</span></b>
                        <a id="update_password" href="javascript:void(0);">修改密码</a>
                        <a id="login_out" href="javascript:void(0);">退出</a>
                    </div>
                </div>
            </div>
        </div>
        <div data-options="region:'south', split:false" style="height:50px;">
            <div class="copyright">&#xA9;2016-2017 程序源科技版权所有</div>
        </div>
        <div data-options="region:'west', border:true, split:false, headerCls:'index-panel-header'" title="功能导航" style="width: 200px; overflow-x: hidden; overflow-y: auto; padding-top: 0px; background: #fcfcfc none repeat scroll 0 0;">
            <div>
                <div class="sidebar">
                    <ul class="left_nav" id="jq_menu_ul"></ul>
                </div>
            </div>
        </div>
        <div data-options="region:'center',border:false" style="overflow: hidden;">
            <div class="easyui-tabs" id="index_tabs">
                <div title="首页" style="padding:10px;">后台首页</div>
            </div>
        </div>
    </div>
    <div id="tabs_menu" style="width: 120px; display: none;">
        <div title="refresh" data-options="iconCls:'transmit'">刷新</div>
        <div class="menu-sep"></div>
        <div title="close" data-options="iconCls:'delete'">关闭</div>
        <div title="closeOther" data-options="iconCls:'delete'">关闭其他</div>
        <div title="closeAll" data-options="iconCls:'delete'">关闭所有</div>
    </div>
</body>
<script type="text/javascript" src="/assets/js/require.min.js" data-main="/assets/js/main"></script>
</html>
