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
use App\Components\HuxingStyleManager;
use App\Components\HouselabelManager;
use App\Components\HuxingStyleStyleManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\ZYGWManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\HuxingStyle;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class HuxingStyleController extends Controller
{

    /*
     * 根据id获取户型
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
        $huxingStyle = HuxingStyleManager::getById($data['id']);
        $huxingStyle = HuxingStyleManager::getInfoByLevel($huxingStyle, "");

        return ApiResponse::makeResponse(true, $huxingStyle, ApiResponse::SUCCESS_CODE);
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
        if (array_key_exists('huxing_id', $data) && !Utils::isObjNull($data['huxing_id'])) {
            $house_id = $data['huxing_id'];
        }

        $con_arr = array(
            'huxing_id' => $house_id,
            'status' => '1'
        );

        //根据条件搜索楼盘
        $huxingStyles = HuxingStyleManager::getListByCon($con_arr, false);
        foreach ($huxingStyles as $huxingStyle) {
            $huxingStyle = HuxingStyleManager::getInfoByLevel($huxingStyle, "");
        }
        return ApiResponse::makeResponse(true, $huxingStyles, ApiResponse::SUCCESS_CODE);
    }
}