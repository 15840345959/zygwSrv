@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 楼盘管理 <span
                class="c-gray en">&gt;</span> 楼盘列表 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace('{{URL::asset('/admin/house/house/index')}}');"
                                                      title="刷新"
                                                      onclick="location.replace('{{URL::asset('/admin/house/house/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">

        <div class="text-c">
            <form id="search_form" action="{{URL::asset('admin/house/house/index')}}?page={{$datas->currentPage()}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:250px"
                           placeholder="根据楼盘名称模糊搜索" value="{{$con_arr['search_word']}}">
                    <span class="select-box" style="width:150px">
                        <select class="select" name="status" id="status" size="1">
                            <option value="" {{$con_arr['status']==""?'selected':''}}>全部状态</option>
                            @foreach(\App\Components\Value::COMMON_STATUS_VAL as $key=>$value)
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

        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;" onclick="edit('新建楼盘','{{URL::asset('/admin/house/house/edit')}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加楼盘
                 </a>
            </span>
            {{--<span class="r">共有数据：<strong>{{$datas->count()}}</strong> 条</span>--}}
        </div>

        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">楼盘列表</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="40">楼盘名称</th>
                    <th width="100">基础信息</th>
                    <th width="50">状态</th>
                    <th width="50">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>
                            <div>
                                <img src="{{$data->image}}" style="width: 80px;">
                            </div>
                            <div class="mt-5">
                                <span class="c-primary">{{$data->title}} 编号：{{$data->id}}</span>
                            </div>
                            <div class="mt-5">
                                <span class="">地址：{{$data->address}}</span>
                            </div>
                            <div class="mt-5">
                                <span class="">开发商：{{$data->developer}}</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span>所在地区：</span><span
                                        class="ml-5 label label-secondary">{{isset($data->area)?$data->area->name:'--'}}</span>
                            </div>
                            <div class="mt-5">
                                <span>楼盘均价：</span><span class="ml-5">{{$data->price}}元</span>
                            </div>
                            <div class="mt-5">
                                <span>面积区间：</span><span class="ml-5">{{$data->size_min}}至{{$data->size_max}}m²</span>
                            </div>
                            <div class="mt-5">
                                <span>剩余套数：</span><span class="ml-5">{{$data->count}}套</span>
                            </div>
                            <div class="mt-5">
                                <span>标签：</span>
                                @foreach($data->labels as $label)
                                    <span class="ml-5">{{$label->name}}</span>
                                @endforeach
                            </div>
                            <div class="mt-5">
                                <span>类型：</span>
                                @foreach($data->types as $type)
                                    <span class="ml-5">{{$type->name}}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div>
                                @if($data->status=="1")
                                    <span class="label label-success radius">正常</span>
                                @else
                                    <span class="label label-default radius">冻结</span>
                                @endif
                            </div>
                            <div class="mt-5">
                                <span class="c-primary">{{isset($data->admin)?$data->admin->name:"--"}}</span>
                            </div>
                        </td>
                        <td>
                            <div>
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
                            </div>
                            <div class="mt-5">
                                <a title="编辑楼盘" href="javascript:;"
                                   onclick="edit('编辑楼盘-{{$data->title}}','{{URL::asset('/admin/house/house/edit')}}?id={{$data->id}}',{{$data->id}})"
                                   class="c-primary ml-5" style="text-decoration:none">
                                    编辑楼盘
                                </a>
                            </div>
                            <div class="mt-5">
                                <a title="房产商客户" href="javascript:;"
                                   onclick="client('房产商客户-{{$data->title}}','{{URL::asset('/admin/house/houseClient/index')}}?house_id={{$data->id}}',{{$data->id}})"
                                   class="c-primary ml-5" style="text-decoration:none">
                                    房产商客户
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


        /*
         参数解释：
         title	标题
         url		请求的url
         id		需要操作的数据id
         w		弹出层宽度（缺省调默认值）
         h		弹出层高度（缺省调默认值）
         */
        /*活动-增加*/
        function edit(title, url) {
            creatIframe(url, title)
        }

        /*
         参数解释：
         title	标题
         url		请求的url
         id		需要操作的数据id
         w		弹出层宽度（缺省调默认值）
         h		弹出层高度（缺省调默认值）
         */
        /*活动-增加*/
        function client(title, url) {
            creatIframe(url, title)
        }

        /*楼盘-隐藏*/
        function stop(obj, id) {
            console.log("stop id:" + id);
            layer.confirm('确认要隐藏吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 0,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置楼盘状态
                ajaxRequest('{{URL::asset('')}}' + "admin/house/house/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已隐藏', {icon: 5, time: 1000});
            });
        }

        /*楼盘-显示*/
        function start(obj, id) {
            layer.confirm('确认要显示吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置楼盘状态
                ajaxRequest('{{URL::asset('')}}' + "admin/house/house/setStatus/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                    }
                });
                layer.msg('已显示', {icon: 6, time: 1000});
            });
        }

    </script>
@endsection