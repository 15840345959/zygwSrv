@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->

    <section class="container-fluid page-404 minWP text-c">
        <p class="error-title"><i class="Hui-iconfont va-m" style="font-size:80px">&#xe688;</i>
        </p>
        <p class="error-info">
        <h3 class="">
            业务错误，请联系<a href="#">技术人员处理</a>
        </h3>
        </p>
        <p>
            <div class="error-content">
                <h3><i class="fa fa-warning text-red"></i>There is some Error</h3>
        <p>
            具体错误如下（请描述操作方法，并截取该页面）
        </p>
        <p>
            @if($msg)
                {{$msg}}
            @else
                暂无错误提示，请重现问题并反馈管理员
            @endif
        </p>
        <p>
            <a href="#" class="c-primary">沈阳艺萨艺术发展有限公司 ISART 2015-2018</a>
        </p>
        </div>
        </p>
    </section>
@endsection