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
use App\Components\HuxingManager;
use App\Components\HouselabelManager;
use App\Components\HuxingStyleManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\ZYGWManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\Huxing;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class HuxingController extends Controller
{

    /*
     * 根据id获取楼盘信息
     *
     * By TerryQi
     *
     * 2018-02-07
     *
     */
    public function getById(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $huxing = HuxingManager::getById($data['id']);
        $huxing = HuxingManager::getInfoByLevel($huxing, "0");

        return ApiResponse::makeResponse(true, $huxing, ApiResponse::SUCCESS_CODE);
    }


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
        $huxings = HuxingManager::getListByCon($con_arr, false);
        $level = "0";
        if (array_key_exists('level', $data)) {
            $level = $data['level'];
        }
        foreach ($huxings as $huxing) {
            $huxing = HuxingManager::getInfoByLevel($huxing, $level);
        }
        return ApiResponse::makeResponse(true, $huxings, ApiResponse::SUCCESS_CODE);
    }
}