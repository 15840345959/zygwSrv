@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 白皮书管理 <span
                class="c-gray en">&gt;</span> 白皮书管理 <a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace('{{URL::asset('/admin/tw/index')}}');"
                                                       title="刷新"
                                                       onclick="location.replace('{{URL::asset('/admin/tw/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">白皮书管理</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="20">id</th>
                    <th width="120">标题</th>
                    <th width="60">类型</th>
                    <th width="60">创建时间</th>
                    <th width="40">操作内容</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$data->id}}</td>
                        <td><span class="c-primary">{{$data->title}}</span></td>
                        <td>{{$data->type_str}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>
                            <a title="编辑" href="javascript:;"
                               onclick="edit('编辑','{{URL::asset('/admin/tw/edit')}}?id={{$data->id}}',{{$data->id}})"
                               class="c-primary ml-5" style="text-decoration:none">
                                编辑
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('script')
    <script type="text/javascript">


        $(function () {

        });


        /*编辑*/
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


    </script>
@endsection