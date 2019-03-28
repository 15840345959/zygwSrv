<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\HomeManager;
use App\Components\HouseManager;
use App\Components\UserManager;
use App\Components\UserUpManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\UserUp;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class UserUpController extends Controller
{


    /*
     * 中介申请成为案场负责人接口
     *
     * By TerryQi
     *
     * 2017-01-21
     */
    public function userUpApply(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
            'house_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //是否该用户重复申请
        $userUp = UserUpManager::getListByCon(['user_id' => $data['user_id'], 'house_id' => $data['house_id']], false)->first();
        if ($userUp) {
            return ApiResponse::makeResponse(false, "用户已经申请", ApiResponse::INNER_ERROR);
        }
        $userUp = new UserUp();
        $userUp = UserUpManager::setInfo($userUp, $data);
        $userUp->save();
        $userUp = UserUpManager::getById($userUp->id);
        return ApiResponse::makeResponse(true, $userUp, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据userid获取审核记录
     *
     * By TerryQi
     *
     * 2018-02-14
     *
     */
    public function getListByUserId(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $userUps = UserUpManager::getListByCon(['user_id' => $data['user_id']], false);
        foreach ($userUps as $userUp) {
            $userUp = UserUpManager::getInfoByLevel($userUp, "01");
        }
        return ApiResponse::makeResponse(true, $userUps, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 获取案场负责人所属楼盘列表
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public function getUserUpHousesByUserId(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //是否该用户重复申请
        $userUpHouses = UserUpManager::getUserUpHousesByUserId($data['user_id']);
        foreach ($userUpHouses as $userUpHouse) {
            $userUpHouse->house = HouseManager::getById($userUpHouse->house_id);
        }
        return ApiResponse::makeResponse(true, $userUpHouses, ApiResponse::SUCCESS_CODE);
    }
}