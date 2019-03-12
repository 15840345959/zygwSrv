@extends('admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 业务概览 <span
                class="c-gray en">&gt;</span> 业务概览 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace('{{URL::asset('/admin/stmt/index')}}');"
                                                      title="刷新"
                                                      onclick="location.replace('{{URL::asset('/admin/stmt/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">

        <div class="text-c">
            <form id="search_form" action="{{URL::asset('admin/stmt/index')}}"
                  method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <span class="ml-5">开始时间：</span>
                    <input id="start_date" name="start_date" type="date" class="input-text" style="width:200px"
                           value="{{$con_arr['start_date']}}" placeholder="开始时间">
                    <span class="ml-5">结束时间：</span>
                    <input id="end_date" name="end_date" type="date" class="input-text" style="width:200px"
                           value="{{$con_arr['end_date']}}" placeholder="结束时间">
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                    <span class="ml-5">
                        <a href="{{URL::asset('/admin/stmt/export')}}?start_date={{$con_arr['start_date']}}&end_date={{$con_arr['end_date']}}"
                           target="_blank">
                        <span class="text-primary">点击下载报表</span>
                        </a>
                     </span>
                </div>
            </form>
        </div>

        <div class="mt-40">
            <div class="row">
                <div class="col-xs-4 text-c">
                    <h4>报备状态占比（个）</h4>
                    <div id="baobei_pie_div" class="mt-20" style="width: 100%;height: 280px;background: white;">

                    </div>
                </div>
                <div class="col-xs-8 text-c">
                    <h4>报备到访趋势图（个）</h4>
                    <div id="daofang_trend_bar_div" class="mt-20" style="width: 100%;height: 280px;background: white;">

                    </div>
                </div>
            </div>
        </div>

        <div style="height: 400px;"></div>

        <div class="pt-30">
            <div class="row">
                <div class="col-xs-3 text-c">
                    <h4>报备单-确认结算数占比（个）</h4>
                    <div id="can_jiesuan_pie_div" style="width: 100%;height: 240px;background: white;">

                    </div>
                </div>
                <div class="col-xs-3 text-c">
                    <h4>报备单-佣金支付数占比（个）</h4>
                    <div id="pay_zhongjie_pie_div" style="width: 100%;height: 240px;background: white;">

                    </div>
                </div>
                <div class="col-xs-3 text-c">
                    <h4>报备单-确认佣金金额（元）</h4>
                    <div id="yongjin_canjiesuan_pie_div" style="width: 100%;height: 240px;background: white;">

                    </div>
                </div>
                <div class="col-xs-3 text-c">
                    <h4>报备单-支付佣金金额（元）</h4>
                    <div id="yongjin_payzhongjie_pie_div" style="width: 100%;height: 240px;background: white;">

                    </div>
                </div>
            </div>
        </div>


        <div style="height: 300px;"></div>

        <div class="pt-30">
            <div class="row">
                <div class="col-xs-12 text-c">
                    <h4>佣金趋势（元）</h4>
                    <div id="yongjin_trend_div" style="width: 100%;height: 240px;background: white;">

                    </div>
                </div>
            </div>
        </div>


    </div>


@endsection

@section('script')
    <script type="text/javascript">


        $(function () {

        });

        //统计信息
        var baobei_stmt = {!!$baobei_stmt!!};
        var daofang_trend = {!! $daofang_trend !!};
        var jiesuan_stmt = {!!$jiesuan_stmt!!};
        var yongjin_stmt = {!!$yongjin_stmt!!};
        var yongjin_trend = {!!$yongjin_trend!!};

        //报备信息
        showBaoBeiPieChart();
        // //到访趋势
        showDaofangTrendBarChart();
        // //案场确认佣金
        showCanJieSuanPieChart();
        // //中介结算
        showPayZhongJiePieChart();
        // //佣金金额确认
        showYongjinCanJieSuanPieChart();
        // //佣金计算金额
        showYongjinPayZhongJiePieChart();
        // //显示佣金趋势
        showYongjinTrendLineChart();

        //展示报备图表
        function showBaoBeiBarChart() {
            var chart = echarts.init(document.getElementById('baobei_bar_div'));
            var option = {
                color: ['#3398DB'],
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        data: ['全部', '报备', '到访', '成交', '签约', '全款到账'],
                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '',
                        type: 'bar',
                        barWidth: '40%',
                        data: [baobei_stmt[0].all, baobei_stmt[0].baobei_status0
                            , baobei_stmt[0].baobei_status1, baobei_stmt[0].baobei_status2
                            , baobei_stmt[0].baobei_status3, baobei_stmt[0].baobei_status4]
                    }
                ]
            };
            chart.setOption(option);
        }

        //展示报备图表
        function showBaoBeiPieChart() {
            var chart = echarts.init(document.getElementById('baobei_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['报备', '到访', '成交', '签约', '全款到账']
                },
                series: [
                    {
                        name: '报备单类型',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: baobei_stmt[0].baobei_status0, name: '报备'},
                            {value: baobei_stmt[0].baobei_status1, name: '到访'},
                            {value: baobei_stmt[0].baobei_status2, name: '成交'},
                            {value: baobei_stmt[0].baobei_status3, name: '签约'},
                            {value: baobei_stmt[0].baobei_status4, name: '全款到账'}
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }

        //到访趋势
        function showDaofangTrendBarChart() {
            var chart = echarts.init(document.getElementById('daofang_trend_bar_div'));
            var date_arr = [];
            var date_value_arr = [];
            for (var i = 0; i < daofang_trend.length; i++) {
                date_arr.push(daofang_trend[i].tjdate);
                date_value_arr.push(daofang_trend[i].nums);
            }
            var option = {
                color: ['#3398DB'],
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        data: date_arr,
                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '',
                        type: 'bar',
                        barWidth: '40%',
                        data: date_value_arr
                    }
                ]
            };
            chart.setOption(option);
        }


        //展示能否结算
        function showCanJieSuanPieChart() {
            var chart = echarts.init(document.getElementById('can_jiesuan_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['待确认', '已确认']
                },
                series: [
                    {
                        name: '案场是否确认',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: jiesuan_stmt[0].can_jiesuan_status0, name: '待确认'},
                            {value: jiesuan_stmt[0].can_jiesuan_status1, name: '已确认'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }


        //中介结算
        function showPayZhongJiePieChart() {
            var chart = echarts.init(document.getElementById('pay_zhongjie_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['待结算', '已结算']
                },
                series: [
                    {
                        name: '是否向中介结算',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: jiesuan_stmt[0].pay_zhongjie_status0, name: '待结算'},
                            {value: jiesuan_stmt[0].pay_zhongjie_status1, name: '已结算'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }

        function showYongjinCanJieSuanPieChart() {
            var chart = echarts.init(document.getElementById('yongjin_canjiesuan_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['待结算', '已结算']
                },
                series: [
                    {
                        name: '案场是否确认',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: yongjin_stmt[0].can_jiesuan_status0, name: '待确认'},
                            {value: yongjin_stmt[0].can_jiesuan_status1, name: '已确认'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }

        //向中介支付
        function showYongjinPayZhongJiePieChart() {
            var chart = echarts.init(document.getElementById('yongjin_payzhongjie_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['待结算', '已结算']
                },
                series: [
                    {
                        name: '是否向中介结算',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: yongjin_stmt[0].pay_zhongjie_status0, name: '待结算'},
                            {value: yongjin_stmt[0].pay_zhongjie_status1, name: '已结算'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }

        //展示佣金趋势
        function showYongjinTrendLineChart() {
            var chart = echarts.init(document.getElementById('yongjin_trend_div'));

            var date_arr = [];
            var shengcheng_yongjin_arr = [];
            var queren_kejiesuan_arr = [];
            var zhifu_zhongjie_arr = [];
            for (var i = 0; i < yongjin_trend[0].shengcheng_yongjin.length; i++) {
                date_arr.push(yongjin_trend[0].shengcheng_yongjin[i].tjdate);
                shengcheng_yongjin_arr.push(yongjin_trend[0].shengcheng_yongjin[i].yongjin == null ? 0 : yongjin_trend[0].shengcheng_yongjin[i].yongjin);
                queren_kejiesuan_arr.push(yongjin_trend[0].queren_yongjin[i].yongjin == null ? 0 : yongjin_trend[0].queren_yongjin[i].yongjin);
                zhifu_zhongjie_arr.push(yongjin_trend[0].zhifu_yongjin[i].yongjin == null ? 0 : yongjin_trend[0].zhifu_yongjin[i].yongjin);
            }

            var option = {
                title: {},
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['产生佣金', '确认可结算', '支付中介']
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                toolbox: {
                    feature: {
                        saveAsImage: {}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: date_arr
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                        name: '产生佣金',
                        type: 'line',
                        stack: '日生成量',
                        data: shengcheng_yongjin_arr
                    },
                    {
                        name: '确认可结算',
                        type: 'line',
                        stack: '日生成量',
                        data: queren_kejiesuan_arr
                    },
                    {
                        name: '支付中介',
                        type: 'line',
                        stack: '日生成量',
                        data: zhifu_zhongjie_arr
                    }
                ]
            };
            chart.setOption(option);
        }

    </script>
@endsection