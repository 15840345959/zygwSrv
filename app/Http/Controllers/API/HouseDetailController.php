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
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class HouseDetailController extends Controller
{

    /*
     * 根据id获取楼盘信息
     *
     * By TerryQi
     *
     * 2018-02-07
     *
     */
    public function getByHouseId(Request $request)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'house_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $houseDetail = HouseDetailManager::getByHouseId($data['house_id']);

        return ApiResponse::makeResponse(true, $houseDetail, ApiResponse::SUCCESS_CODE);
    }

}