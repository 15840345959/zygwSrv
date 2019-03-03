@extends('admin.layouts.app')

@section('content')
    <div class="page-container">
        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr>
                    <th scope="col" colspan="100">产品佣金设置记录</th>
                </tr>
                <tr class="text-c">
                    {{--<th width="25"><input type="checkbox" name="" value=""></th>--}}
                    <th width="20">id</th>
                    <th width="60">产品名称</th>
                    <th width="60">管理员</th>
                    <th width="80">设置时间</th>
                    <th width="200">操作内容</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr class="text-c">
                        {{--<td><input type="checkbox" value="1" name=""></td>--}}
                        <td>{{$data->id}}</td>
                        <td>{{$data->huxing->name}}</td>
                        <td><span class="c-primary">{{$data->admin->name}}</span></td>
                        <td>{{$data->created_at}}</td>
                        <td><span class="c-primary">{{$data->record}}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection