@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span>客户管理 <span
                class="c-gray en">&gt;</span>客户列表 <a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace(location.href);" title="刷新"
                                                       onclick="location.replace('{{URL::asset('admin/client/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form id="search_form" action="{{URL::asset('admin/client/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                           placeholder="客户姓名/手机号码" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                    <input id="user_id" name="user_id" type="text" class="input-text" style="width:150px"
                           placeholder="首次报备人id" value="{{$con_arr['user_id']?$con_arr['user_id']:''}}">
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                </div>
            </form>
        </div>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="r">共有数据：<strong>{{$datas->total()}}</strong> 条</span>
        </div>
        <table class="table table-border table-bordered table-bg table-sort mt-10">
            <thead>
            <tr>
                <th scope="col" colspan="100">内部管理员列表</th>
            </tr>
            <tr class="text-c">
                {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                <th width="40">ID</th>
                <th width="50">客户姓名</th>
                <th width="60">客户手机号</th>
                <th width="60">首次报备中介</th>
                <th width="50">报备次数</th>
                <th width="100">首次报备时间</th>
                <th width="100">备注</th>
                <th width="20">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    {{--<td><input type="checkbox" value="1" name=""></td>--}}
                    <td>{{$data->id}}</td>
                    <td><span class="c-primary">{{$data->name}}</span></td>
                    <td><span class="c-primary">{{$data->phonenum}}</span></td>
                    <td>
                        {{isset($data->user)?$data->user->nick_name:'--'}}
                        ({{isset($data->user)?$data->user->id:'--'}})
                    </td>
                    <td>{{$data->baobei_times?$data->baobei_times:'--'}}</td>
                    <td>{{$data->created_at?$data->created_at:'--'}}</td>
                    <td>{{$data->remark?$data->remark:'--'}}</td>
                    <td class="td-manage">

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-20">
            {{ $datas->appends($con_arr)->links() }}
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        

    </script>
@endsection