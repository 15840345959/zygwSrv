@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 广告管理 <span
                class="c-gray en">&gt;</span> 广告列表 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace('{{URL::asset('/admin/ad/index')}}');"
                                                      title="刷新"
                                                      onclick="location.replace('{{URL::asset('/admin/ad/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form id="search_form" action="{{URL::asset('/admin/ad/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:250px"
                           placeholder="根据广告标题搜索，支持模糊查询" value="{{$con_arr['search_word']}}">
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                </div>
            </form>
        </div>

        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;" onclick="edit('添加广告','{{URL::asset('/admin/ad/edit')}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加广告
                 </a>
            </span>
            {{--<span class="r">共有数据：<strong>{{$datas->count()}}</strong> 条</span>--}}
        </div>

        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">广告列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="40">ID</th>
                    <th width="100">图片</th>
                    <th width="150">标题</th>
                    <th width="50">顺序</th>
                    <th width="100">创建时间</th>
                    <th width="60">跳转类型</th>
                    <th width="60">状态</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$data->id}}</td>
                        <td><img src="{{ $data->img.'?imageView2/1/w/100/h/60/interlace/1/q/75|imageslim'}}"/></td>
                        <td>{{$data->title}}</td>
                        {{--<td>{{$data->position}}</td>--}}
                        <td>
                            {{$data->seq}}
                        </td>
                        <td>{{$data->created_at}}</td>
                        <td>{{$data->type_str}}</td>
                        <td class="td-status">
                            @if($data->status=="1")
                                <span class="label label-success radius">显示</span>
                            @else
                                <span class="label label-default radius">隐藏</span>
                            @endif
                        </td>
                        <td class="td-manage">
                            @if($data->status=="1")
                                <a style="text-decoration:none" onClick="stop(this,'{{$data->id}}')"
                                   href="javascript:;" class="c-primary"
                                   title="停用">
                                    停用
                                </a>
                            @else
                                <a style="text-decoration:none" onClick="start(this,'{{$data->id}}')"
                                   href="javascript:;" class="c-primary"
                                   title="启用">
                                    启用
                                </a>
                            @endif
                            <a title="编辑" href="javascript:;"
                               onclick="edit('编辑广告图','{{URL::asset('/admin/ad/edit')}}?id={{$data->id}})',{{$data->id}})"
                               class="c-primary ml-5" style="text-decoration:none">
                                编辑
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


        /*广告-编辑*/
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

        /*广告-隐藏*/
        function stop(obj, id) {
            console.log("stop id:" + id);
            layer.confirm('确认要隐藏吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 0,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置广告状态
                ajaxRequest('{{URL::asset('')}}' + "admin/ad/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已隐藏', {icon: 5, time: 1000});
            });
        }

        /*广告-显示*/
        function start(obj, id) {
            layer.confirm('确认要显示吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置广告状态
                ajaxRequest('{{URL::asset('')}}' + "admin/ad/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已显示', {icon: 6, time: 1000});
            });
        }

    </script>
@endsection