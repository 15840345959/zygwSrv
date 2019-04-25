@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 房产商客户管理 <span
                class="c-gray en">&gt;</span> 房产商客户列表 <a class="btn btn-success radius r btn-refresh"
                                                         style="line-height:1.6em;margin-top:3px"
                                                         href="javascript:location.replace('{{URL::asset('/admin/house/houseClient/index')}}');"
                                                         title="刷新"
                                                         onclick="location.replace('{{URL::asset('/admin/house/houseClient/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">

        <div class="text-c">
            <form id="search_form"
                  action="{{URL::asset('admin/house/houseClient/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r ml-5">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:450px"
                           placeholder="根据手机号模糊搜索" value="{{$con_arr['search_word']}}">
                    <input id="house_id" name="house_id" type="text" class="input-text" style="width:150px"
                           placeholder="楼盘id" value="{{$con_arr['house_id']}}">
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                </div>
            </form>
        </div>

        <div class="cl pd-5 bg-1 bk-gray mt-20">
            @if($house)
                <span class="l">
                     <a href="javascript:;"
                        onclick="edit('导入房产商客户','{{URL::asset('/admin/house/houseClient/edit')}}?house_id={{$house->id}}')"
                        class="btn btn-primary radius">
                         <i class="Hui-iconfont">&#xe600;</i> 导入房产商客户-{{$house->title}}
                     </a>
                </span>
            @endif
            {{--<span class="r">共有数据：<strong>{{$datas->count()}}</strong> 条</span>--}}
        </div>

        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">房产商客户列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="40">ID</th>
                    <th width="50">客户手机号</th>
                    <th width="50">楼盘名称</th>
                    <th width="100">导入人</th>
                    <th width="50">导入时间</th>
                    <th width="50">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$data->id}}</td>
                        <td><span class="c-primary">{{$data->phonenum}}</span></td>
                        <td><span class="c-primary">{{$data->house->title}}({{$data->house->id}})</span></td>
                        <td><span class="c-primary">{{isset($data->admin)?$data->admin->name:'--'}}
                                ({{isset($data->admin)?$data->admin->id:'--'}})</span></td>
                        <td>{{$data->created_at}}</td>
                        <td class="td-manage">
                            <a style="text-decoration:none" onClick="del(this,'{{$data->id}}')"
                               href="javascript:;" class="c-primary"
                               title="删除">
                                删除
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


        /*房产商客户-编辑*/
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

        /*房产商客户-隐藏*/
        function del(obj, id) {
            console.log("stop id:" + id);
            layer.confirm('确认要删除吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置房产商客户状态
                ajaxRequest('{{URL::asset('')}}' + "admin/house/houseClient/del/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已删除', {icon: 5, time: 1000});
            });
        }


    </script>
@endsection