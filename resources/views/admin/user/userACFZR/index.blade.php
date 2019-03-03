@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 案场负责人管理 <span
                class="c-gray en">&gt;</span> 案场负责人列表 <a class="btn btn-success radius r btn-refresh"
                                                         style="line-height:1.6em;margin-top:3px"
                                                         href="javascript:location.replace('{{URL::asset('/admin/user/userACFZR/index')}}');"
                                                         title="刷新"
                                                         onclick="location.replace('{{URL::asset('/admin/user/userACFZR/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form id="search_form" action="{{URL::asset('/admin/user/userACFZR/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:450px"
                           placeholder="根据姓名、手机号模糊搜索" value="{{$con_arr['search_word']}}">
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
                    <th scope="col" colspan="100">案场负责人列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="20">ID</th>
                    <th width="40">头像</th>
                    <th width="60">昵称</th>
                    <th width="50">姓名</th>
                    <th width="100">电话</th>
                    <th width="30">积分</th>
                    <th width="30">角色</th>
                    <th width="100">注册时间</th>
                    <th width="30">状态</th>
                    <th width="40">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$data->id}}</td>
                        <td>
                            <img src="{{ $data->avatar ? $data->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                 class="img-rect-30 radius-5">
                        </td>
                        <td>{{$data->nick_name?$data->nick_name:'--'}}</td>
                        <td>{{$data->real_name?$data->real_name:'--'}}</td>
                        <td>{{$data->phonenum?$data->phonenum:'--'}}</td>
                        <td>{{$data->jifen}}</td>
                        <td>{{$data->role_str}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>
                            <span class="c-primary">{{$data->status_str}}</span>
                        </td>
                        <td class="td-manage">
                            <a style="text-decoration:none" onClick="start(this,'{{$data->id}}')"
                               href="javascript:;" class="c-primary"
                               title="启用">
                                启用
                            </a>
                            <a style="text-decoration:none" onClick="stop(this,'{{$data->id}}')"
                               href="javascript:;" class="c-primary ml-5"
                               title="停用">
                                停用
                            </a>
                            <a style="text-decoration:none" onClick="down(this,'{{$data->id}}')"
                               href="javascript:;" class="c-primary ml-5"
                               title="降级">
                                降级
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


        /*案场负责人-编辑*/
        function edit(title, url, id) {
            console.log("edit url:" + url);
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            console.log(index);
            layer.full(index);
        }

        /*案场负责人-隐藏*/
        function stop(obj, id) {
            console.log("stop id:" + id);
            layer.confirm('确认要隐藏吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 0,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置案场负责人状态
                ajaxRequest('{{URL::asset('')}}' + "admin/user/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已隐藏', {icon: 5, time: 1000});
            });
        }

        /*案场负责人-显示*/
        function start(obj, id) {
            layer.confirm('确认要显示吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置案场负责人状态
                ajaxRequest('{{URL::asset('')}}' + "admin/user/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已显示', {icon: 6, time: 1000});
            });
        }

        //降级-down
        function down(obj, id) {
            layer.confirm('确认要降级吗？降级后该案场负责人的申请状态将全部设置为驳回状态', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    role: 0,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置案场负责人状态
                ajaxRequest('{{URL::asset('')}}' + "admin/user/setRole/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已降级', {icon: 5, time: 1000});
            });
        }

    </script>
@endsection