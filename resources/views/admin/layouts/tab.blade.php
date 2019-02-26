<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="ie-comp">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="Bookmark" href="{{ URL::asset('img/favor.ico') }}">
    <link rel="Shortcut Icon" href="{{ URL::asset('img/favor.ico') }}"/>
    <!--[if lt IE 9]>
    <script type="text/javascript"
            src="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/lib/html5shiv.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/lib/respond.min.js') }}"></script>
    <![endif]-->
    <link href="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/static/h-ui/css/H-ui.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/static/h-ui.admin.pro.iframe/css/h-ui.admin.pro.iframe.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/lib/Hui-iconfont/1.0.9/iconfont.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/static/h-ui.admin.pro.iframe/skin/default/skin.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/static/business/css/style.css') }}"
          rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/common.css') }}"/>
    <!--[if IE 6]>
    <script type="text/javascript" src="{{ URL::asset('dist/lib/DD_belatedPNG_0.0.8a-min.js') }}"></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>置业顾问 | 管理后台</title>
</head>
<body>

@yield('content')

</body>
</html>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript"
        src="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/lib/jquery/1.9.1/jquery.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/lib/layer/3.1.1/layer.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/static/h-ui/js/H-ui.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/static/h-ui.admin.pro.iframe/js/h-ui.admin.pro.iframe.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/lib/jquery.contextmenu/jquery.contextmenu.r2.js') }}"></script>

<!--/_footer 作为公共模版分离出去-->

{{--doT、md5、七牛等相关--}}
<script type="text/javascript" src="{{ URL::asset('/js/doT.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/md5.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/qiniu.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/plupload/plupload.full.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/plupload/moxie.js') }}"></script>

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery.validation/1.14.0/jquery.validate.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery.validation/1.14.0/validate-methods.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery.validation/1.14.0/messages_zh.js') }}"></script>

{{--common.js--}}
<script type="text/javascript" src="{{ URL::asset('/js/common.js') }}"></script>

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript"
        src="{{ URL::asset('dist/static/H-ui.admin.pro.iframe-v1.0.4/H-ui.admin.pro.iframe/static/business/js/main.js') }}"></script>

@yield('script')