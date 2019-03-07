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

        if (array_key_exists('baobei_status', $data)) {
            $baobei_status = $data['baobei_status'];
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
        if (array_key_exists('search_word', $data)) {
            $search_word = $data['search_word'];
        }

        $con_arr = array(
            'search_word' => $search_word,
            'status' => $status,
            'baobei_status' => $baobei_status,
            'pay_zhongjie_status' => $pay_zhongjie_status,
            'can_jiesuan_status' => $can_jiesuan_status,
        );

        $baobeis = BaobeiManager::getListByCon($con_arr, true);
        foreach ($baobeis as $baobei) {
            $baobei = BaobeiManager::getInfoByLevel($baobei, "02");
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

        return view('admin.baobei.baobei.info', ['admin' => $admin, 'data' => $baobei, 'upload_token' => $upload_token]);
    }

}