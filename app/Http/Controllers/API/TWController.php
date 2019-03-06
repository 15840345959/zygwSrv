<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\API;

use App\Components\TWManager;
use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\QNManager;
use App\Components\TWStepManager;
use App\Components\XJManager;
use App\Components\Utils;
use App\Libs\CommonUtils;
use App\Models\Article;
use App\Models\Doctor;
use App\Models\TWStep;
use App\Models\TWInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class TWController
{

    /*
      * 根据id获取合作细则详情
      *
      * By TerryQi
      *
      * 2017-12-08
      *
      */
    public function getById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $tw = TWManager::getById($data['id']);
        $tw = TWManager::getInfoByLevel($tw, '');
        return ApiResponse::makeResponse(true, $tw, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据类型获取图文详情
     *
     * By TerryQi
     *
     * 2018-06-05
     */
    public function getByType(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'type' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
//        $tw=TWManager::getById($data['id']);
        $tw = TWManager::getListByCon(['type' => $data['type']], false)->first();
        if ($tw) {
            $tw = TWManager::getInfoByLevel($tw, '');
        }
        return ApiResponse::makeResponse(true, $tw, ApiResponse::SUCCESS_CODE);
    }

}
