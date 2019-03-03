<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\House;

use App\Components\HouseManager;
use App\Components\HouseTypeManager;
use App\Components\HuxingAreaManager;
use App\Components\HuxinglabelManager;
use App\Components\HuxingManager;
use App\Components\HuxingTypeManager;
use App\Components\HuxingYongjinRecordManager;
use App\Components\QNManager;
use App\Components\RequestValidator;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\Huxing;
use App\Models\HuxingYongjinRecord;
use Illuminate\Http\Request;

class HuxingYongjinRecordController
{

    /*
     * 首页
     *
     * By mtt
     *
     * 2018-4-9
     */
    public static function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'huxing_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $huxing_id = $data['huxing_id'];

        $huxingYongjinRecords = HuxingYongjinRecordManager::getListByCon(['huxing_id' => $huxing_id], false);
        foreach ($huxingYongjinRecords as $huxingYongjinRecord) {
            $huxingYongjinRecord = HuxingYongjinRecordManager::getInfoByLevel($huxingYongjinRecord, '01');
        }

        return view('admin.house.huxingYongjinRecord.index', ['admin' => $admin, 'datas' => $huxingYongjinRecords]);
    }
}





