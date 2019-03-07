@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 报备单管理 <span
                class="c-gray en">&gt;</span> 报备单列表 <a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace('{{URL::asset('/admin/baobei/baobei/index')}}');"
                                                       title="刷新"
                                                       onclick="location.replace('{{URL::asset('/admin/baobei/baobei/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">

        <div class="text-c">
            <form id="search_form"
                  action="{{URL::asset('admin/baobei/baobei/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                           placeholder="根据报备单号模糊查询" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                    <span class="ml-5">报备状态：</span>
                    <span class="select-box" style="width:150px">
                        <select class="select" name="baobei_status" id="baobei_status" size="1">
                            <option value="" {{$con_arr['baobei_status']==""?'selected':''}}>全部状态</option>
                            @foreach(\App\Components\Value::BAOBEI_STATUS_VAL as $key=>$value)
                                <option value="{{$key}}" {{$con_arr['baobei_status']==strval($key)?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="Huiform text-r mt-10">
                    <span class="ml-5">是否可结算：</span>
                    <span class="select-box" style="width:150px">
                        <select class="select" name="can_jiesuan_status" id="can_jiesuan_status" size="1">
                            <option value="" {{$con_arr['can_jiesuan_status']==""?'selected':''}}>全部状态</option>
                            @foreach(\App\Components\Value::BAOBEI_CAN_JIESUAN_STATUS_VAL as $key=>$value)
                                <option value="{{$key}}" {{$con_arr['can_jiesuan_status']==strval($key)?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </span>
                    <span class="ml-5">付款状态：</span>
                    <span class="select-box" style="width:150px">
                        <select class="select" name="pay_zhongjie_status" id="pay_zhongjie_status" size="1">
                            <option value="" {{$con_arr['pay_zhongjie_status']==""?'selected':''}}>全部状态</option>
                            @foreach(\App\Components\Value::BAOBEI_PAY_ZHONGJIE_STATUS_VAL as $key=>$value)
                                <option value="{{$key}}" {{$con_arr['pay_zhongjie_status']==strval($key)?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </span>
                    <span class="ml-5">有效状态：</span>
                    <span class="select-box" style="width:150px">
                        <select class="select" name="status" id="status" size="1">
                            <option value="" {{$con_arr['status']==""?'selected':''}}>全部状态</option>
                            @foreach(\App\Components\Value::COMMON_STATUS_VAL as $key=>$value)
                                <option value="{{$key}}" {{$con_arr['status']==strval($key)?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </span>
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                </div>
            </form>
        </div>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="r">共有数据：<strong>{{$datas->total()}}</strong> 条</span>
        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">报备单列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="20">ID</th>
                    <th width="80">流水</th>
                    <th width="40">客户</th>
                    <th width="50">电话</th>
                    <th width="80">意向楼盘</th>
                    <th width="30">分润佣金</th>
                    <th width="30">是否有效</th>
                    <th width="30">报备状态</th>
                    <th width="30">是否可结算</th>
                    <th width="30">付款状态</th>
                    <th width="50">报备时间</th>
                    <th width="30">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$data->id}}</td>
                        <td>{{$data->trade_no}}</td>
                        <td>
                            <span class="c-primary">{{$data->client->name}}({{$data->client->id}})</span>
                        </td>
                        <td>
                            <span class="c-primary">{{$data->client->phonenum}}</span>
                        </td>
                        <td>{{$data->house->title}}</td>
                        <td>{{$data->yongjin}}</td>
                        <td>
                            <span class="c-primary">{{$data->status_str}}</span>
                        </td>
                        <td>
                            <span class="c-primary">{{$data->baobei_status_str}}</span>
                        </td>
                        <td>
                            <span class="c-primary">{{$data->can_jiesuan_status_str}}</span>
                        </td>
                        <td>
                            <span class="c-primary">{{$data->pay_zhongjie_status_str}}</span>
                        </td>
                        <td>{{$data->created_at}}</td>
                        <td class="td-manage">
                            <a title="处理报备单" href="javascript:;"
                               onclick="edit('处理报备单-{{$data->trade_no}}','{{URL::asset('/admin/baobei/baobei/info')}}?id={{$data->id}}',{{$data->id}})"
                               class="c-primary ml-5" style="text-decoration:none">
                                处理
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-20">
                {{ $datas->appends($con_arr)->links() }}
            </div>
        </div>
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