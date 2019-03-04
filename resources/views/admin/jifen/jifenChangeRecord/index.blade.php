@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 积分变更记录管理 <span
                class="c-gray en">&gt;</span> 积分变更记录列表 <a class="btn btn-success radius r btn-refresh"
                                                          style="line-height:1.6em;margin-top:3px"
                                                          href="javascript:location.replace('{{URL::asset('/admin/jifen/jifenChangeRecord/index')}}');"
                                                          title="刷新"
                                                          onclick="location.replace('{{URL::asset('/admin/jifen/jifenChangeRecord/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form id="search_form"
                  action="{{URL::asset('/admin/jifen/jifenChangeRecord/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="user_id" name="user_id" type="text" class="input-text" style="width:150px"
                           placeholder="用户id" value="{{$con_arr['user_id']}}">
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
                    <th scope="col" colspan="100">积分变更记录列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="40">ID</th>
                    <th width="50">头像</th>
                    <th width="50">昵称</th>
                    <th width="50">姓名</th>
                    <th width="50">电话</th>
                    <th width="50">变更积分值</th>
                    <th width="50">备注</th>
                    <th width="50">变更时间</th>
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
                        <td><span class="c-primary">{{isset($data->user->nick_name)?$data->user->nick_name:'--'}}
                                （{{isset($data->user->id)?$data->user->id:'--'}}）</span>
                        </td>
                        <td><span class="c-primary">{{isset($data->user->real_name)?$data->user->real_name:'--'}}</span>
                        </td>
                        <td>{{isset($data->user->phonenum)?$data->user->phonenum:'--'}}</td>
                        <td>{{$data->jifen}}</td>
                        <td>{{$data->record}}</td>
                        <td>{{isset($data->created_at)?$data->created_at:'--'}}</td>
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


    </script>
@endsection