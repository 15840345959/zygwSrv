@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 推荐管理 <span
                class="c-gray en">&gt;</span> 推荐列表 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace('{{URL::asset('/admin/user/recommInfo/index')}}');"
                                                      title="刷新"
                                                      onclick="location.replace('{{URL::asset('/admin/user/recommInfo/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form id="search_form"
                  action="{{URL::asset('/admin/user/recommInfo/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <span class="ml-5">用户id：</span>
                    <input id="user_id" name="user_id" type="text" class="input-text"
                           style="width:100px"
                           placeholder="用户id" value="{{$con_arr['user_id']}}">
                    <span class="ml-5">推荐用户id：</span>
                    <input id="re_user_id" name="re_user_id" type="text" class="input-text"
                           style="width:100px"
                           placeholder="推荐用户id" value="{{$con_arr['re_user_id']}}">

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
                    <th scope="col" colspan="100">推荐列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="20">ID</th>
                    <th width="40">被推荐人头像</th>
                    <th width="60">被推荐人姓名</th>
                    <th width="40">推荐人头像</th>
                    <th width="60">推荐人姓名</th>
                    <th width="50">推荐时间</th>
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
                        <td>{{$data->user->nick_name?$data->user->nick_name:'--'}}({{$data->user->id}})</td>
                        <td>
                            <img src="{{ $data->re_user->avatar ? $data->re_user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                 class="img-rect-30 radius-5">
                        </td>
                        <td>{{$data->re_user->nick_name?$data->re_user->nick_name:'--'}}({{$data->re_user->id}})</td>
                        <td>{{$data->created_at}}</td>
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