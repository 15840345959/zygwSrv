@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 报备单管理 <span
                class="c-gray en">&gt;</span> 报备单详情 <a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace('{{URL::asset('/admin/baobei/baobei/info')}}?id={{$data->id}}');"
                                                       title="刷新"
                                                       onclick="location.replace('{{URL::asset('/admin/baobei/baobei/info')}}?id={{$data->id}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">

        {{--报备单基础信息--}}
        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">报备单详情</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><strong>ID</strong></td>
                    <td>{{$data->id}}</td>
                    <td><strong>报备流水</strong></td>
                    <td>{{$data->trade_no}}</td>
                    <td><strong>客户姓名</strong></td>
                    <td><span class="c-primary">{{$data->client->name}}({{$data->client->id}})</span></td>
                    <td><strong>客户电话</strong></td>
                    <td><span class="c-primary">{{$data->client->phonenum}}</span></td>
                    <td><strong>报备时间</strong></td>
                    <td>{{$data->created_at}}</td>
                </tr>
                <tr>
                    <td><strong>意向楼盘</strong></td>
                    <td>{{$data->house->title}}（{{$data->house->id}}）</td>
                    <td><strong>楼盘顾问</strong></td>
                    <td>{{isset($data->guwen)?$data->guwen->name:'--'}}</td>
                    <td><strong>客户所在区域</strong></td>
                    <td>{{isset($data->area)?$data->area->name:'--'}}</td>
                    <td><strong>客户住址</strong></td>
                    <td>{{isset($data->address)?$data->address:'--'}}</td>
                    <td><strong>意向面积</strong></td>
                    <td>{{isset($data->size)?$data->size:'0'}}㎡</td>
                </tr>
                <tr>
                    <td><strong>关注要点</strong></td>
                    <td>{{isset($data->care)?$data->care->name:'--'}}</td>
                    <td><strong>认知途径</strong></td>
                    <td>{{isset($data->way)?$data->way->name:'--'}}</td>
                    <td><strong>购买目的</strong></td>
                    <td>{{isset($data->purpose)?$data->purpose->name:'--'}}</td>
                    <td><strong>购买意向</strong></td>
                    <td>{{isset($data->intention_status_str)?$data->intention_status_str:'--'}}</td>
                    <td><strong>报备进度</strong></td>
                    <td>{{isset($data->baobei_status_str)?$data->baobei_status_str:'--'}}</td>
                </tr>
                <tr>
                    <td><strong>是否可结算</strong></td>
                    <td>{{isset($data->can_jiesuan_status_str)?$data->can_jiesuan_status_str:'--'}}</td>
                    <td><strong>是否已结算</strong></td>
                    <td>{{isset($data->pay_zhongjie_status_str)?$data->pay_zhongjie_status_str:'--'}}</td>
                    <td><strong>报备状态</strong></td>
                    <td><span class="c-primary">{{$data->status_str}}</span></td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                </tr>
                <tr>
                    <td scope="col" colspan="100">{{isset($data->remark)?$data->remark:'--'}}</td>
                </tr>
                </tbody>
            </table>
        </div>

        {{--中介信息--}}
        @if(isset($data->user))
            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                    <tr>
                        <th scope="col" colspan="100">中介信息</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>ID</strong></td>
                        <td>{{$data->user->id}}</td>
                        <td><strong>头像</strong></td>
                        <td>
                            <img src="{{ $data->user->avatar ? $data->user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : Url::asset('/img/default_headicon.png')}}"
                                 class="img-rect-30 radius-5">
                        </td>
                        <td><strong>微信昵称</strong></td>
                        <td>{{$data->user->nick_name}}</td>
                        <td><strong>姓名</strong></td>
                        <td><span class="c-primary">{{$data->user->real_name}}({{$data->user->real_name}})</span></td>
                        <td><strong>电话</strong></td>
                        <td><span class="c-primary">{{$data->user->phonenum}}</span></td>
                    </tr>
                    <tr>
                        <td><strong>身份证</strong></td>
                        <td>{{$data->user->cardID}}</td>
                        <td><strong>性别</strong></td>
                        <td>{{isset($data->user->gender_str)?$data->user->gender_str:'--'}}</td>
                        <td><strong>省份</strong></td>
                        <td>{{isset($data->province)?$data->province:'--'}}</td>
                        <td><strong>城市</strong></td>
                        <td>{{isset($data->city)?$data->city:'--'}}</td>
                        <td><strong>积分</strong></td>
                        <td>{{isset($data->jifen)?$data->jifen:'0'}}</td>
                    </tr>
                    <tr>
                        <td><strong>注册时间</strong></td>
                        <td>{{isset($data->user->created_at)?$data->user->created_at:'--'}}</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

        {{--案场负责人信息--}}
        @if(isset($data->anchang))
            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                    <tr>
                        <th scope="col" colspan="100">案场负责人信息</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>ID</strong></td>
                        <td>{{$data->anchang->id}}</td>
                        <td><strong>头像</strong></td>
                        <td>
                            <img src="{{ $data->anchang->avatar ? $data->anchang->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : Url::asset('/img/default_headicon.png')}}"
                                 class="img-rect-30 radius-5">
                        </td>
                        <td><strong>微信昵称</strong></td>
                        <td>{{$data->anchang->nick_name}}</td>
                        <td><strong>姓名</strong></td>
                        <td><span class="c-primary">{{$data->anchang->real_name}}({{$data->anchang->real_name}})</span>
                        </td>
                        <td><strong>电话</strong></td>
                        <td><span class="c-primary">{{$data->anchang->phonenum}}</span></td>
                    </tr>
                    <tr>
                        <td><strong>身份证</strong></td>
                        <td>{{$data->anchang->cardID}}</td>
                        <td><strong>性别</strong></td>
                        <td>{{isset($data->anchang->gender_str)?$data->anchang->gender_str:'--'}}</td>
                        <td><strong>省份</strong></td>
                        <td>{{isset($data->province)?$data->province:'--'}}</td>
                        <td><strong>城市</strong></td>
                        <td>{{isset($data->city)?$data->city:'--'}}</td>
                        <td><strong>积分</strong></td>
                        <td>{{isset($data->jifen)?$data->jifen:'0'}}</td>
                    </tr>
                    <tr>
                        <td><strong>注册时间</strong></td>
                        <td>{{isset($data->anchang->created_at)?$data->anchang->created_at:'--'}}</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

        {{--报备单详情--}}
        <div class="mt-20">
            <div>
                <h2>报备单进度（{{$data->baobei_status_str}}）</h2>
            </div>
            <div class="mt-10">
                <div class="progress">
                    <div class="progress-bar">
                        <span class="sr-only"
                              style="width:{{(double)(($data->baobei_status+1)/5)*100}}%"></span>
                    </div>
                </div>
            </div>
        </div>
        @if($data->baobei_status>=0)
            <div class="mt-20">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h4>报备客户 {{$data->created_at}}</h4>
                    </div>
                    <div class="panel-body">
                        <div class="mt-10">
                            中介：
                            <span class="c-primary">{{$data->user->real_name}}/{{$data->user->nick_name}}
                                ({{$data->user->id}})</span>
                            <span class="c-primary ml-5">{{$data->user->phonenum}}</span>
                        </div>
                        <div class="margin-top-20">
                            客户：<span class="c-primary">{{$data->client->name}}({{$data->client->id}})</span>
                            <span class="c-primary ml-5">{{$data->client->phonenum}}</span>
                        </div>
                        <div class="margin-top-20">
                            楼盘：<span class="c-primary">{{$data->house->title}}</span>
                        </div>
                        <div class="margin-top-20">
                            计划到访时间：<span class="c-primary">{{$data->plan_visit_time}}
                                /{{$data->visit_way_str}}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($data->baobei_status>=1)
            <div class="mt-20">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h4>客户到访 {{$data->visit_time}}</h4>
                    </div>
                    <div class="panel-body">
                        <div class="mt-10">
                            案场负责人：
                            @if(isset($data->anchang))
                                <span class="c-primary">{{$data->anchang->real_name}}/{{$data->anchang->nick_name}}
                                    ({{$data->anchang->id}})</span>
                                <span class="c-primary ml-5">{{$data->anchang->phonenum}}</span>
                            @else
                                暂无案场负责人接收
                            @endif
                        </div>
                        <div class="margin-top-20">
                            <img src="{{$data->visit_attach}}" style="width: 300px;">
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($data->baobei_status>=2)
            <div class="mt-20">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h4>报备成交 {{$data->deal_time}}</h4>
                    </div>
                    <div class="panel-body">
                        <div class="mt-10">
                            成交面积：
                            <span class="c-primary">{{$data->deal_size}}m²</span>
                        </div>
                        <div class="mt-10">
                            成交金额：
                            <span class="c-primary">{{$data->deal_price}}元</span>
                        </div>
                        <div class="mt-10">
                            成交户型：
                            <span class="c-primary">{{$data->deal_huxing->name}}元</span>
                            <span class="c-primary ml-5">
                                {{$data->deal_huxing->yongjin_type_str}}-{{$data->deal_huxing->yongjin_value_str}}
                            </span>
                        </div>
                        <div class="mt-10">
                            成交房号：
                            <span class="c-primary">{{$data->deal_room}}</span>
                        </div>
                        <div class="mt-10">
                            付款方式：
                            <span class="c-primary">{{$data->pay_way->name}}</span>
                        </div>
                        <div class="mt-10">
                            产生佣金：
                            <span class="c-primary">{{$data->yongjin}}元</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($data->baobei_status>=3)
            <div class="mt-20">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h4>客户签约 {{$data->sign_time}}</h4>
                    </div>
                    <div class="panel-body">
                        <div class="mt-10">
                            暂无其他信息
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($data->baobei_status>=4)
            <div class="mt-20">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h4>全款到账 {{$data->qkdz_time}}</h4>
                    </div>
                    <div class="panel-body">
                        <div class="mt-10">
                            暂无其他信息
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($data->baobei_status>=2&&$data->pay_zhongjie_status=='1')
            <div class="mt-20">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h4>向中介结算 {{$data->pay_zhongjie_time}}</h4>
                    </div>
                    <div class="panel-body">
                        <div class="mt-10">
                            结算管理员：
                            <span class="c-primary">{{$data->admin->name}}</span>
                        </div>
                        <div class="mt-10">
                            <img src="{{$data->pay_zhongjie_attach}}" style="width: 300px;">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script type="text/javascript">


        $(function () {

        });


        /*处理-报备单*/
        function info(title, url) {
            creatIframe(url, title)
        }

    </script>
@endsection