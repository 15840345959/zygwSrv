@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb">楼盘管理 <a class="btn btn-success radius r btn-refresh"
                                    style="line-height:1.6em;margin-top:3px"
                                    title="刷新"
                                    onclick="location.replace('{{URL::asset('/admin/house/house/edit')}}?item={{$item}}&id={{isset($data)?$data->id:''}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>

    <div class="page-container">
        <div class="btn-group text-c">
            <span class="btn {{$item==0?'btn-primary':'btn-default'}} radius"
                  onclick="selectItem(0)">基础信息</span>
            <span class="btn {{$item==1?'btn-primary':'btn-default'}} radius"
                  onclick="selectItem(1)">视频管理</span>
            <span class="btn {{$item==2?'btn-primary':'btn-default'}} radius"
                  onclick="selectItem(2)">产品管理</span>
            <span class="btn {{$item==3?'btn-primary':'btn-default'}} radius"
                  onclick="selectItem(3)">案场负责人列表</span>
            <span class="btn {{$item==4?'btn-primary':'btn-default'}} radius"
                  onclick="selectItem(4)">置业顾问管理</span>
            <span class="btn {{$item==5?'btn-primary':'btn-default'}} radius"
                  onclick="selectItem(5)">楼盘详情</span>
        </div>

        <div class="border-t mt-10 mb-20"></div>

        {{--通过item控制显示选项--}}
        @if($item=='0')
            @include('admin.house.house.item0')
        @endif
        @if($item=='1')
            @include('admin.house.house.item1')
        @endif
        @if($item=='2')
            @include('admin.house.house.item2')
        @endif
        @if($item=='3')
            @include('admin.house.house.item3')
        @endif
        @if($item=='4')
            @include('admin.house.house.item4')
        @endif
        @if($item=='5')
            @include('admin.house.house.item5')
        @endif
    </div>

@endsection

@section('script')
    <script type="text/javascript">

        //楼盘id
        var house_id = '{{$data->id}}';

        $(function () {

        });

        //选择项目
        function selectItem(item) {

            if (judgeIsAnyNullStr(house_id)) {
                layer.alert('必须配置楼盘基本信息后才可以进行其他配置');
                return;
            }
            var index = layer.load(2, {time: 10 * 1000}); //加载
            location.replace('{{URL::asset('/admin/house/house/edit')}}?id={{$data->id}}&item=' + item);
        }

    </script>
@endsection