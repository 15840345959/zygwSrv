@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 兑换订单管理 <span
                class="c-gray en">&gt;</span> 兑换订单列表 <a class="btn btn-success radius r btn-refresh"
                                                        style="line-height:1.6em;margin-top:3px"
                                                        href="javascript:location.replace('{{URL::asset('/admin/jifen/goodsExchange/index')}}');"
                                                        title="刷新"
                                                        onclick="location.replace('{{URL::asset('/admin/jifen/goodsExchange/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form id="search_form"
                  action="{{URL::asset('/admin/jifen/goodsExchange/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="user_id" name="user_id" type="text" class="input-text" style="width:150px"
                           placeholder="用户id" value="{{$con_arr['user_id']}}">
                    <input id="goods_id" name="goods_id" type="text" class="input-text" style="width:150px"
                           placeholder="商品id" value="{{$con_arr['goods_id']}}">
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">兑换订单列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="40">ID</th>
                    <th width="100">图片</th>
                    <th width="50">商品</th>
                    <th width="50">用户</th>
                    <th width="50">电话</th>
                    <th width="50">积分</th>
                    <th width="50">兑换时间</th>
                    <th width="50">兑付时间</th>
                    <th width="50">审核人</th>
                    <th width="50">兑付状态</th>
                    <th width="40">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$data->id}}</td>
                        <td>
                            <img src="{{ isset($data->goods)?$data->goods->image.'?imageView2/1/w/60/interlace/1/q/75|imageslim':Url::asset('/img/upload.png')}}"
                                 style="width: 60px;"/>
                        </td>
                        <td><span class="c-primary">{{isset($data->goods)?$data->goods->name:'--'}}
                                ({{$data->goods->id}})</span></td>
                        <td><span class="c-primary">{{isset($data->user->nick_name)?$data->user->nick_name:'--'}}
                                ({{$data->user->id}})</span>
                        </td>
                        <td>{{isset($data->user->phonenum)?$data->user->phonenum:'--'}}</td>
                        <td>{{$data->total_jifen}}</td>
                        <td>{{isset($data->created_at)?$data->created_at:'--'}}</td>
                        <td>{{isset($data->dh_time)?$data->dh_time:'--'}}</td>
                        <td>{{isset($data->admin)?$data->admin->name:'--'}}</td>
                        <td>
                            <span class="c-primary">{{$data->status_str}}</span>
                        </td>
                        <td class="td-manage">
                            <div>
                                @if($data->status=='0')
                                    <a style="text-decoration:none" onClick="exchange(this,'{{$data->id}}')"
                                       href="javascript:;" class="c-primary"
                                       title="确认兑付">
                                        确认兑付
                                    </a>
                                @else
                                    <a style="text-decoration:none"
                                       href="javascript:;" class="c-primary"
                                       title="已经兑付">
                                        已经兑付
                                    </a>
                                @endif
                            </div>
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


        /*兑换订单-隐藏*/
        function exchange(obj, id) {
            console.log("stop id:" + id);
            layer.confirm('确认要进行兑付吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置兑换订单状态
                ajaxRequest('{{URL::asset('')}}' + "admin/jifen/goodsExchange/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已兑付', {icon: 6, time: 1000});
            });
        }

    </script>
@endsection