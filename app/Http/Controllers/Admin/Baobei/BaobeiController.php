<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\ADManager;
use App\Components\AdminManager;
use App\Components\BaobeiManager;
use App\Components\BaobeiPayWayManager;
use App\Components\DateTool;
use App\Components\HuxingManager;
use App\Components\QNManager;
use App\Libs\CommonUtils;
use App\Models\ResetDealInfoRecord;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;


class BaobeiController
{

    //首页信息
    public function index(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        //报备状态条件
        $baobei_status = null;
        if (array_key_exists('baobei_status', $data)) {
            $baobei_status = $data['baobei_status'];
        }
        //是否可以结算条件
        $can_jiesuan_status = null;
        if (array_key_exists('can_jiesuan_status', $data)) {
            $can_jiesuan_status = $data['can_jiesuan_status'];
        }
        //是否已经结算条件
        $pay_zhongjie_status = null;
        if (array_key_exists('pay_zhongjie_status', $data)) {
            $pay_zhongjie_status = $data['pay_zhongjie_status'];
        }
        //trade_no
        $trade_no = null;
        if (array_key_exists('trade_no', $data)) {
            $trade_no = $data['trade_no'];
        }
        $datas = BaobeiManager::getListByStatusPaginate($baobei_status, $can_jiesuan_status, $pay_zhongjie_status, $trade_no);
        foreach ($datas as $data) {
            $data = BaobeiManager::getInfoByLevel($data, "0");
        }
        //获取统计信息
        $stmt = new Collection([
            'all_nums' => BaobeiManager::getListByStatus(null, null, null, null, null, null)->count(),
            'baobei_status0' => BaobeiManager::getListByStatus('0', null, null, null, null, null)->count(),
            'baobei_status1' => BaobeiManager::getListByStatus('1', null, null, null, null, null)->count(),
            'baobei_status2' => BaobeiManager::getListByStatus('2', null, null, null, null, null)->count(),
            'baobei_status3' => BaobeiManager::getListByStatus('3', null, null, null, null, null)->count(),
            'baobei_status4' => BaobeiManager::getListByStatus('4', null, null, null, null, null)->count(),
            'can_jiesuan_status1' => BaobeiManager::getListByStatus(null, '1', null, null, null, null)->count(),
            'pay_zhongjie_status1' => BaobeiManager::getListByStatus(null, null, '1', null, null, null)->count(),
        ]);
//        dd($stmt);
        return view('admin.baobei.index', ['admin' => $admin, 'stmt' => $stmt, 'datas' => $datas]);
    }

    //报备详情信息
    public function info(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $baobei = BaobeiManager::getById($data['id']);
        if ($baobei) {
            $baobei = BaobeiManager::getInfoByLevel($baobei, "0");
        }
//        dd($baobei);
        return view('admin.baobei.info', ['admin' => $admin, 'data' => $baobei]);
    }

    //重新设置交易信息
    public function resetDealInfo(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //如果非根级管理员，则不可以修改交易信息
        if ($admin->role != '1') {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '权限错误，只有根级管理员可以修改报备交易信息']);
        }
        $baobei = BaobeiManager::getById($data['id']);
        if ($baobei->pay_zhongjie_status == '1') {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '状态错误，只有未结算的报备单可以修改交易信息']);
        }
        //获取产品列表
        $huxings = HuxingManager::getListByHouseId($baobei->house_id);
        $pay_ways = BaobeiPayWayManager::getListValid();

        return view('admin.baobei.resetDealInfo', ['admin' => $admin, 'data' => $baobei, 'huxings' => $huxings, 'pay_ways' => $pay_ways]);

    }


    //重新设置交易佣金
    public function resetDealInfoPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
            'deal_size' => 'required',
            'deal_room' => 'required',
            'deal_price' => 'required',
            'deal_huxing_id' => 'required',
            'pay_way_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //报备信息
        $baobei = BaobeiManager::getById($data['id']);
        $baobei = BaobeiManager::setBaoBei($baobei, $data);
        $huxing = HuxingManager::getById($data['deal_huxing_id']);
        $yongjin = 0;
        //获取佣金金额
        if ($huxing->yongjin_type == '0') { //固定金额
            $yongjin = $huxing->yongjin_value;
        }
        if ($huxing->yongjin_type == "1") {
            $yongjin = $huxing->yongjin_value * $data['deal_price'] / 1000; //成交额千分比
        }
        $baobei->yongjin = $yongjin;
        $baobei->save();
        //记录修改信息
        $resetDealInfoRecord = new ResetDealInfoRecord();
        $resetDealInfoRecord->baobei_id = $baobei->id;
        $resetDealInfoRecord->admin_id = $admin->id;
        $resetDealInfoRecord->desc = "报备单变更为(" . $data['deal_huxing_id'] . "):" . $huxing->name .
            " 成交面积:" . $data['deal_size'] . " 成交房号:" . $data['deal_room'] . " 成交金额:" . $data['deal_price'] . " 支付方式:" . $data['pay_way_id'];
        $resetDealInfoRecord->save();

        return redirect('/admin/baobei/info?id=' . $baobei->id);
    }


}