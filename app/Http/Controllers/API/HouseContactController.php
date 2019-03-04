<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\DateTool;
use App\Components\HouseContactManager;
use App\Components\HomeManager;
use App\Components\SendMessageManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class HouseContactController extends Controller
{

    /*
       * 根据id获取楼盘联系人信息
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
        $houseContact = HouseContactManager::getById($data['id']);
        //将图片转换为本地图片
        $img_name = Utils::downloadImage($houseContact->wx_ewm, './img/');

        $scheme = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
        $url = $scheme . $_SERVER['HTTP_HOST'];

        $houseContact->wx_ewm = $url . '/img/' . $img_name;

        return ApiResponse::makeResponse(true, $houseContact, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 获取楼盘联系人列表
     *
     * By TerryQi
     *
     * 2017-11-27
     */
    public function getListByCon(Request $request)
    {
        $data = $request->all();

        $houseContacts = HouseContactManager::getListByCon(['status' => '1'], false);

        return ApiResponse::makeResponse(true, $houseContacts, ApiResponse::SUCCESS_CODE);
    }
}