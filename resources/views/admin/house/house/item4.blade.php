<div class="page-container">

    <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;"
                    onclick="edit('添加置业顾问','{{URL::asset('/admin/house/zygw/edit')}}?house_id={{$data->id}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加置业顾问
                 </a>
            </span>
        {{--<span class="r">共有数据：<strong>{{$datas->count()}}</strong> 条</span>--}}
    </div>


    <div class="mt-20">
        <table class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr>
                <th scope="col" colspan="100">置业顾问列表</th>
            </tr>
            <tr class="text-c">
                {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                <th width="20">ID</th>
                <th width="60">顾问</th>
                <th width="60">楼盘</th>
                <th width="60">电话</th>
                <th width="60">创建时间</th>
                <th width="60">管理员</th>
                <th width="60">状态</th>
                <th width="50">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($zygws as $zygw)
                <tr class="text-c">
                    {{--<td><input type="checkbox" value="1" name=""></td>--}}
                    <td>{{$zygw->id}}</td>
                    <td><span class="c-primary">{{$zygw->name?$zygw->name:'--'}}</span></td>
                    <td>{{isset($zygw->house->title)?$zygw->house->title:'--'}}</td>
                    <td><span class="c-primary">{{isset($zygw->phonenum)?$zygw->phonenum:'--'}}</span></td>
                    <td>{{$zygw->created_at}}</td>
                    <td>{{isset($zygw->admin)?$zygw->admin->name:'--'}}</td>
                    <td>
                        <span class="c-primary">{{$zygw->status_str}}</span>
                    </td>
                    <td>
                        <div>
                            <a style="text-decoration:none" onClick="start(this,'{{$zygw->id}}')"
                               href="javascript:;" class="c-primary"
                               title="启用">
                                启用
                            </a>

                            <a style="text-decoration:none" onClick="stop(this,'{{$zygw->id}}')"
                               href="javascript:;" class="c-primary ml-5"
                               title="停用">
                                停用
                            </a>
                        </div>
                        <div class="mt-5">
                            <a title="编辑顾问" href="javascript:;"
                               onclick="edit('编辑顾问-{{$zygw->name}}','{{URL::asset('/admin/house/zygw/edit')}}?id={{$zygw->id}}&house_id={{$data->id}}',{{$zygw->id}})"
                               class="c-primary ml-5" style="text-decoration:none">
                                编辑顾问
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">

    /*置业顾问-隐藏*/
    function stop(obj, id) {
        console.log("stop id:" + id);
        layer.confirm('确认要隐藏吗？', function (index) {
            //此处请求后台程序，下方是成功后的前台处理
            var param = {
                id: id,
                status: 0,
                _token: "{{ csrf_token() }}"
            }
            //从后台设置置业顾问状态
            ajaxRequest('{{URL::asset('')}}' + "admin/house/zygw/setStatus/" + id, param, "GET", function (ret) {
                if (ret.result == true) {
                    $(".btn-refresh").click();
                }
            });
            layer.msg('已隐藏', {icon: 5, time: 1000});
        });
    }

    /*置业顾问-显示*/
    function start(obj, id) {
        layer.confirm('确认要显示吗？', function (index) {
            //此处请求后台程序，下方是成功后的前台处理
            var param = {
                id: id,
                status: 1,
                _token: "{{ csrf_token() }}"
            }
            //从后台设置置业顾问状态
            ajaxRequest('{{URL::asset('')}}' + "admin/house/zygw/setStatus/" + id, param, "GET", function (ret) {
                if (ret.result == true) {
                    $(".btn-refresh").click();
                }
            });
            layer.msg('已显示', {icon: 6, time: 1000});
        });
    }

    /*置业顾问-编辑*/
    function edit(title, url, id) {
        console.log("edit url:" + url);
        var index = layer.open({
            type: 2,
            area: ['650px', '350px'],
            fixed: false,
            maxmin: true,
            title: title,
            content: url
        });
    }

</script>