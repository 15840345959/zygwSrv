<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\AdminManager;
use App\Components\BaobeiManager;
use App\Components\DateTool;
use App\Components\Utils;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use Maatwebsite\Excel\Facades\Excel;

class StmtController
{
    //首页
    public function index(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //检索的时间段
        $start_date = date("Y-m-d", strtotime("-30 day"));
        $end_date = date("Y-m-d", time());
        if (array_key_exists('start_date', $data) && !Utils::isObjNull($data['start_date'])) {
            $start_date = $data['start_date'];
        }
        if (array_key_exists('end_date', $data) && !Utils::isObjNull($data['end_date'])) {
            $end_date = $data['end_date'];
        }

        $con_arr = array(
            'start_date' => $start_date,
            'end_date' => $end_date
        );

        //报备维度统计信息
        $baobei_stmt = array([
            "all" => BaobeiManager::getBaobeiStmtByStatus(null, null, null, null, $start_date, $end_date),
            "baobei_status0" => BaobeiManager::getBaobeiStmtByStatus(['0'], null, null, null, $start_date, $end_date),
            "baobei_status1" => BaobeiManager::getBaobeiStmtByStatus(['1'], null, null, null, $start_date, $end_date),
            "baobei_status2" => BaobeiManager::getBaobeiStmtByStatus(['2'], null, null, null, $start_date, $end_date),
            "baobei_status3" => BaobeiManager::getBaobeiStmtByStatus(['3'], null, null, null, $start_date, $end_date),
            "baobei_status4" => BaobeiManager::getBaobeiStmtByStatus(['4'], null, null, null, $start_date, $end_date),
        ]);
        //报备趋势
        $daofang_trend = BaobeiManager::getDaofangTrend(null, $start_date, $end_date);
//        dd($daofang_trend);
        //结算维度统计信息
        $jiesuan_stmt = array([
            "all" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], null, null, null, $start_date, $end_date),
            "can_jiesuan_status0" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], ['0'], null, null, $start_date, $end_date),
            "can_jiesuan_status1" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], ['1'], null, null, $start_date, $end_date),
            "pay_zhongjie_status0" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], ['1'], ['0'], null, $start_date, $end_date),
            "pay_zhongjie_status1" => BaobeiManager::getBaobeiStmtByStatus(['2', '3', '4'], ['1'], ['1'], null, $start_date, $end_date),
        ]);
        //佣金信息
        $yongjin_stmt = array([
            "all" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], null, null, null, $start_date, $end_date),
            "can_jiesuan_status0" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], ['0'], null, null, $start_date, $end_date),
            "can_jiesuan_status1" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], ['1'], null, null, $start_date, $end_date),
            "pay_zhongjie_status0" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], ['1'], ['0'], null, $start_date, $end_date),
            "pay_zhongjie_status1" => BaobeiManager::getYongjinStmtByStatus(['2', '3', '4'], ['1'], ['1'], null, $start_date, $end_date),
        ]);

        //佣金相关趋势
        $yongjin_trend = array([
            'shengcheng_yongjin' => BaobeiManager::getShengchengYongjinTrend(null, $start_date, $end_date),
            'queren_yongjin' => BaobeiManager::getQueRenYongjinTrend(null, $start_date, $end_date),
            'zhifu_yongjin' => BaobeiManager::getZhiFuYongjinTrend(null, $start_date, $end_date)]);

        return view('admin.stmt.index', ['admin' => $admin
            , 'con_arr' => $con_arr
            , 'baobei_stmt' => \GuzzleHttp\json_encode($baobei_stmt)
            , 'daofang_trend' => \GuzzleHttp\json_encode($daofang_trend)
            , 'jiesuan_stmt' => \GuzzleHttp\json_encode($jiesuan_stmt)
            , 'yongjin_stmt' => \GuzzleHttp\json_encode($yongjin_stmt)
            , 'yongjin_trend' => \GuzzleHttp\json_encode($yongjin_trend)]);
    }


    //数据导出
    public function export(Request $request)
    {
        $data = $request->all();
        Utils::processLog(__METHOD__, '', " " . "data:" . json_encode($data));
        $start_date = null;
        $end_date = null;
        if (array_key_exists('start_date', $data) && !Utils::isObjNull($data['start_date'])) {
            $start_date = $data['start_date'];
        }
        if (array_key_exists('end_date', $data) && !Utils::isObjNull($data['end_date'])) {
            $end_date = DateTool::dateAdd('D', 1, $data['end_date']);
        }
        $con_arr = array(
            'start_time' => $start_date,
            'end_time' => $end_date
        );
        Utils::processLog(__METHOD__, '', " " . "con_arr:" . json_encode($con_arr));
        $baobeis = BaobeiManager::getListByCon($con_arr, false);
        foreach ($baobeis as $baobei) {
            $baobei = BaobeiManager::getInfoByLevel($baobei, "0");
        }
//        dd($baobeis->get(18));
        $cellData = [['报备流水', '客户姓名', '客户电话', '报备单状态', '是否可结算', '是否已结算', '佣金金额'
            , '中介姓名', '中介电话', '案场负责人姓名', '案场负责人电话', '意向楼盘', '报备时间', '计划到访时间', '到访方式'
            , '到访时间', '成交时间', '成交面积', '成交户型', '成交房号', '付款方式', '签约时间', '全款到账时间'
            , '顾问', '客户住址', '意向面积', '关注要点', '认知途径', '购房目的']];

        Utils::processLog(__METHOD__, '', " " . "cellData:" . json_encode($cellData));

        foreach ($baobeis as $baobei) {
            $cell_data = array();
            array_push($cell_data, $baobei->trade_no);
            array_push($cell_data, $baobei->client->name);
            array_push($cell_data, $baobei->client->phonenum);
            array_push($cell_data, BaobeiManager::getBaobeiStr($baobei->baobei_status));
            array_push($cell_data, BaobeiManager::getCanJieSuanStr($baobei->can_jiesuan_status));
            array_push($cell_data, BaobeiManager::getPayZhongJieStr($baobei->pay_zhongjie_status));
            array_push($cell_data, $baobei->yongjin);
            array_push($cell_data, isset($baobei->user) ? $baobei->user->real_name : '--');
            array_push($cell_data, isset($baobei->user) ? $baobei->user->phonenum : '--');
            array_push($cell_data, isset($baobei->anchang) ? $baobei->anchang->real_name : '--');
            array_push($cell_data, isset($baobei->anchang) ? $baobei->anchang->phonenum : '--');
            array_push($cell_data, isset($baobei->house) ? $baobei->house->title : '--');
            array_push($cell_data, $baobei->created_at);
            array_push($cell_data, isset($baobei->plan_visit_time) ? $baobei->plan_visit_time : '--');
            array_push($cell_data, BaobeiManager::getVisitWayTxt($baobei->visit_way));
            array_push($cell_data, isset($baobei->visit_time) ? $baobei->visit_time : '--');
            array_push($cell_data, isset($baobei->deal_time) ? $baobei->deal_time : '--');
            array_push($cell_data, isset($baobei->deal_size) ? $baobei->deal_size : '--');
            array_push($cell_data, isset($baobei->deal_huxing) ? $baobei->deal_huxing->name : '--');
            array_push($cell_data, isset($baobei->deal_room) ? $baobei->deal_room : '--');
            array_push($cell_data, isset($baobei->pay_way) ? $baobei->pay_way->name : '--');
            array_push($cell_data, isset($baobei->sign_time) ? $baobei->sign_time : '--');
            array_push($cell_data, isset($baobei->qkdz_time) ? $baobei->qkdz_time : '--');
            array_push($cell_data, isset($baobei->guwen) ? $baobei->guwen->name : '--');
            array_push($cell_data, isset($baobei->address) ? $baobei->address : '--');
            array_push($cell_data, isset($baobei->size) ? $baobei->size : '--');
            array_push($cell_data, isset($baobei->care) ? $baobei->care->name : '--');
            array_push($cell_data, isset($baobei->know_way) ? $baobei->know_way->name : '--');
            array_push($cell_data, isset($baobei->purpose) ? $baobei->purpose->name : '--');
            Utils::processLog(__METHOD__, '', " " . "cell_data:" . json_encode($cell_data));
            //推入表格
            array_push($cellData, $cell_data);
        }

        Utils::processLog(__METHOD__, '', " " . "开始导出Excel表:");

//        dd($cellData);
        Excel::create('报备单详情', function ($excel) use ($cellData) {
            $excel->sheet('报备单详情', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}