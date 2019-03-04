<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\ADManager;
use App\Components\HomeManager;
use App\Components\HouseDetailManager;
use App\Components\HouseManager;
use App\Components\ZYGWManager;
use App\Components\HouselabelManager;
use App\Components\HuxingStyleManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\Huxing;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class ZYGWController extends Controller
{
    
    /*
     * 根据条件搜索列表
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public function getListByCon(Request $request)
    {
        $data = $request->all();

        $house_id = null;    //搜索条件
        if (array_key_exists('house_id', $data) && !Utils::isObjNull($data['house_id'])) {
            $house_id = $data['house_id'];
        }

        $con_arr = array(
            'house_id' => $house_id,
            'status' => '1'
        );

        //根据条件搜索楼盘
        $zygws = ZYGWManager::getListByCon($con_arr, true);
        $level = "0";
        if (array_key_exists('level', $data)) {
            $level = $data['level'];
        }
        foreach ($zygws as $zygw) {
            $zygw = ZYGWManager::getInfoByLevel($zygw, $level);
        }
        return ApiResponse::makeResponse(true, $zygw, ApiResponse::SUCCESS_CODE);
    }
}