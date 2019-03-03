@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 申请案场管理 <span
                class="c-gray en">&gt;</span> 申请案场列表 <a class="btn btn-success radius r btn-refresh"
                                                        style="line-height:1.6em;margin-top:3px"
                                                        href="javascript:location.replace('{{URL::asset('/admin/user/userUp/index')}}');"
                                                        title="刷新"
                                                        onclick="location.replace('{{URL::asset('/admin/user/userUp/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form id="search_form" action="{{URL::asset('/admin/user/userUp/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <span class="ml-5">用户id：</span>
                    <input id="user_id" name="user_id" type="text" class="input-text"
                           style="width:100px"
                           placeholder="用户id" value="{{$con_arr['user_id']}}">
                    <span class="select-box" style="width:150px">
                        <select class="select" name="status" id="status" size="1">
                            <option value="" {{$con_arr['status']==""?'selected':''}}>全部状态</option>
                            @foreach(\App\Components\Value::USER_UP_STATUS_VAL as $key=>$value)
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

        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">申请案场列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="20">ID</th>
                    <th width="40">头像</th>
                    <th width="60">昵称</th>
                    <th width="50">姓名</th>
                    <th width="60">电话</th>
                    <th width="100">楼盘</th>
                    <th width="50">申请时间</th>
                    <th width="30">状态</th>
                    <th width="30">管理员</th>
                    <th width="40">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$data->id}}</td>
                        <td>
                            <img src="{{ $data->user->avatar ? $data->user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                 class="img-rect-30 radius-5">
                        </td>
                        <td>{{$data->user->nick_name?$data->user->nick_name:'--'}}</td>
                        <td>{{$data->user->real_name?$data->user->real_name:'--'}}</td>
                        <td>{{$data->user->phonenum?$data->user->phonenum:'--'}}</td>
                        <td>{{isset($data->house->title)?$data->house->title:'--'}}
                            ({{isset($data->house->id)?$data->house->id:'--'}})
                        </td>
                        <td>{{$data->created_at}}</td>
                        <td>
                            <span class="c-primary">{{$data->status_str}}</span>
                        </td>
                        <td>{{isset($data->admin)?$data->admin->name:'--'}}</td>
                        <td class="td-manage">
                            <a style="text-decoration:none" onClick="start(this,'{{$data->id}}')"
                               href="javascript:;" class="c-primary"
                               title="审核通过">
                                通过
                            </a>
                            <a style="text-decoration:none" onClick="stop(this,'{{$data->id}}')"
                               href="javascript:;" class="c-primary ml-5"
                               title="审核驳回">
                                驳回
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


        /*申请案场-隐藏*/
        function stop(obj, id) {
            console.log("stop id:" + id);
            layer.confirm('确认要审核驳回吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 2,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置申请案场状态
                ajaxRequest('{{URL::asset('')}}' + "admin/user/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已驳回', {icon: 5, time: 1000});
            });
        }

        /*申请案场-显示*/
        function start(obj, id) {
            layer.confirm('确认要审核通过吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置申请案场状态
                ajaxRequest('{{URL::asset('')}}' + "admin/user/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已通过', {icon: 6, time: 1000});
            });
        }

    </script>
@endsection