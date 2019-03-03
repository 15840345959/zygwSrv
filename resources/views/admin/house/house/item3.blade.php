<div class="page-container">

    <div class="mt-20">
        <table class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr>
                <th scope="col" colspan="100">案场负责人列表</th>
            </tr>
            <tr class="text-c">
                {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                <th width="20">ID</th>
                <th width="60">头像</th>
                <th width="60">昵称</th>
                <th width="60">姓名</th>
                <th width="60">电话</th>
                <th width="60">楼盘</th>
                <th width="60">申请时间</th>
                <th width="60">审核时间</th>
                <th width="60">审核状态</th>
                <th width="60">审核人</th>
                <th width="50">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($userUps as $userUp)
                <tr class="text-c">
                    {{--<td><input type="checkbox" value="1" name=""></td>--}}
                    <td>{{$userUp->id}}</td>
                    <td>
                        <img src="{{ $userUp->user->avatar ? $userUp->user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                             class="img-rect-30 radius-5">
                    </td>
                    <td>{{$userUp->user->nick_name?$userUp->user->nick_name:'--'}}</td>
                    <td>{{$userUp->user->real_name?$userUp->user->real_name:'--'}}</td>
                    <td>{{$userUp->user->phonenum?$userUp->user->phonenum:'--'}}</td>
                    <td>{{isset($userUp->house->title)?$userUp->house->title:'--'}}</td>
                    <td>{{$userUp->created_at}}</td>
                    <td>{{$userUp->sh_time}}</td>
                    <td>
                        <span class="c-primary">{{$userUp->status_str}}</span>
                    </td>
                    <td>{{isset($userUp->admin)?$userUp->admin->name:'--'}}</td>
                    <td>
                        <div>
                            <a style="text-decoration:none" onClick="down(this,'{{$userUp->id}}')"
                               href="javascript:;" class="c-primary ml-5"
                               title="降级">
                                降级
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

    //降级-down
    function down(obj, id) {
        layer.confirm('确认要降级吗？降级后该案场负责人的该楼盘申请状态将设置为驳回状态', function (index) {
            //此处请求后台程序，下方是成功后的前台处理
            var param = {
                id: id,
                status: 2,
                _token: "{{ csrf_token() }}"
            }
            //从后台设置案场负责人状态
            ajaxRequest('{{URL::asset('')}}' + "admin/user/userUp/setStatus/" + id, param, "GET", function (ret) {
                if (ret.result == true) {
                    $("btn-refresh").click();
                }
            });
            layer.msg('已降级', {icon: 5, time: 1000});
        });
    }

</script>