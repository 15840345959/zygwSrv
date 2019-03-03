@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 户型样式管理 <span
                class="c-gray en">&gt;</span> 户型样式列表 <a class="btn btn-success radius r btn-refresh"
                                                        style="line-height:1.6em;margin-top:3px"
                                                        href="javascript:location.replace('{{URL::asset('/admin/house/huxingStyle/index')}}?huxing_id={{$huxing->id}}');"
                                                        title="刷新"
                                                        onclick="location.replace('{{URL::asset('/admin/house/huxingStyle/index')}}?huxing_id={{$huxing->id}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">

        <div class="text-c">
            <form id="search_form"
                  action="{{URL::asset('admin/house/huxingStyle/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:250px"
                           placeholder="根据名称模糊搜索" value="{{$con_arr['search_word']}}">
                    <input id="huxing_id" name="huxing_id" type="text" class="input-text hidden" style="width:250px"
                           placeholder="产品id" value="{{$con_arr['huxing_id']}}">
                    <input id="huxing_name" name="huxing_name" type="text" class="input-text" style="width:250px"
                           placeholder="产品名称" value="{{$huxing->name}}" disabled="">
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                </div>
            </form>
        </div>

        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;"
                    onclick="edit('添加户型样式','{{URL::asset('/admin/house/huxingStyle/edit')}}?huxing_id={{$huxing->id}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加户型样式
                 </a>
            </span>
            {{--<span class="r">共有数据：<strong>{{$datas->count()}}</strong> 条</span>--}}
        </div>

        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">户型样式列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="40">ID</th>
                    <th width="60">图片</th>
                    <th width="50">名称</th>
                    <th width="50">面积m²</th>
                    <th width="50">朝向</th>
                    <th width="50">录入人</th>
                    <th width="50">状态</th>
                    <th width="60">创建时间</th>
                    <th width="50">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$data->id}}</td>
                        <td>
                            <img src="{{$data->image}}" style="width: 80px;">
                        </td>
                        <td><span class="c-primary">{{$data->name}}</span></td>
                        <td>{{$data->size}}</td>
                        <td>{{$data->orientation}}</td>
                        <td>{{isset($data->admin)?$data->admin->name:'--'}}</td>
                        <td>
                            <span class="c-primary">{{$data->status_str}}</span>
                        </td>
                        <td>{{$data->created_at}}</td>
                        <td class="td-manage">
                            <div>
                                <a style="text-decoration:none" onClick="stop(this,'{{$data->id}}')"
                                   href="javascript:;" class="c-primary"
                                   title="停用">
                                    停用
                                </a>
                                <a style="text-decoration:none" onClick="start(this,'{{$data->id}}')"
                                   href="javascript:;" class="c-primary ml-5"
                                   title="启用">
                                    启用
                                </a>
                            </div>
                            <div class="mt-5">
                                <a title="编辑户型" href="javascript:;"
                                   onclick="edit('编辑户型样式','{{URL::asset('/admin/house/huxingStyle/edit')}}?id={{$data->id}}&huxing_id={{$huxing->id}}',{{$data->id}})"
                                   class="c-primary ml-5" style="text-decoration:none">
                                    编辑户型
                                </a>
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


        /*户型样式-编辑*/
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

        /*户型样式-隐藏*/
        function stop(obj, id) {
            console.log("stop id:" + id);
            layer.confirm('确认要隐藏吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 0,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置户型样式状态
                ajaxRequest('{{URL::asset('')}}' + "admin/house/huxingStyle/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已隐藏', {icon: 5, time: 1000});
            });
        }

        /*户型样式-显示*/
        function start(obj, id) {
            layer.confirm('确认要显示吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置户型样式状态
                ajaxRequest('{{URL::asset('')}}' + "admin/house/huxingStyle/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已显示', {icon: 6, time: 1000});
            });
        }

    </script>
@endsection