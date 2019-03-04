<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\Jifen;

use App\Components\GoodsExchangeManager;
use App\Components\JifenChangeRecordManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\Goods;
use Illuminate\Http\Request;

class JifenChangeRecordController
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

        $user_id = null;    //搜索条件

        if (array_key_exists('user_id', $data) && !Utils::isObjNull($data['user_id'])) {
            $user_id = $data['user_id'];
        }

        $con_arr = array(
            'user_id' => $user_id,
        );

        $jifenChangeRecords = JifenChangeRecordManager::getListByCon($con_arr, true);
        foreach ($jifenChangeRecords as $jifenChangeRecord) {
            $jifenChangeRecord = JifenChangeRecordManager::getInfoByLevel($jifenChangeRecord, '0');
        }

//        dd($goodsExchanges);

        return view('admin.jifen.jifenChangeRecord.index', ['admin' => $admin, 'datas' => $jifenChangeRecords, 'con_arr' => $con_arr]);
    }

}





