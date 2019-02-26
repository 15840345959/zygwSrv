@extends('admin.layouts.tab')

@section('content')
    <!--_menu 作为公共模版分离出去-->
    <aside class="Hui-admin-aside-wrapper">
        <div class="Hui-admin-logo-wrapper">
            <a class="logo navbar-logo" href="#">
                <i class="va-m iconpic global-logo"></i>
                <span class="va-m">置业顾问</span>
            </a>
        </div>
        <div class="Hui-admin-menu-dropdown bk_2">
            <dl class="Hui-menu">
                <dt class="Hui-menu-title">
                    业务概览
                </dt>
            </dl>
            <dl class="Hui-menu">
                <dt class="Hui-menu-title">管理员信息<i class="Hui-iconfont Hui-admin-menu-dropdown-arrow">&#xe6d5;</i></dt>
                <dd class="Hui-menu-item">
                    <ul>
                        <li><a data-href="{{ URL::asset('/admin/admin/index') }}" data-title="管理员管理"
                               href="javascript:void(0)">管理员管理</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="Hui-menu">
                <dt class="Hui-menu-title">广告位管理<i class="Hui-iconfont Hui-admin-menu-dropdown-arrow">&#xe6d5;</i></dt>
                <dd class="Hui-menu-item">
                    <ul>
                        <li><a data-href="{{ URL::asset('/admin/ad/index') }}" data-title="轮播管理"
                               href="javascript:void(0)">轮播管理</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="Hui-menu">
                <dt class="Hui-menu-title">案场/中介<i class="Hui-iconfont Hui-admin-menu-dropdown-arrow">&#xe6d5;</i></dt>
                <dd class="Hui-menu-item">
                    <ul>
                        <li><a data-href="" data-title="升级审核"
                               href="javascript:void(0)">升级审核</a></li>
                        <li><a data-href="" data-title="案场负责人管理"
                               href="javascript:void(0)">案场负责人管理</a></li>
                        <li><a data-href="" data-title="中介人员管理"
                               href="javascript:void(0)">中介人员管理</a></li>
                        <li><a data-href="" data-title="中介排名"
                               href="javascript:void(0)">中介排名</a></li>
                        <li><a data-href="" data-title="签到明细"
                               href="javascript:void(0)">签到明细</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="Hui-menu">
                <dt class="Hui-menu-title">楼盘管理<i class="Hui-iconfont Hui-admin-menu-dropdown-arrow">&#xe6d5;</i></dt>
                <dd class="Hui-menu-item">
                    <ul>
                        <li><a data-href="" data-title="楼盘管理"
                               href="javascript:void(0)">楼盘管理</a></li>
                        <li><a data-href="" data-title="楼盘联系人"
                               href="javascript:void(0)">楼盘联系人</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="Hui-menu">
                <dt class="Hui-menu-title">客户管理<i class="Hui-iconfont Hui-admin-menu-dropdown-arrow">&#xe6d5;</i></dt>
                <dd class="Hui-menu-item">
                    <ul>
                        <li><a data-href="" data-title="客户管理"
                               href="javascript:void(0)">客户管理</a></li>
                        <li><a data-href="" data-title="报备查询"
                               href="javascript:void(0)">报备查询</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="Hui-menu">
                <dt class="Hui-menu-title">积分兑换<i class="Hui-iconfont Hui-admin-menu-dropdown-arrow">&#xe6d5;</i></dt>
                <dd class="Hui-menu-item">
                    <ul>
                        <li><a data-href="" data-title="商品管理"
                               href="javascript:void(0)">商品管理</a></li>
                        <li><a data-href="" data-title="兑换订单"
                               href="javascript:void(0)">兑换订单</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="Hui-menu">
                <dt class="Hui-menu-title">
                    规则管理
                </dt>
            </dl>
            <dl class="Hui-menu">
                <dt class="Hui-menu-title">配置数据<i class="Hui-iconfont Hui-admin-menu-dropdown-arrow">&#xe6d5;</i></dt>
                <dd class="Hui-menu-item">
                    <ul>
                        <li><a data-href="" data-title="积分规则"
                               href="javascript:void(0)">积分规则</a></li>
                        <li><a data-href="" data-title="楼盘标签管理"
                               href="javascript:void(0)">楼盘标签管理</a></li>
                        <li><a data-href="" data-title="楼盘类型管理"
                               href="javascript:void(0)">楼盘类型管理</a></li>
                        <li><a data-href="" data-title="楼盘区域管理"
                               href="javascript:void(0)">楼盘区域管理</a></li>
                    </ul>
                </dd>
            </dl>

        </div>
    </aside>

    <div class="Hui-admin-aside-mask"></div>
    <!--/_menu 作为公共模版分离出去-->

    <div class="Hui-admin-dislpayArrow">
        <a href="javascript:void(0);" onClick="displaynavbar(this)">
            <i class="Hui-iconfont Hui-iconfont-left">&#xe6d4;</i>
            <i class="Hui-iconfont Hui-iconfont-right">&#xe6d7;</i>
        </a>
    </div>

    <section class="Hui-admin-article-wrapper">
        <!--_header 作为公共模版分离出去-->
        <header class="Hui-navbar">
            <div class="navbar">
                <div class="container-fluid clearfix">
                    <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar">
                        <ul class="clearfix">
                            <li>管理员</li>
                            {{--<li><img src="{{$partner->gzh_ewm}}?imageView2/1/w/50/h/50/interlace/1"--}}
                            {{--style="width: 28px;height: 28px;border-radius: 50%;"--}}
                            {{--class="ml-10"></li>--}}
                            <li class="dropDown dropDown_hover">
                                <a href="#" class="dropDown_A">
                                    <span class="c-primary">{{$admin->name}}</span>
                                    <span class="c-primary ml-5">{{$admin->role_str}}</span>
                                    <i class="Hui-iconfont">&#xe6d5;</i>
                                </a>
                                <ul class="dropDown-menu menu radius box-shadow">
                                    <li><a href="javascript:;" onClick="myself_edit()">个人信息</a></li>
                                    <li><a href="{{ URL::asset('/admin/loginout') }}">退出</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <!--/_header 作为公共模版分离出去-->

        <div id="Hui-admin-tabNav" class="Hui-admin-tabNav">
            <div class="Hui-admin-tabNav-wp">
                <ul id="min_title_list" class="acrossTab clearfix" style="width: 241px; left: 0px;">
                    <li class="active">
                        <span title="业务概览"
                              data-href="">业务概览</span>
                        <em></em>
                    </li>
                </ul>
            </div>
            <div class="Hui-admin-tabNav-more btn-group" style="display: none;">
                <a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i
                            class="Hui-iconfont">&#xe6d4;</i></a>
                <a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i
                            class="Hui-iconfont">&#xe6d7;</i></a>
            </div>
        </div>

        <div id="iframe_box" class="Hui-admin-article">
            <div class="show_iframe">
                <iframe id="iframe-welcome" data-scrolltop="0" scrolling="yes" frameborder="0"
                        src="{{ URL::asset('/admin/admin/index') }}"></iframe>
            </div>
        </div>

    </section>
    <div class="contextMenu" id="Huiadminmenu">
        <ul>
            <li id="closethis">关闭当前</li>
            <li id="closeall">关闭全部</li>
        </ul>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

        $(function () {

        });

        /*个人信息-修改*/
        function myself_edit(title, url) {
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }

    </script>
@endsection