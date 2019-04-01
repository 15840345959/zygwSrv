<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin\Baobei;

use App\Components\ADManager;
use App\Components\AdminManager;
use App\Components\BaobeiManager;
use App\Components\BaobeiPayWayManager;
use App\Components\DateTool;
use App\Components\HuxingManager;
use App\Components\QNManager;
use App\Components\ResetDealInfoRecordManager;
use App\Http\Controllers\ApiResponse;
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
        $can_jiesuan_status = null;
        $pay_zhongjie_status = null;
        $status = null;
        $search_word = null;
        $client_id = null;
        $user_id = null;
        $anchang_id = null;
        $start_time = null;
        $end_time = null;

        if (array_key_exists('baobei_status', $data)) {
            $baobei_status = $data['baobei_status'];
        }
        if (array_key_exists('search_word', $data)) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('can_jiesuan_status', $data)) {
            $can_jiesuan_status = $data['can_jiesuan_status'];
        }
        if (array_key_exists('pay_zhongjie_status', $data)) {
            $pay_zhongjie_status = $data['pay_zhongjie_status'];
        }
        if (array_key_exists('status', $data)) {
            $status = $data['status'];
        }
        if (array_key_exists('user_id', $data)) {
            $user_id = $data['user_id'];
        }
        if (array_key_exists('client_id', $data)) {
            $client_id = $data['client_id'];
        }
        if (array_key_exists('anchang_id', $data)) {
            $anchang_id = $data['anchang_id'];
        }
        if (array_key_exists('start_time', $data)) {
            $start_time = $data['start_time'];
        }
        if (array_key_exists('end_time', $data)) {
            $end_time = $data['end_time'];
        }

        $con_arr = array(
            'search_word' => $search_word,
            'status' => $status,
            'baobei_status' => $baobei_status,
            'pay_zhongjie_status' => $pay_zhongjie_status,
            'can_jiesuan_status' => $can_jiesuan_status,
            'user_id' => $user_id,
            'anchang_id' => $anchang_id,
            'client_id' => $client_id,
            'start_time' => $start_time,
            'end_time' => $end_time
        );

        $baobeis = BaobeiManager::getListByCon($con_arr, true);
        foreach ($baobeis as $baobei) {
            $baobei = BaobeiManager::getInfoByLevel($baobei, "012");
        }
//        dd($stmt);
        return view('admin.baobei.baobei.index', ['admin' => $admin, 'con_arr' => $con_arr, 'datas' => $baobeis]);
    }


    /*
    * 报备信息-get
    *
    * By mtt
    *
    * 2018-4-9
    */
    public function info(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数id$id']);
        }

        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $baobei = BaobeiManager::getById($data['id']);
        $baobei = BaobeiManager::getInfoByLevel($baobei, '0123');

        //获取变更记录
        $resetDealInfoRecords = ResetDealInfoRecordManager::getListByCon(['baobei_id' => $baobei->id], false);
        foreach ($resetDealInfoRecords as $resetDealInfoRecord) {
            $resetDealInfoRecord = ResetDealInfoRecordManager::getInfoByLevel($resetDealInfoRecord, '01');
        }

        return view('admin.baobei.baobei.info', ['admin' => $admin, 'data' => $baobei
            , 'resetDealInfoRecords' => $resetDealInfoRecords, 'upload_token' => $upload_token]);
    }


    /*
     * 中介结算
     *
     * By TerryQi
     *
     * 2019-03-07
     *
     */
    public function payZhongjie(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数id$id']);
        }

        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $baobei = BaobeiManager::getById($data['id']);
        $baobei = BaobeiManager::getInfoByLevel($baobei, '0123');

        return view('admin.baobei.baobei.payZhongjie', ['admin' => $admin, 'data' => $baobei, 'upload_token' => $upload_token]);
    }


    /*
     * 中介结算
     *
     * By TerryQi
     *
     * 2019-03-07
     *
     */
    public function payZhongjiePost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
            'pay_zhongjie_attach' => 'required',
        ]);
        if (!$requestValidationResult) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数id$id']);
        }

        $baobei = BaobeiManager::getById($data['id']);
        //未找到报备信息
        if (!$baobei) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，未找到报备单']);
        }
        //如果不符合结算条件
        if (!($baobei->can_jiesuan_status == '1' && $baobei->pay_zhongjie_status == '0')) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '报备单不符合结算条件']);
        }

        $baobei->pay_admin_id = $admin->id;
        $baobei->pay_zhongjie_time = DateTool::getCurrentTime();
        $baobei->pay_zhongjie_status = '1';
        $baobei->pay_zhongjie_attach = $data['pay_zhongjie_attach'];
        $baobei->save();

        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 重置交易信息
     *
     * By TerryQi
     *
     * 2019-03-08
     *
     */
    public function resetDealInfo(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数id$id']);
        }

        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $baobei = BaobeiManager::getById($data['id']);
        $baobei = BaobeiManager::getInfoByLevel($baobei, '0123');

        //户型
        $huxings = HuxingManager::getListByCon(['status' => '1', 'house_id' => $baobei->house_id], false);
        //支付方式
        $pay_ways = BaobeiPayWayManager::getListByCon(['status' => '1'], false);

        return view('admin.baobei.baobei.resetDealInfo', ['admin' => $admin, 'data' => $baobei
            , 'upload_token' => $upload_token, 'pay_ways' => $pay_ways, 'huxings' => $huxings]);
    }

    /*
     * 调整交易信息
     *
     * By TerryQi
     *
     * 2019-03-08
     */
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
        $baobei = BaobeiManager::setInfo($baobei, $data);
        $huxing = HuxingManager::getById($data['deal_huxing_id']);
        $huxing = HuxingManager::getInfoByLevel($huxing, '');
        $pay_way = BaobeiPayWayManager::getById($data['pay_way_id']);
        $yongjin = 0;
        //获取佣金金额
        if ($huxing->yongjin_type == '0') { //固定金额
            $yongjin = $huxing->yongjin_value;
        }
        if ($huxing->yongjin_type == "1") {
            $yongjin = (double)($huxing->yongjin_value * $data['deal_price']) / 1000; //成交额千分比
        }
        $baobei->yongjin = $yongjin;
        $baobei->save();
        //记录修改信息
        $resetDealInfoRecord = new ResetDealInfoRecord();
        $resetDealInfoRecord->baobei_id = $baobei->id;
        $resetDealInfoRecord->admin_id = $admin->id;
        $resetDealInfoRecord->desc = "报备单变更为:   产品" . $huxing->name . "(" . $data['deal_huxing_id'] . ")" . $huxing->yongjin_type_str . " " . $huxing->yongjin_value_str .
            "   成交面积:" . $data['deal_size'] . "     成交房号:" . $data['deal_room'] . "     成交金额:" . $data['deal_price'] . "    支付方式（" . $data['pay_way_id'] . "）:" . $pay_way->name;
        $resetDealInfoRecord->save();

        return ApiResponse::makeResponse(true, $baobei, ApiResponse::SUCCESS_CODE);
    }
}