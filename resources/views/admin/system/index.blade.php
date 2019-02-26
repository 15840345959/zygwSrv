@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 积分规则 <span
                class="c-gray en">&gt;</span> 积分规则 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace('{{URL::asset('/admin/system/index')}}');"
                                                      title="刷新"
                                                      onclick="location.replace('{{URL::asset('/admin/system/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">

        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">当前配置</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="100">签到积分</th>
                    <th width="100">推荐积分</th>
                    <th width="100">到访积分</th>
                    <th width="100"> 成交积分</th>
                    <th width="50">操作</th>
                </tr>
                </thead>
                <tbody>
                <tr class="text-c">
                    {{--<td><input type="checkbox" value="1" name=""></td>--}}
                    <td>{{$data->qd_jifen}}</td>
                    <td>{{$data->tj_jifen}}</td>
                    <td>{{$data->df_jifen}}</td>
                    <td>{{$data->cj_jifen}}</td>
                    <td>
                        <a title="编辑" href="javascript:;"
                           onclick="edit('编辑积分规则','{{URL::asset('/admin/system/edit')}}?id={{$data->id}})',{{$data->id}})"
                           class="c-primary ml-5" style="text-decoration:none">
                            编辑
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>


        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">积分修改记录</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="20">ID</th>
                    <th width="60">配置时间</th>
                    <th width="50">配置人</th>
                    <th width="250">配置操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($systemRecords as $systemRecord)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$systemRecord->id}}</td>
                        <td>{{$systemRecord->created_at}}</td>
                        <td>{{$systemRecord->admin->name}}</td>
                        <td>{{$systemRecord->desc}}</td>
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