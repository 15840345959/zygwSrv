<div class="page-container">

    <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;"
                    onclick="edit('新建产品','{{URL::asset('/admin/house/huxing/edit')}}?house_id={{$data->id}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 新建产品
                 </a>
            </span>
        {{--<span class="r">共有数据：<strong>{{$datas->count()}}</strong> 条</span>--}}
    </div>

    <div class="mt-20">
        <table class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr>
                <th scope="col" colspan="100">产品列表</th>
            </tr>
            <tr class="text-c">
                {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                <th width="20">ID</th>
                <th width="60">图片</th>
                <th width="60">名称</th>
                <th width="60">类型</th>
                <th width="60">面积</th>
                <th width="50">分佣类型</th>
                <th width="50">佣金比例</th>
                <th width="50">状态</th>
                <th width="50">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($huxings as $huxing)
                <tr class="text-c">
                    {{--<td><input type="checkbox" value="1" name=""></td>--}}
                    <td>{{$huxing->id}}</td>
                    <td><img src="{{ $huxing->image.'?imageView2/1/w/60/h/40/interlace/1/q/75|imageslim'}}"/></td>
                    <td>{{$huxing->name}}</td>
                    <td>{{$huxing->type->name}}</td>
                    <td>{{$huxing->size_min}}至{{$huxing->size_max}}m²</td>
                    <td><span class="c-primary">{{$huxing->yongjin_type_str}}</span></td>
                    <td><span class="c-primary">{{$huxing->yongjin_value_str}}</span></td>
                    <td>
                        <div>
                            @if($huxing->status=="1")
                                <span class="label label-success radius">正常</span>
                            @else
                                <span class="label label-default radius">冻结</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div>
                            <a style="text-decoration:none" onClick="start(this,'{{$huxing->id}}')"
                               href="javascript:;" class="c-primary"
                               title="启用">
                                启用
                            </a>

                            <a style="text-decoration:none" onClick="stop(this,'{{$huxing->id}}')"
                               href="javascript:;" class="c-primary ml-5"
                               title="停用">
                                停用
                            </a>
                        </div>
                        <div class="mt-5">
                            <a title="编辑产品" href="javascript:;"
                               onclick="edit('编辑产品-{{$huxing->name}}','{{URL::asset('/admin/house/huxing/edit')}}?id={{$huxing->id}}&house_id={{$data->id}}',{{$huxing->id}})"
                               class="c-primary ml-5" style="text-decoration:none">
                                编辑产品
                            </a>
                        </div>
                        <div class="mt-5">
                            <a title="设置佣金" href="javascript:;"
                               onclick="editYongjin('设置佣金-{{$huxing->name}}','{{URL::asset('/admin/house/huxing/editYongjin')}}?id={{$huxing->id}}&house_id={{$data->id}}',{{$huxing->id}})"
                               class="c-primary ml-5" style="text-decoration:none">
                                设置佣金
                            </a>
                        </div>
                        <div class="mt-5">
                            <a title="佣金设置记录" href="javascript:;"
                               onclick="huxingYongjinRecord('佣金设置记录-{{$huxing->name}}','{{URL::asset('/admin/house/huxingYongjinRecord/index')}}?huxing_id={{$huxing->id}}&house_id={{$data->id}}',{{$huxing->id}})"
                               class="c-primary ml-5" style="text-decoration:none">
                                佣金设置记录
                            </a>
                        </div>
                        <div class="mt-5">
                            <a title="编辑户型" href="javascript:;"
                               onclick="editHuxingStyle('编辑户型-{{$huxing->name}}','{{URL::asset('/admin/house/huxingStyle/index')}}?huxing_id={{$huxing->id}}')"
                               class="c-primary ml-5" style="text-decoration:none">
                                编辑户型
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

    /*户型-隐藏*/
    function stop(obj, id) {
        console.log("stop id:" + id);
        layer.confirm('确认要隐藏吗？', function (index) {
            //此处请求后台程序，下方是成功后的前台处理
            var param = {
                id: id,
                status: 0,
                _token: "{{ csrf_token() }}"
            }
            //从后台设置户型状态
            ajaxRequest('{{URL::asset('')}}' + "admin/house/huxing/setStatus/" + id, param, "GET", function (ret) {
                if (ret.result == true) {
                    location.replace('{{URL::asset('/admin/house/house/edit')}}?item={{$item}}&id=' + ret.ret.house_id);         //这里是house_id
                }
            });
            layer.msg('已隐藏', {icon: 5, time: 1000});
        });
    }

    /*户型-显示*/
    function start(obj, id) {
        layer.confirm('确认要显示吗？', function (index) {
            //此处请求后台程序，下方是成功后的前台处理
            var param = {
                id: id,
                status: 1,
                _token: "{{ csrf_token() }}"
            }
            //从后台设置户型状态
            ajaxRequest('{{URL::asset('')}}' + "admin/house/huxing/setStatus/" + id, param, "GET", function (ret) {
                if (ret.result == true) {
                    location.replace('{{URL::asset('/admin/house/house/edit')}}?item={{$item}}&id=' + ret.ret.house_id);        //这里是house_id
                }
            });
            layer.msg('已显示', {icon: 6, time: 1000});
        });
    }


    /*户型样式-编辑*/
    function editHuxingStyle(title, url) {
        creatIframe(url, title)
    }

    /*
     * 产品佣金设置记录
     * 
     * By TerryQi
     * 
     * 2019-03-02
     */
    function huxingYongjinRecord(title, url, id) {
        console.log("edit url:" + url);
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        console.log(index);
        layer.full(index);
    }


    /*产品佣金-编辑*/
    function editYongjin(title, url, id) {
        console.log("editYongjin url:" + url);
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